<?php

namespace Drupal\eauthority_atos;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Customer Training Course entities.
 *
 * @ingroup eauthority_atos
 */
class CustomerTrainingCourseListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Customer Training Course ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\eauthority_atos\Entity\CustomerTrainingCourse */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.customer_training_courses.edit_form',
      ['customer_training_courses' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
