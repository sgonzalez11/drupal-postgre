<?php

namespace Drupal\eauthority_parties\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Party type entity.
 *
 * @ConfigEntityType(
 *   id = "party_type",
 *   label = @Translation("Party type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority_parties\PartyTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\eauthority_parties\Form\PartyTypeForm",
 *       "edit" = "Drupal\eauthority_parties\Form\PartyTypeForm",
 *       "delete" = "Drupal\eauthority_parties\Form\PartyTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority_parties\PartyTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "party_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "party",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/party_type/{party_type}",
 *     "add-form" = "/admin/structure/party_type/add",
 *     "edit-form" = "/admin/structure/party_type/{party_type}/edit",
 *     "delete-form" = "/admin/structure/party_type/{party_type}/delete",
 *     "collection" = "/admin/structure/party_type"
 *   }
 * )
 */
class PartyType extends ConfigEntityBundleBase implements PartyTypeInterface {

  /**
   * The Party type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Party type label.
   *
   * @var string
   */
  protected $label;

}
