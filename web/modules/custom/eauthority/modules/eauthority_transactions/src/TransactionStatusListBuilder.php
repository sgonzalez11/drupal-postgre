<?php

namespace Drupal\eauthority_transactions;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Transaction Status entities.
 *
 * @ingroup eauthority_transactions
 */
class TransactionStatusListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Transaction Status ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\eauthority_transactions\Entity\TransactionStatus */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.transaction_status.edit_form',
      ['transaction_status' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
