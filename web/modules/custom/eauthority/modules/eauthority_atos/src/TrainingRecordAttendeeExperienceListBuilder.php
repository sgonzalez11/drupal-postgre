<?php

namespace Drupal\eauthority_atos;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Attendee Experience entities.
 *
 * @ingroup eauthority_atos
 */
class TrainingRecordAttendeeExperienceListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Attendee Experience ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeExperience */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.training_record_attendee_exp.edit_form',
      ['training_record_attendee_exp' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
