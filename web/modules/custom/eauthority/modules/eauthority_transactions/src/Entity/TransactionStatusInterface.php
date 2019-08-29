<?php

namespace Drupal\eauthority_transactions\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Transaction Status entities.
 *
 * @ingroup eauthority_transactions
 */
interface TransactionStatusInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Transaction Status name.
   *
   * @return string
   *   Name of the Transaction Status.
   */
  public function getName();

  /**
   * Sets the Transaction Status name.
   *
   * @param string $name
   *   The Transaction Status name.
   *
   * @return \Drupal\eauthority_transactions\Entity\TransactionStatusInterface
   *   The called Transaction Status entity.
   */
  public function setName($name);

  /**
   * Gets the Transaction Status creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Transaction Status.
   */
  public function getCreatedTime();

  /**
   * Sets the Transaction Status creation timestamp.
   *
   * @param int $timestamp
   *   The Transaction Status creation timestamp.
   *
   * @return \Drupal\eauthority_transactions\Entity\TransactionStatusInterface
   *   The called Transaction Status entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Transaction Status published status indicator.
   *
   * Unpublished Transaction Status are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Transaction Status is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Transaction Status.
   *
   * @param bool $published
   *   TRUE to set this Transaction Status to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority_transactions\Entity\TransactionStatusInterface
   *   The called Transaction Status entity.
   */
  public function setPublished($published);

}
