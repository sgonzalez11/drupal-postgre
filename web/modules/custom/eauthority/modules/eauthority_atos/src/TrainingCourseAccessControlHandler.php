<?php

namespace Drupal\eauthority_atos;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Training Course entity.
 *
 * @see \Drupal\eauthority_atos\Entity\TrainingCourse.
 */
class TrainingCourseAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eauthority_atos\Entity\TrainingCourseInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished training course entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published training course entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit training course entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete training course entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add training course entities');
  }

}