<?php

namespace Drupal\eauthority_atos\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Training Record Attendee Results entities.
 *
 * @ingroup eauthority_atos
 */
interface TrainingRecordAttendeeResultInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Training Record Attendee Results name.
   *
   * @return string
   *   Name of the Training Record Attendee Results.
   */
  public function getName();

  /**
   * Sets the Training Record Attendee Results name.
   *
   * @param string $name
   *   The Training Record Attendee Results name.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeResultInterface
   *   The called Training Record Attendee Results entity.
   */
  public function setName($name);

  /**
   * Gets the Training Record Attendee Results creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Training Record Attendee Results.
   */
  public function getCreatedTime();

  /**
   * Sets the Training Record Attendee Results creation timestamp.
   *
   * @param int $timestamp
   *   The Training Record Attendee Results creation timestamp.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeResultInterface
   *   The called Training Record Attendee Results entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Training Record Attendee Results published status indicator.
   *
   * Unpublished Training Record Attendee Results are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Training Record Attendee Results is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Training Record Attendee Results.
   *
   * @param bool $published
   *   TRUE to set this Training Record Attendee Results to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeResultInterface
   *   The called Training Record Attendee Results entity.
   */
  public function setPublished($published);

}
