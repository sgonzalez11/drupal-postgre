<?php

namespace Drupal\eauthority_parties\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Party entities.
 */
class PartyViewsData extends EntityViewsData {

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
