<?php

namespace Drupal\cars\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Car entity entities.
 *
 * @ingroup cars
 */
interface CarEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Car entity name.
   *
   * @return string
   *   Name of the Car entity.
   */
  public function getName();

  /**
   * Sets the Car entity name.
   *
   * @param string $name
   *   The Car entity name.
   *
   * @return \Drupal\cars\Entity\CarEntityInterface
   *   The called Car entity entity.
   */
  public function setName($name);

  /**
   * Gets the Car entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Car entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Car entity creation timestamp.
   *
   * @param int $timestamp
   *   The Car entity creation timestamp.
   *
   * @return \Drupal\cars\Entity\CarEntityInterface
   *   The called Car entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Car entity published status indicator.
   *
   * Unpublished Car entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Car entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Car entity.
   *
   * @param bool $published
   *   TRUE to set this Car entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\cars\Entity\CarEntityInterface
   *   The called Car entity entity.
   */
  public function setPublished($published);

}
