<?php

namespace Drupal\eauthority\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Identifier Types entities.
 *
 * @ingroup eauthority
 */
interface IdentifierTypeInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Identifier Types name.
   *
   * @return string
   *   Name of the Identifier Types.
   */
  public function getName();

  /**
   * Sets the Identifier Types name.
   *
   * @param string $name
   *   The Identifier Types name.
   *
   * @return \Drupal\eauthority\Entity\IdentifierTypeInterface
   *   The called Identifier Types entity.
   */
  public function setName($name);

  /**
   * Gets the Identifier Types creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Identifier Types.
   */
  public function getCreatedTime();

  /**
   * Sets the Identifier Types creation timestamp.
   *
   * @param int $timestamp
   *   The Identifier Types creation timestamp.
   *
   * @return \Drupal\eauthority\Entity\IdentifierTypeInterface
   *   The called Identifier Types entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Identifier Types published status indicator.
   *
   * Unpublished Identifier Types are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Identifier Types is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Identifier Types.
   *
   * @param bool $published
   *   TRUE to set this Identifier Types to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority\Entity\IdentifierTypeInterface
   *   The called Identifier Types entity.
   */
  public function setPublished($published);

}
