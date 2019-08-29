<?php

namespace Drupal\eauthority_amos\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Medical Examination type entity.
 *
 * @ConfigEntityType(
 *   id = "medical_examination_type",
 *   label = @Translation("Medical Examination type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority_amos\MedicalExaminationTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\eauthority_amos\Form\MedicalExaminationTypeForm",
 *       "edit" = "Drupal\eauthority_amos\Form\MedicalExaminationTypeForm",
 *       "delete" = "Drupal\eauthority_amos\Form\MedicalExaminationTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority_amos\MedicalExaminationTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "medical_examination_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "medical_examination",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/medical_examination_type/{medical_examination_type}",
 *     "add-form" = "/admin/structure/medical_examination_type/add",
 *     "edit-form" = "/admin/structure/medical_examination_type/{medical_examination_type}/edit",
 *     "delete-form" = "/admin/structure/medical_examination_type/{medical_examination_type}/delete",
 *     "collection" = "/admin/structure/medical_examination_type"
 *   }
 * )
 */
class MedicalExaminationType extends ConfigEntityBundleBase implements MedicalExaminationTypeInterface {

  /**
   * The Medical Examination type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Medical Examination type label.
   *
   * @var string
   */
  protected $label;

}
