<?php

namespace Drupal\eauthority_transactions;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Transaction entity.
 *
 * @see \Drupal\eauthority_transactions\Entity\Transaction.
 */
class TransactionAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eauthority_transactions\Entity\TransactionInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished transaction entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published transaction entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit transaction entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete transaction entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add transaction entities');
  }

}
