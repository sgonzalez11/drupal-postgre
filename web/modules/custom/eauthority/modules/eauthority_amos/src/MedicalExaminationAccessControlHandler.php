<?php

namespace Drupal\eauthority_amos;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Medical Examination entity.
 *
 * @see \Drupal\eauthority_amos\Entity\MedicalExamination.
 */
class MedicalExaminationAccessControlHandler extends EntityAccessControlHandler {

    /**
     * {@inheritdoc}
     */
    protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
        /** @var \Drupal\eauthority_amos\Entity\MedicalExaminationInterface $entity */
        switch ($operation) {
            case 'view':
                if (!$entity->isPublished()) {
                    return AccessResult::allowedIfHasPermission($account, 'view unpublished medical examination entities');
                }
                return AccessResult::allowedIfHasPermission($account, 'view published medical examination entities');

            case 'update':
                return AccessResult::allowedIfHasPermission($account, 'edit medical examination entities');

            case 'delete':
                return AccessResult::allowedIfHasPermission($account, 'delete medical examination entities');

            case 'cancel':
                return AccessResult::allowedIfHasPermission($account, 'allows users to cancel medical examination entities');

            case 'finish':
                return AccessResult::allowedIfHasPermission($account, "allows users to finish an medical examination");

            case 'undo-completed':
                return AccessResult::allowedIfHasPermission($account, "allows users to undo completed examination");

            case 'undo-closed':
                return AccessResult::allowedIfHasPermission($account, "allows users to undo closed examination");

            case 'close':
                return AccessResult::allowedIfHasPermission($account, "allows users to close a medical examination");
        }

        // Unknown operation, no opinion.
        return AccessResult::neutral();
    }

    /**
     * {@inheritdoc}
     */
    protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
        return AccessResult::allowedIfHasPermission($account, 'add medical examination entities');
    }

}
