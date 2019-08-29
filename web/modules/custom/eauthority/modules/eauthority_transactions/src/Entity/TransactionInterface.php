<?php

namespace Drupal\eauthority_transactions\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Transaction entities.
 *
 * @ingroup eauthority_transactions
 */
interface TransactionInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Transaction name.
   *
   * @return string
   *   Name of the Transaction.
   */
  public function getName();

  /**
   * Sets the Transaction name.
   *
   * @param string $name
   *   The Transaction name.
   *
   * @return \Drupal\eauthority_transactions\Entity\TransactionInterface
   *   The called Transaction entity.
   */
  public function setName($name);

  /**
   * Gets the Transaction creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Transaction.
   */
  public function getCreatedTime();

  /**
   * Sets the Transaction creation timestamp.
   *
   * @param int $timestamp
   *   The Transaction creation timestamp.
   *
   * @return \Drupal\eauthority_transactions\Entity\TransactionInterface
   *   The called Transaction entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Transaction published status indicator.
   *
   * Unpublished Transaction are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Transaction is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Transaction.
   *
   * @param bool $published
   *   TRUE to set this Transaction to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\eauthority_transactions\Entity\TransactionInterface
   *   The called Transaction entity.
   */
  public function setPublished($published);

}
