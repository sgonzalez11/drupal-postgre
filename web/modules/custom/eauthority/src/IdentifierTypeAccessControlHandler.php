<?php

namespace Drupal\eauthority;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Identifier Types entity.
 *
 * @see \Drupal\eauthority\Entity\IdentifierType.
 */
class IdentifierTypeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eauthority\Entity\IdentifierTypeInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished identifier types entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published identifier types entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit identifier types entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete identifier types entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add identifier types entities');
  }

}
