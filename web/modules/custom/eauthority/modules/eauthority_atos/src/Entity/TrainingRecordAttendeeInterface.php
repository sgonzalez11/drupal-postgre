<?php

namespace Drupal\eauthority_atos\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Attendee entities.
 *
 * @ingroup eauthority_atos
 */
interface TrainingRecordAttendeeInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Attendee name.
   *
   * @return string
   *   Name of the Attendee.
   */
  public function getName();

  /**
   * Sets the Attendee name.
   *
   * @param string $name
   *   The Attendee name.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeInterface
   *   The called Attendee entity.
   */
  public function setName($name);

  /**
   * Gets the Attendee creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Attendee.
   */
  public function getCreatedTime();

  /**
   * Sets the Attendee creation timestamp.
   *
   * @param int $timestamp
   *   The Attendee creation timestamp.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeInterface
   *   The called Attendee entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Attendee published status indicator.
   *
   * Unpublished Attendee are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Attendee is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Attendee.
   *
   * @param bool $published
   *   TRUE to set this Attendee to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeInterface
   *   The called Attendee entity.
   */
  public function setPublished($published);

}
