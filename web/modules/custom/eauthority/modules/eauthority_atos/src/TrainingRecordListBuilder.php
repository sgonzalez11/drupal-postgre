<?php

namespace Drupal\eauthority_atos;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Training Record entities.
 *
 * @ingroup eauthority_atos
 */
class TrainingRecordListBuilder extends EntityListBuilder {

    /**
     * {@inheritdoc}
     */
    public function buildHeader() {
        $header['training_course'] = $this->t('Training Course');
        $header['start_date'] = $this->t('Start Date');
        $header['finish_date'] = $this->t('Finish Date');
        $header['entity_status'] = $this->t('Status');
        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity) {
        /* @var $entity \Drupal\eauthority_atos\Entity\TrainingRecord */
        $tcid = $entity->getTrainingCourse();
        $entityTC = \Drupal::entityTypeManager()
                ->getStorage('training_course')
                ->load($tcid);
        $row['training_course'] = $entity->toLink($entity->getName());
        $dateFormatter = \Drupal::service('date.formatter');
        if ($entity->getStartDate()){
            $row['start_date'] = $dateFormatter->format(strtotime($entity->getStartDate()), 'short');
        }else{
            $row['start_date'] = '';
        }
        if ($entity->getFinishDate()){
            $row['finish_date'] = $dateFormatter->format(strtotime($entity->getFinishDate()), 'short');
        }else{
            $row['finish_date'] = '';
        }
        $row['entity_status'] = $entity->getEntityStatusLabel();
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
          'url' => 'myURL.com',
        ];
      }

      if ($entity->access('report_start_date') && $entity->hasLinkTemplate('report-start-date-form')) {
        $operations['report_start_date'] = [
          'title' => $this->t('Report Start Date'),
          'weight' => 50,
          'url' => $this->ensureDestination($entity->toUrl('report-start-date-form')),
        ];
      }

      if ($entity->access('finish_course') && $entity->hasLinkTemplate('finish-course-form')) {
        $operations['finish_course'] = [
          'title' => $this->t('Finish Course'),
          'weight' => 50,
          'url' => $this->ensureDestination($entity->toUrl('finish-course-form')),
        ];
      }
      
      if ($entity->access('update') && $entity->hasLinkTemplate('edit-form')) {
        $operations['edit'] = [
          'title' => $this->t('Edit'),
          'weight' => 10,
          'url' => $this->ensureDestination($entity->toUrl('edit-form')),
        ];
      }
      if ($entity->access('delete') && $entity->hasLinkTemplate('delete-form')) {
        $operations['delete'] = [
          'title' => $this->t('Delete'),
          'weight' => 100,
          'url' => $this->ensureDestination($entity->toUrl('delete-form')),
        ];
      }

      return $operations;
    }

}