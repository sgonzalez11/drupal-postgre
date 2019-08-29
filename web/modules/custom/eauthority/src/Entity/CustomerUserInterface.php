<?php

namespace Drupal\eauthority\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Customer User entities.
 *
 * @ingroup eauthority
 */
interface CustomerUserInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Customer User name.
   *
   * @return string
   *   Name of the Customer User.
   */
  public function getName();

  /**
   * Sets the Customer User name.
   *
   * @param string $name
   *   The Customer User name.
   *
   * @return \Drupal\eauthority\Entity\CustomerUserInterface
   *   The called Customer User entity.
   */
  public function setName($name);

  /**
   * Gets the Customer User creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Customer User.
   */
  public function getCreatedTime();

  /**
   * Sets the Customer User creation timestamp.
   *
   * @param int $timestamp
   *   The Customer User creation timestamp.
   *
   * @return \Drupal\eauthority\Entity\CustomerUserInterface
   *   The called Customer User entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Customer User published status indicator.
   *
   * Unpublished Customer User are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Customer User is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Customer User.
   *
   * @param bool $published
   *   TRUE to set this Customer User to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority\Entity\CustomerUserInterface
   *   The called Customer User entity.
   */
  public function setPublished($published);

}
