<?php

namespace Drupal\eauthority_transactions\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Transaction Status entities.
 */
class TransactionStatusViewsData extends EntityViewsData {

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
