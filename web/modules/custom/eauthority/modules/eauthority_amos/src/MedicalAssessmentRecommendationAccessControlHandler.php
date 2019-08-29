<?php

namespace Drupal\eauthority_amos;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Medical Assessment Recommendations entity.
 *
 * @see \Drupal\eauthority_amos\Entity\MedicalAssessmentRecommendation.
 */
class MedicalAssessmentRecommendationAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\eauthority_amos\Entity\MedicalAssessmentRecommendationInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished medical assessment recommendations entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published medical assessment recommendations entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit medical assessment recommendations entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete medical assessment recommendations entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add medical assessment recommendations entities');
  }

}
