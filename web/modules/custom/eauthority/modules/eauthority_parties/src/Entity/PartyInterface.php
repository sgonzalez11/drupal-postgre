<?php

namespace Drupal\eauthority_parties\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Party entities.
 *
 * @ingroup eauthority_parties
 */
interface PartyInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Party name.
   *
   * @return string
   *   Name of the Party.
   */
  public function getName();

  /**
   * Sets the Party name.
   *
   * @param string $name
   *   The Party name.
   *
   * @return \Drupal\eauthority_parties\Entity\PartyInterface
   *   The called Party entity.
   */
  public function setName($name);

  /**
   * Gets the Party creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Party.
   */
  public function getCreatedTime();

  /**
   * Sets the Party creation timestamp.
   *
   * @param int $timestamp
   *   The Party creation timestamp.
   *
   * @return \Drupal\eauthority_parties\Entity\PartyInterface
   *   The called Party entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Party published status indicator.
   *
   * Unpublished Party are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Party is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Party.
   *
   * @param bool $published
   *   TRUE to set this Party to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority_parties\Entity\PartyInterface
   *   The called Party entity.
   */
  public function setPublished($published);

}
