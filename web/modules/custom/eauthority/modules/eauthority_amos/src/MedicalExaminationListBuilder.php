<?php

namespace Drupal\eauthority_amos;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Medical Examination entities.
 *
 * @ingroup eauthority_amos
 */
class MedicalExaminationListBuilder extends EntityListBuilder {

    /**
     * {@inheritdoc}
     */
    public function buildHeader() {
        $header['id'] = $this->t('Medical Examination ID');
        $header['name'] = $this->t('Name');
        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity) {
        /* @var $entity \Drupal\eauthority_amos\Entity\MedicalExamination */
        $row['id'] = $entity->id();
        $row['name'] = Link::createFromRoute(
                        $entity->label(), 'entity.medical_examination.edit_form', ['medical_examination' => $entity->id()]
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

        if ($entity->access('finish') && $entity->hasLinkTemplate('finish-form')) {
            $operations['finish'] = [
                'title' => $this->t('Finish'),
                'weight' => 50,
                'url' => $this->ensureDestination($entity->toUrl('finish-form')),
            ];
        }

        if ($entity->access('undo-completed') && $entity->hasLinkTemplate('undo-completed-form')) {
            $operations['undo_completed'] = [
                'title' => $this->t('Undo Completed'),
                'weight' => 50,
                'url' => $this->ensureDestination($entity->toUrl('undo-completed-form')),
            ];
        }

        if ($entity->access('undo-closed') && $entity->hasLinkTemplate('undo-closed-form')) {
            $operations['undo_closed'] = [
                'title' => $this->t('Undo Closed'),
                'weight' => 50,
                'url' => $this->ensureDestination($entity->toUrl('undo-closed-form')),
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
