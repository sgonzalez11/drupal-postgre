<?php

namespace Drupal\eauthority_atos;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Attendee Experience entity.
 *
 * @see \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeExperience.
 */
class TrainingRecordAttendeeExperienceAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeExperienceInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished attendee experience entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published attendee experience entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit attendee experience entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete attendee experience entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add attendee experience entities');
  }

}
