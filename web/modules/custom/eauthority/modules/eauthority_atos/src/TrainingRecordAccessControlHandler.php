<?php

namespace Drupal\eauthority_atos;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Training Record entity.
 *
 * @see \Drupal\eauthority_atos\Entity\TrainingRecord.
 */
class TrainingRecordAccessControlHandler extends EntityAccessControlHandler {

    /**
     * {@inheritdoc}
     */
    protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
        /** @var \Drupal\eauthority_atos\Entity\TrainingRecordInterface $entity */
        switch ($operation) {
            case 'view':
                if (!$entity->isPublished()) {
                    return AccessResult::allowedIfHasPermission($account, 'view unpublished training record entities');
                }
                return AccessResult::allowedIfHasPermission($account, 'view published training record entities');

            case 'update':
                return AccessResult::allowedIfHasPermission($account, 'edit training record entities');

            case 'delete':
                return AccessResult::allowedIfHasPermission($account, 'delete training record entities');

            case 'cancel':
                return AccessResult::allowedIfHasPermission($account, 'cancel training record entities');

            case 'report_start_date':
                return AccessResult::allowedIfHasPermission($account, 'report start date training record entities');

            case 'finish_course':
                return AccessResult::allowedIfHasPermission($account, 'finish course training record entities');
        }

        // Unknown operation, no opinion.
        return AccessResult::neutral();
    }

    /**
     * {@inheritdoc}
     */
    protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
        return AccessResult::allowedIfHasPermission($account, 'add training record entities');
    }

}
