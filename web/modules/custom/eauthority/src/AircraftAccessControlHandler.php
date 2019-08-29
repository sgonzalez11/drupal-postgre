<?php

namespace Drupal\eauthority;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Aircraft entity.
 *
 * @see \Drupal\eauthority\Entity\Aircraft.
 */
class AircraftAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eauthority\Entity\AircraftInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished aircraft entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published aircraft entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit aircraft entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete aircraft entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add aircraft entities');
  }

}
