<?php

namespace Drupal\eauthority_amos;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Medical Assessment Recommendations entities.
 *
 * @ingroup eauthority_amos
 */
class MedicalAssessmentRecommendationListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Medical Assessment Recommendations ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\eauthority_amos\Entity\MedicalAssessmentRecommendation */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.med_assessmt_recommendation.edit_form',
      ['med_assessmt_recommendation' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
