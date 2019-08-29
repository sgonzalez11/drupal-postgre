<?php

namespace Drupal\eauthority;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Customers entity.
 *
 * @see \Drupal\eauthority\Entity\Customer.
 */
class CustomerAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eauthority\Entity\CustomerInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished customers entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published customers entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit customers entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete customers entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add customers entities');
  }

}
