<?php

namespace Drupal\eauthority_amos\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Medical Assessment Recommendations entities.
 *
 * @ingroup eauthority_amos
 */
interface MedicalAssessmentRecommendationInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Medical Assessment Recommendations name.
   *
   * @return string
   *   Name of the Medical Assessment Recommendations.
   */
  public function getName();

  /**
   * Sets the Medical Assessment Recommendations name.
   *
   * @param string $name
   *   The Medical Assessment Recommendations name.
   *
   * @return \Drupal\eauthority_amos\Entity\MedicalAssessmentRecommendationInterface
   *   The called Medical Assessment Recommendations entity.
   */
  public function setName($name);

  /**
   * Gets the Medical Assessment Recommendations creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Medical Assessment Recommendations.
   */
  public function getCreatedTime();

  /**
   * Sets the Medical Assessment Recommendations creation timestamp.
   *
   * @param int $timestamp
   *   The Medical Assessment Recommendations creation timestamp.
   *
   * @return \Drupal\eauthority_amos\Entity\MedicalAssessmentRecommendationInterface
   *   The called Medical Assessment Recommendations entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Medical Assessment Recommendations published status indicator.
   *
   * Unpublished Medical Assessment Recommendations are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Medical Assessment Recommendations is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Medical Assessment Recommendations.
   *
   * @param bool $published
   *   TRUE to set this Medical Assessment Recommendations to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority_amos\Entity\MedicalAssessmentRecommendationInterface
   *   The called Medical Assessment Recommendations entity.
   */
  public function setPublished($published);

}
