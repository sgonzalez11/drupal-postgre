<?php

namespace Drupal\eauthority_atos\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Training Course entities.
 *
 * @ingroup eauthority_atos
 */
interface TrainingCourseInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Training Course name.
   *
   * @return string
   *   Name of the Training Course.
   */
  public function getName();

  /**
   * Sets the Training Course name.
   *
   * @param string $name
   *   The Training Course name.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingCourseInterface
   *   The called Training Course entity.
   */
  public function setName($name);

  /**
   * Gets the Training Course creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Training Course.
   */
  public function getCreatedTime();

  /**
   * Sets the Training Course creation timestamp.
   *
   * @param int $timestamp
   *   The Training Course creation timestamp.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingCourseInterface
   *   The called Training Course entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Training Course published status indicator.
   *
   * Unpublished Training Course are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Training Course is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Training Course.
   *
   * @param bool $published
   *   TRUE to set this Training Course to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingCourseInterface
   *   The called Training Course entity.
   */
  public function setPublished($published);

}
