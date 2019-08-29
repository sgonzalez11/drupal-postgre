<?php

namespace Drupal\eauthority_transactions;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Transaction Status entity.
 *
 * @see \Drupal\eauthority_transactions\Entity\TransactionStatus.
 */
class TransactionStatusAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eauthority_transactions\Entity\TransactionStatusInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished transaction status entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published transaction status entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit transaction status entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete transaction status entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add transaction status entities');
  }

}
