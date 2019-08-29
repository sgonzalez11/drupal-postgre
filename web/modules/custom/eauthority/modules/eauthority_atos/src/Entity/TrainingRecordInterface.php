<?php

namespace Drupal\eauthority_atos\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Training Record entities.
 *
 * @ingroup eauthority_atos
 */
interface TrainingRecordInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Training Record name.
   *
   * @return string
   *   Name of the Training Record.
   */
  public function getName();

  /**
   * Sets the Training Record name.
   *
   * @param string $name
   *   The Training Record name.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingRecordInterface
   *   The called Training Record entity.
   */
  public function setName($name);

  /**
   * Gets the Training Record creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Training Record.
   */
  public function getCreatedTime();

  /**
   * Sets the Training Record creation timestamp.
   *
   * @param int $timestamp
   *   The Training Record creation timestamp.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingRecordInterface
   *   The called Training Record entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Training Record published status indicator.
   *
   * Unpublished Training Record are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Training Record is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Training Record.
   *
   * @param bool $published
   *   TRUE to set this Training Record to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority_atos\Entity\TrainingRecordInterface
   *   The called Training Record entity.
   */
  public function setPublished($published);

}
