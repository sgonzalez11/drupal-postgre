<?php

namespace Drupal\eauthority;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Customer Profiles entity.
 *
 * @see \Drupal\eauthority\Entity\CustomerProfiles.
 */
class CustomerProfilesAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eauthority\Entity\CustomerProfilesInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished customer profiles entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published customer profiles entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit customer profiles entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete customer profiles entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add customer profiles entities');
  }

}
