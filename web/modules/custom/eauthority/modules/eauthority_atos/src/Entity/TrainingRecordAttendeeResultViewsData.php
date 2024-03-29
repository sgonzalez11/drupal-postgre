<?php

namespace Drupal\eauthority_atos\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Training Record Attendee Results entities.
 */
class TrainingRecordAttendeeResultViewsData extends EntityViewsData {

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
