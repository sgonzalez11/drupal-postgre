<?php

namespace Drupal\eauthority_atos;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Flight Experience Type entity.
 *
 * @see \Drupal\eauthority_atos\Entity\FlightExperienceType.
 */
class FlightExperienceTypeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eauthority_atos\Entity\FlightExperienceTypeInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished flight experience type entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published flight experience type entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit flight experience type entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete flight experience type entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add flight experience type entities');
  }

}
