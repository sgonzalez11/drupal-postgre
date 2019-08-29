<?php

namespace Drupal\eauthority_atos;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Training Record Attendee Results entity.
 *
 * @see \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeResult.
 */
class TrainingRecordAttendeeResultAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeResultInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished training record attendee results entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published training record attendee results entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit training record attendee results entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete training record attendee results entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add training record attendee results entities');
  }

}
