<?php

namespace Drupal\eauthority_transactions\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Transaction type entity.
 *
 * @ConfigEntityType(
 *   id = "transaction_type",
 *   label = @Translation("Transaction type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority_transactions\TransactionTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\eauthority_transactions\Form\TransactionTypeForm",
 *       "edit" = "Drupal\eauthority_transactions\Form\TransactionTypeForm",
 *       "delete" = "Drupal\eauthority_transactions\Form\TransactionTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority_transactions\TransactionTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "transaction_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "transaction",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/transaction_type/{transaction_type}",
 *     "add-form" = "/admin/structure/transaction_type/add",
 *     "edit-form" = "/admin/structure/transaction_type/{transaction_type}/edit",
 *     "delete-form" = "/admin/structure/transaction_type/{transaction_type}/delete",
 *     "collection" = "/admin/structure/transaction_type"
 *   }
 * )
 */
class TransactionType extends ConfigEntityBundleBase implements TransactionTypeInterface {

  /**
   * The Transaction type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Transaction type label.
   *
   * @var string
   */
  protected $label;

}
