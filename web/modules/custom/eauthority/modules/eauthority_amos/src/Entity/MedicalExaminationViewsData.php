<?php

namespace Drupal\eauthority_amos\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Medical Examination entities.
 */
class MedicalExaminationViewsData extends EntityViewsData {

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
