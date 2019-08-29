<?php

namespace Drupal\eauthority;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Document Parameter entity.
 *
 * @see \Drupal\eauthority\Entity\DocumentParameter.
 */
class DocumentParameterAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eauthority\Entity\DocumentParameterInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished document parameter entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published document parameter entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit document parameter entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete document parameter entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add document parameter entities');
  }

}
