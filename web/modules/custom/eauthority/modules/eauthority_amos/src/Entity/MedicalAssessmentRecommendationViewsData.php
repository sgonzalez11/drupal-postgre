<?php

namespace Drupal\eauthority_amos\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Medical Assessment Recommendations entities.
 */
class MedicalAssessmentRecommendationViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
