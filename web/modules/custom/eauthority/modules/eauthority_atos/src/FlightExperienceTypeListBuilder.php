<?php

namespace Drupal\eauthority_atos;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Flight Experience Type entities.
 *
 * @ingroup eauthority_atos
 */
class FlightExperienceTypeListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Flight Experience Type ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\eauthority_atos\Entity\FlightExperienceType */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.flight_experience_type.edit_form',
      ['flight_experience_type' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
