<?php

namespace Drupal\eauthority_atos\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Attendee Experience entities.
 *
 * @ingroup eauthority_atos
 */
interface TrainingRecordAttendeeExperienceInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Attendee Experience name.
   *
   * @return string
   *   Name of the Attendee Experience.
   */
  public function getName();

  /**
   * Sets the Attendee Experience name.
   *
   * @param string $name
   *   The Attendee Experience name.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeExperienceInterface
   *   The called Attendee Experience entity.
   */
  public function setName($name);

  /**
   * Gets the Attendee Experience creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Attendee Experience.
   */
  public function getCreatedTime();

  /**
   * Sets the Attendee Experience creation timestamp.
   *
   * @param int $timestamp
   *   The Attendee Experience creation timestamp.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeExperienceInterface
   *   The called Attendee Experience entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Attendee Experience published status indicator.
   *
   * Unpublished Attendee Experience are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Attendee Experience is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Attendee Experience.
   *
   * @param bool $published
   *   TRUE to set this Attendee Experience to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeExperienceInterface
   *   The called Attendee Experience entity.
   */
  public function setPublished($published);

}
