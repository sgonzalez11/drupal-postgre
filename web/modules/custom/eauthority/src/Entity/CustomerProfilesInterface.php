<?php

namespace Drupal\eauthority\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Customer Profiles entities.
 *
 * @ingroup eauthority
 */
interface CustomerProfilesInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Customer Profiles name.
   *
   * @return string
   *   Name of the Customer Profiles.
   */
  public function getName();

  /**
   * Sets the Customer Profiles name.
   *
   * @param string $name
   *   The Customer Profiles name.
   *
   * @return \Drupal\eauthority\Entity\CustomerProfilesInterface
   *   The called Customer Profiles entity.
   */
  public function setName($name);

  /**
   * Gets the Customer Profiles creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Customer Profiles.
   */
  public function getCreatedTime();

  /**
   * Sets the Customer Profiles creation timestamp.
   *
   * @param int $timestamp
   *   The Customer Profiles creation timestamp.
   *
   * @return \Drupal\eauthority\Entity\CustomerProfilesInterface
   *   The called Customer Profiles entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Customer Profiles published status indicator.
   *
   * Unpublished Customer Profiles are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Customer Profiles is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Customer Profiles.
   *
   * @param bool $published
   *   TRUE to set this Customer Profiles to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority\Entity\CustomerProfilesInterface
   *   The called Customer Profiles entity.
   */
  public function setPublished($published);

}
