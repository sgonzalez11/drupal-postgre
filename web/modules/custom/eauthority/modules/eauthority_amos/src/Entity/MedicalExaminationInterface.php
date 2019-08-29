<?php

namespace Drupal\eauthority_amos\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Medical Examination entities.
 *
 * @ingroup eauthority_amos
 */
interface MedicalExaminationInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Medical Examination name.
   *
   * @return string
   *   Name of the Medical Examination.
   */
  public function getName();

  /**
   * Sets the Medical Examination name.
   *
   * @param string $name
   *   The Medical Examination name.
   *
   * @return \Drupal\eauthority_amos\Entity\MedicalExaminationInterface
   *   The called Medical Examination entity.
   */
  public function setName($name);

  /**
   * Gets the Medical Examination creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Medical Examination.
   */
  public function getCreatedTime();

  /**
   * Sets the Medical Examination creation timestamp.
   *
   * @param int $timestamp
   *   The Medical Examination creation timestamp.
   *
   * @return \Drupal\eauthority_amos\Entity\MedicalExaminationInterface
   *   The called Medical Examination entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Medical Examination published status indicator.
   *
   * Unpublished Medical Examination are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Medical Examination is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Medical Examination.
   *
   * @param bool $published
   *   TRUE to set this Medical Examination to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority_amos\Entity\MedicalExaminationInterface
   *   The called Medical Examination entity.
   */
  public function setPublished($published);

}
