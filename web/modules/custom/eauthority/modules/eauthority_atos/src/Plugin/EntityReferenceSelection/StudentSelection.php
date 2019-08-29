<?php

namespace Drupal\eauthority_atos\Plugin\EntityReferenceSelection;

use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;
use Drupal\Component\Utility\Html;

/**
 * Provides specific access control for the Customer entity type.
 *
 * @EntityReferenceSelection(
 *   id = "customer:student",
 *   label = @Translation("Student selection"),
 *   entity_types = {"customer"},
 *   group = "customer",
 *   weight = 1
 * )
 */
class StudentSelection extends DefaultSelection {

    /**
     * Builds an EntityQuery to get referenceable entities.
     *
     * @param string|null $match
     *   (Optional) Text to match the label against. Defaults to NULL.
     * @param string $match_operator
     *   (Optional) The operation the matching should be done with. Defaults
     *   to "CONTAINS".
     *
     * @return \Drupal\Core\Entity\Query\QueryInterface
     *   The EntityQuery object with the basic conditions and sorting applied to
     *   it.
     */
    protected function buildEntityQuery($match = NULL, $match_operator = 'CONTAINS') {
        $configuration = $this->getConfiguration();
        $target_type = $configuration['target_type'];
        $entity_type = $this->entityManager->getDefinition($target_type);

        $query = db_select('customer', 'c')->fields('c', array('id'));
        $query->innerJoin('customer_field_data', 'cfd', "c.id = cfd.id");
        $query->condition('cfd.type', 'PER', "=");

        // If 'target_bundles' is NULL, all bundles are referenceable, no further
        // conditions are needed.
        if (is_array($configuration['target_bundles'])) {
            // If 'target_bundles' is an empty array, no bundle is referenceable,
            // force the query to never return anything and bail out early.
            if ($configuration['target_bundles'] === []) {
                $query->condition($entity_type->getKey('id'), NULL, '=');
                return $query;
            } else {
                $query->condition($entity_type->getKey('bundle'), $configuration['target_bundles'], 'IN');
            }
        }

        if (isset($match) && $label_key = $entity_type->getKey('label')) {
            $query->condition($label_key, '%' . $match . '%', 'LIKE');
        }

        // Add entity-access tag.
        $query->addTag($target_type . '_access');

        // Add the Selection handler for system_query_entity_reference_alter().
        $query->addTag('entity_reference');
        $query->addMetaData('entity_reference_selection_handler', $this);

        // Add the sort option.
        if ($configuration['sort']['field'] !== '_none') {
            $query->sort($configuration['sort']['field'], $configuration['sort']['direction']);
        }
        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceableEntities($match = NULL, $match_operator = 'CONTAINS', $limit = 0) {
        $target_type = $this->getConfiguration()['target_type'];

        $query = $this->buildEntityQuery($match, $match_operator);
        if ($limit > 0) {
            $query->range(0, $limit);
        }

        $result = $query->execute();

        $item = array();
        foreach ($result as $key => $record) {
            $item[$record->id] = (float)$record->id;
        }

        if (empty($item)) {
            return [];
        }

        $options = [];
        $entities = $this->entityManager->getStorage($target_type)->loadMultiple($item);
        foreach ($entities as $entity_id => $entity) {
            $bundle = $entity->bundle();
            $options[$bundle][$entity_id] = Html::escape($this->entityManager->getTranslationFromContext($entity)->label());
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function validateReferenceableEntities(array $ids) {
        $result = [];
        if ($ids) {
            $target_type = $this->configuration['target_type'];
            $entity_type = $this->entityManager->getDefinition($target_type);
            $query = $this->buildEntityQuery();
            $result = $query
                    ->condition($entity_type->getKey('c.id'), $ids, 'IN')
                    ->execute();
        }

        return $result;
    }
    
}
