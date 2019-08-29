<?php

namespace Drupal\eauthority_atos;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Training Record Attendee Results entities.
 *
 * @ingroup eauthority_atos
 */
class TrainingRecordAttendeeResultListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Training Record Attendee Results ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeResult */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.training_record_attendee_result.edit_form',
      ['training_record_attendee_result' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
