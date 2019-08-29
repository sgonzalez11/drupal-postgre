<?php

namespace Drupal\eauthority_atos\Plugin\EntityReferenceSelection;

use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;
use Drupal\Component\Utility\Html;

/**
 * Provides specific access control for the Customer entity type.
 *
 * @EntityReferenceSelection(
 *   id = "customer:aircraft",
 *   label = @Translation("Aircraft selection"),
 *   entity_types = {"customer"},
 *   group = "customer",
 *   weight = 1
 * )
 */
class AircraftSelection extends DefaultSelection {

    /**
     * {@inheritdoc}
     */
    protected function buildEntityQuery($match = NULL, $match_operator = 'CONTAINS') {
        $configuration = $this->getConfiguration();
        $target_type = $configuration['target_type'];
        $entity_type = $this->entityManager->getDefinition($target_type);
        $query = db_select('customer', 'c')->fields('ci', array('aircraft_target_id'));
        $query->innerJoin('customer_field_data', 'cfd', "c.id = cfd.id");
        $query->innerJoin('customer__aircraft', 'ci', "cfd.id = ci.entity_id");
        $query->where('(c.id = :toid)', array(':toid' => $_SESSION["current_ato_id"]));

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
        //$query = parent::buildEntityQuery($match, $match_operator);
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
            $item[$record->aircraft_target_id] = (float)$record->aircraft_target_id;
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

}
