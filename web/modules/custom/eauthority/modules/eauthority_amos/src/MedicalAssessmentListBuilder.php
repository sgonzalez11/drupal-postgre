<?php

namespace Drupal\eauthority_amos;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Medical Assessment entities.
 *
 * @ingroup eauthority_amos
 */
class MedicalAssessmentListBuilder extends EntityListBuilder {

    /**
     * {@inheritdoc}
     */
    public function buildHeader() {
        $header['id'] = $this->t('Medical Assessment ID');
        $header['name'] = $this->t('Name');
        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity) {
        /* @var $entity \Drupal\eauthority_amos\Entity\MedicalAssessment */
        $row['id'] = $entity->id();
        $row['name'] = Link::createFromRoute(
                        $entity->label(), 'entity.medical_assessment.edit_form', ['medical_assessment' => $entity->id()]
        );
        return $row + parent::buildRow($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOperations(EntityInterface $entity) {
        $operations = [];
        if ($entity->access('cancel') && $entity->hasLinkTemplate('cancel-form')) {
            $operations['cancel'] = [
                'title' => $this->t('Cancel'),
                'weight' => 50,
                'url' => $this->ensureDestination($entity->toUrl('cancel-form')),
            ];
        }

        if ($entity->access('send_to_authority') && $entity->hasLinkTemplate('send-to-authority-form')) {
            $operations['send_to_authority'] = [
                'title' => $this->t('Send to Authority'),
                'weight' => 50,
                'url' => $this->ensureDestination($entity->toUrl('send-to-authority-form')),
            ];
        }

        if ($entity->access('close') && $entity->hasLinkTemplate('close-form')) {
            $operations['close'] = [
                'title' => $this->t('Close'),
                'weight' => 50,
                'url' => $this->ensureDestination($entity->toUrl('close-form')),
            ];
        }

        return $operations + parent::getDefaultOperations($entity);
    }

}
