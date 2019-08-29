<?php

namespace Drupal\eauthority_amos\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Medical Assessment Recommendations entity.
 *
 * @ingroup eauthority_amos
 *
 * @ContentEntityType(
 *   id = "med_assessmt_recommendation",
 *   label = @Translation("Medical Assessment Recommendations"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority_amos\MedicalAssessmentRecommendationListBuilder",
 *     "views_data" = "Drupal\eauthority_amos\Entity\MedicalAssessmentRecommendationViewsData",
 *     "translation" = "Drupal\eauthority_amos\MedicalAssessmentRecommendationTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority_amos\Form\MedicalAssessmentRecommendationForm",
 *       "add" = "Drupal\eauthority_amos\Form\MedicalAssessmentRecommendationForm",
 *       "edit" = "Drupal\eauthority_amos\Form\MedicalAssessmentRecommendationForm",
 *       "delete" = "Drupal\eauthority_amos\Form\MedicalAssessmentRecommendationDeleteForm",
 *     },
 *     "access" = "Drupal\eauthority_amos\MedicalAssessmentRecommendationAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority_amos\MedicalAssessmentRecommendationHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "med_assessmt_recommendation",
 *   data_table = "med_assessmt_recommendation_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer medical assessment recommendations entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/medical/med_assessmt_recommendation/{med_assessmt_recommendation}",
 *     "add-form" = "/medical/med_assessmt_recommendation/add",
 *     "edit-form" = "/medical/med_assessmt_recommendation/{med_assessmt_recommendation}/edit",
 *     "delete-form" = "/medical/med_assessmt_recommendation/{med_assessmt_recommendation}/delete",
 *     "collection" = "/medical/med_assessmt_recommendation",
 *   },
 *   field_ui_base_route = "med_assessmt_recommendation.settings"
 * )
 */
class MedicalAssessmentRecommendation extends ContentEntityBase implements MedicalAssessmentRecommendationInterface {

    use EntityChangedTrait;

    /**
     * {@inheritdoc}
     */
    public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
        parent::preCreate($storage_controller, $values);
        $values += [
            'user_id' => \Drupal::currentUser()->id(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return $this->get('name')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name) {
        $this->set('name', $name);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBehaviourAsLabel() {
        $allowed_values = $this->getFieldDefinition('behaviour_as')->getFieldStorageDefinition()->getSetting('allowed_values');
        $selected_value = $this->get('behaviour_as')->value;
        foreach ($allowed_values as $key => $value) {
            if ($selected_value === $key) {
                return $value;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBehaviourAs() {
        return $this->get('behaviour_as')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setBehaviourAs($behaviour_as) {
        $this->set('behaviour_as', $behaviour_as);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedTime() {
        return $this->get('created')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedTime($timestamp) {
        $this->set('created', $timestamp);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner() {
        return $this->get('user_id')->entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwnerId() {
        return $this->get('user_id')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwnerId($uid) {
        $this->set('user_id', $uid);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(UserInterface $account) {
        $this->set('user_id', $account->id());
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublished() {
        return (bool) $this->getEntityKey('status');
    }

    /**
     * {@inheritdoc}
     */
    public function setPublished($published) {
        $this->set('status', $published ? TRUE : FALSE);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
        $fields = parent::baseFieldDefinitions($entity_type);

        $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Authored by'))
                ->setDescription(t('The user ID of author of the Medical Assessment Recommendations entity.'))
                ->setRevisionable(TRUE)
                ->setSetting('target_type', 'user')
                ->setSetting('handler', 'default')
                ->setTranslatable(TRUE);

        $fields['name'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Recommendation'))
                ->setSettings([
                    'max_length' => 50,
                    'text_processing' => 0,
                ])
                ->setDefaultValue('')
                ->setDisplayOptions('view', [
                    'label' => 'above',
                    'type' => 'string',
                    'weight' => -4,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -4,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE)
                ->setRequired(TRUE);

        $fields['behaviour_as'] = BaseFieldDefinition::create('list_string')
                ->setLabel(t('Behaviour As'))
                ->setTranslatable(true)
                ->setDefaultValue('')
                ->setSettings([
                    'allowed_values' => [
                        'POS' => 'Positive',
                        'NEG' => 'Negative',
                    ],
                ])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'weight' => -3,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'options_select',
                    'weight' => -3,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['status'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Publishing status'))
                ->setDescription(t('A boolean indicating whether the Medical Assessment Recommendations is published.'))
                ->setDefaultValue(TRUE);

        $fields['created'] = BaseFieldDefinition::create('created')
                ->setLabel(t('Created'))
                ->setDescription(t('The time that the entity was created.'));

        $fields['changed'] = BaseFieldDefinition::create('changed')
                ->setLabel(t('Changed'))
                ->setDescription(t('The time that the entity was last edited.'));

        return $fields;
    }

}
