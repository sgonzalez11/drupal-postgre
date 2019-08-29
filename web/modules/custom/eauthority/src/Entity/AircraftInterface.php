<?php

namespace Drupal\eauthority\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Aircraft entities.
 *
 * @ingroup eauthority
 */
interface AircraftInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Aircraft name.
   *
   * @return string
   *   Name of the Aircraft.
   */
  public function getName();

  /**
   * Sets the Aircraft name.
   *
   * @param string $name
   *   The Aircraft name.
   *
   * @return \Drupal\eauthority\Entity\AircraftInterface
   *   The called Aircraft entity.
   */
  public function setName($name);

  /**
   * Gets the Aircraft creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Aircraft.
   */
  public function getCreatedTime();

  /**
   * Sets the Aircraft creation timestamp.
   *
   * @param int $timestamp
   *   The Aircraft creation timestamp.
   *
   * @return \Drupal\eauthority\Entity\AircraftInterface
   *   The called Aircraft entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Aircraft published status indicator.
   *
   * Unpublished Aircraft are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Aircraft is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Aircraft.
   *
   * @param bool $published
   *   TRUE to set this Aircraft to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority\Entity\AircraftInterface
   *   The called Aircraft entity.
   */
  public function setPublished($published);

}
