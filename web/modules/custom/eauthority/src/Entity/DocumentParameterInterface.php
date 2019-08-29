<?php

namespace Drupal\eauthority\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Document Parameter entities.
 *
 * @ingroup eauthority
 */
interface DocumentParameterInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Document Parameter name.
   *
   * @return string
   *   Name of the Document Parameter.
   */
  public function getName();

  /**
   * Sets the Document Parameter name.
   *
   * @param string $name
   *   The Document Parameter name.
   *
   * @return \Drupal\eauthority\Entity\DocumentParameterInterface
   *   The called Document Parameter entity.
   */
  public function setName($name);

  /**
   * Gets the Document Parameter creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Document Parameter.
   */
  public function getCreatedTime();

  /**
   * Sets the Document Parameter creation timestamp.
   *
   * @param int $timestamp
   *   The Document Parameter creation timestamp.
   *
   * @return \Drupal\eauthority\Entity\DocumentParameterInterface
   *   The called Document Parameter entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Document Parameter published status indicator.
   *
   * Unpublished Document Parameter are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Document Parameter is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Document Parameter.
   *
   * @param bool $published
   *   TRUE to set this Document Parameter to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority\Entity\DocumentParameterInterface
   *   The called Document Parameter entity.
   */
  public function setPublished($published);

}
