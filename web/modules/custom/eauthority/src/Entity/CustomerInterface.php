<?php

namespace Drupal\eauthority\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Customers entities.
 *
 * @ingroup eauthority
 */
interface CustomerInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Customers name.
   *
   * @return string
   *   Name of the Customers.
   */
  public function getName();

  /**
   * Sets the Customers name.
   *
   * @param string $name
   *   The Customers name.
   *
   * @return \Drupal\eauthority\Entity\CustomerInterface
   *   The called Customers entity.
   */
  public function setName($name);

  /**
   * Gets the Customers creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Customers.
   */
  public function getCreatedTime();

  /**
   * Sets the Customers creation timestamp.
   *
   * @param int $timestamp
   *   The Customers creation timestamp.
   *
   * @return \Drupal\eauthority\Entity\CustomerInterface
   *   The called Customers entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Customers published status indicator.
   *
   * Unpublished Customers are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Customers is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Customers.
   *
   * @param bool $published
   *   TRUE to set this Customers to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority\Entity\CustomerInterface
   *   The called Customers entity.
   */
  public function setPublished($published);

}
