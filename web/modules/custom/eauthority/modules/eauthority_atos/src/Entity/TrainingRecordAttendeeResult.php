<?php

namespace Drupal\eauthority_atos\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Training Record Attendee Results entity.
 *
 * @ingroup eauthority_atos
 *
 * @ContentEntityType(
 *   id = "training_record_attendee_result",
 *   label = @Translation("Training Record Attendee Results"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority_atos\TrainingRecordAttendeeResultListBuilder",
 *     "views_data" = "Drupal\eauthority_atos\Entity\TrainingRecordAttendeeResultViewsData",
 *     "translation" = "Drupal\eauthority_atos\TrainingRecordAttendeeResultTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority_atos\Form\TrainingRecordAttendeeResultForm",
 *       "add" = "Drupal\eauthority_atos\Form\TrainingRecordAttendeeResultForm",
 *       "edit" = "Drupal\eauthority_atos\Form\TrainingRecordAttendeeResultForm",
 *       "delete" = "Drupal\eauthority_atos\Form\TrainingRecordAttendeeResultDeleteForm",
 *     },
 *     "access" = "Drupal\eauthority_atos\TrainingRecordAttendeeResultAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority_atos\TrainingRecordAttendeeResultHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "training_record_attendee_result",
 *   data_table = "training_record_attendee_result_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer training record attendee results entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/training/training_record_attendee_result/{training_record_attendee_result}",
 *     "add-form" = "/training/training_record_attendee_result/add",
 *     "edit-form" = "/training/training_record_attendee_result/{training_record_attendee_result}/edit",
 *     "delete-form" = "/training/training_record_attendee_result/{training_record_attendee_result}/delete",
 *     "collection" = "/training/training_record_attendee_result",
 *   },
 *   field_ui_base_route = "training_record_attendee_result.settings"
 * )
 */
class TrainingRecordAttendeeResult extends ContentEntityBase implements TrainingRecordAttendeeResultInterface {

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
                ->setDescription(t('The user ID of author of the Training Record Attendee Results entity.'))
                ->setRevisionable(TRUE)
                ->setSetting('target_type', 'user')
                ->setSetting('handler', 'default')
                ->setTranslatable(TRUE);

        $fields['name'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Result'))
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

        $fields['result_set_value'] = BaseFieldDefinition::create('list_string')
                ->setLabel(t('Behaviour As'))
                ->setSettings([
                    'allowed_values' => [
                        'POS' => t('Positive'),
                        'NEG' => t('Negative'),
                    ],
                ])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -2,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'options_select',
                    'weight' => -2,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['status'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Publishing status'))
                ->setDescription(t('A boolean indicating whether the Training Record Attendee Results is published.'))
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
