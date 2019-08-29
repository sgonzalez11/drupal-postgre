<?php

namespace Drupal\eauthority_amos\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Medical Assessment entities.
 *
 * @ingroup eauthority_amos
 */
interface MedicalAssessmentInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Medical Assessment name.
   *
   * @return string
   *   Name of the Medical Assessment.
   */
  public function getName();

  /**
   * Sets the Medical Assessment name.
   *
   * @param string $name
   *   The Medical Assessment name.
   *
   * @return \Drupal\eauthority_amos\Entity\MedicalAssessmentInterface
   *   The called Medical Assessment entity.
   */
  public function setName($name);

  /**
   * Gets the Medical Assessment creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Medical Assessment.
   */
  public function getCreatedTime();

  /**
   * Sets the Medical Assessment creation timestamp.
   *
   * @param int $timestamp
   *   The Medical Assessment creation timestamp.
   *
   * @return \Drupal\eauthority_amos\Entity\MedicalAssessmentInterface
   *   The called Medical Assessment entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Medical Assessment published status indicator.
   *
   * Unpublished Medical Assessment are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Medical Assessment is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Medical Assessment.
   *
   * @param bool $published
   *   TRUE to set this Medical Assessment to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority_amos\Entity\MedicalAssessmentInterface
   *   The called Medical Assessment entity.
   */
  public function setPublished($published);

}
