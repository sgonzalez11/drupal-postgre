<?php

namespace Drupal\eauthority_atos\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Flight Experience Type entities.
 *
 * @ingroup eauthority_atos
 */
interface FlightExperienceTypeInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Flight Experience Type name.
   *
   * @return string
   *   Name of the Flight Experience Type.
   */
  public function getName();

  /**
   * Sets the Flight Experience Type name.
   *
   * @param string $name
   *   The Flight Experience Type name.
   *
   * @return \Drupal\eauthority_atos\Entity\FlightExperienceTypeInterface
   *   The called Flight Experience Type entity.
   */
  public function setName($name);

  /**
   * Gets the Flight Experience Type creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Flight Experience Type.
   */
  public function getCreatedTime();

  /**
   * Sets the Flight Experience Type creation timestamp.
   *
   * @param int $timestamp
   *   The Flight Experience Type creation timestamp.
   *
   * @return \Drupal\eauthority_atos\Entity\FlightExperienceTypeInterface
   *   The called Flight Experience Type entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Flight Experience Type published status indicator.
   *
   * Unpublished Flight Experience Type are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Flight Experience Type is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Flight Experience Type.
   *
   * @param bool $published
   *   TRUE to set this Flight Experience Type to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority_atos\Entity\FlightExperienceTypeInterface
   *   The called Flight Experience Type entity.
   */
  public function setPublished($published);

}
