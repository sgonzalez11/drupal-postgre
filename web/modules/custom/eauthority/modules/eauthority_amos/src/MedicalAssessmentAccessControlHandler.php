<?php

namespace Drupal\eauthority_amos;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Medical Assessment entity.
 *
 * @see \Drupal\eauthority_amos\Entity\MedicalAssessment.
 */
class MedicalAssessmentAccessControlHandler extends EntityAccessControlHandler {

    /**
     * {@inheritdoc}
     */
    protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
        /** @var \Drupal\eauthority_amos\Entity\MedicalAssessmentInterface $entity */
        switch ($operation) {
            case 'view':
                if (!$entity->isPublished()) {
                    return AccessResult::allowedIfHasPermission($account, 'view unpublished medical assessment entities');
                }
                return AccessResult::allowedIfHasPermission($account, 'view published medical assessment entities');

            case 'update':
                return AccessResult::allowedIfHasPermission($account, 'edit medical assessment entities');

            case 'delete':
                return AccessResult::allowedIfHasPermission($account, 'delete medical assessment entities');

            case 'cancel':
                return AccessResult::allowedIfHasPermission($account, 'allows users to cancel medical assessment entities');
            
            case 'send':
                return AccessResult::allowedIfHasPermission($account, "edit responsible medical examiner's declaration page of the medical assessment form");

            case 'close':
                return AccessResult::allowedIfHasPermission($account, "edit responsible medical assessor's declaration page of the medical assessment form");
        }

        // Unknown operation, no opinion.
        return AccessResult::neutral();
    }

    /**
     * {@inheritdoc}
     */
    protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
        return AccessResult::allowedIfHasPermission($account, 'add medical assessment entities');
    }

}
