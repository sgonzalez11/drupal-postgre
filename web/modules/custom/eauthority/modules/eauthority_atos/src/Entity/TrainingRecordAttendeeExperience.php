<?php

namespace Drupal\eauthority_atos\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Attendee Experience entity.
 *
 * @ingroup eauthority_atos
 *
 * @ContentEntityType(
 *   id = "training_record_attendee_exp",
 *   label = @Translation("Attendee Experience"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority_atos\TrainingRecordAttendeeExperienceListBuilder",
 *     "views_data" = "Drupal\eauthority_atos\Entity\TrainingRecordAttendeeExperienceViewsData",
 *     "translation" = "Drupal\eauthority_atos\TrainingRecordAttendeeExperienceTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority_atos\Form\TrainingRecordAttendeeExperienceForm",
 *       "add" = "Drupal\eauthority_atos\Form\TrainingRecordAttendeeExperienceForm",
 *       "edit" = "Drupal\eauthority_atos\Form\TrainingRecordAttendeeExperienceForm",
 *       "delete" = "Drupal\eauthority_atos\Form\TrainingRecordAttendeeExperienceDeleteForm",
 *     },
 *     "access" = "Drupal\eauthority_atos\TrainingRecordAttendeeExperienceAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority_atos\TrainingRecordAttendeeExperienceHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "training_record_attendee_exp",
 *   data_table = "training_record_attendee_exp_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer attendee experience entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/training/training_record_attendee_exp/{training_record_attendee_exp}",
 *     "add-form" = "/training/training_record_attendee_exp/add",
 *     "edit-form" = "/training/training_record_attendee_exp/{training_record_attendee_exp}/edit",
 *     "delete-form" = "/training/training_record_attendee_exp/{training_record_attendee_exp}/delete",
 *     "collection" = "/training/training_record_attendee_exp",
 *   },
 *   field_ui_base_route = "training_record_attendee_exp.settings"
 * )
 */
class TrainingRecordAttendeeExperience extends ContentEntityBase implements TrainingRecordAttendeeExperienceInterface {

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
    public function getFlightHours() {
        return $this->get('flight_hours')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setFlightHours($flightHours) {
        $this->set('flight_hours', $flightHours);
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getAircraftName() {
        $entityId = $this->values['aircraft']['x-default'][0]['target_id'];
        $entityName = \Drupal::entityTypeManager()->getStorage('aircraft')->load($entityId)->getName();
        return $entityName;
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
                ->setDescription(t('The user ID of author of the Attendee Experience entity.'))
                ->setRevisionable(TRUE)
                ->setSetting('target_type', 'user')
                ->setSetting('handler', 'default')
                ->setTranslatable(TRUE);

        $fields['name'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Name'))
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
                    'type' => 'hidden',
                    'weight' => -4,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['flight_hours'] = BaseFieldDefinition::create('float')
                ->setLabel(t('Total Flight Hours'))
                ->setDisplayOptions('form', array(
                    'type' => 'number',
                    'settings' => array(
                        'display_label' => TRUE,
                        'weight' => -3,
                    ),
                ))
                ->setDisplayOptions('view', array(
                    'label' => 'hidden',
                    'type' => 'number_decimal',
                    'weight' => -3,
                ))
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['aircraft'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Aircraft'))
                ->setSetting('target_type', 'aircraft')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -2,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'entity_reference_autocomplete',
                    'weight' => -2,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['flight_experience_types'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Flight Experience Types'))
                ->setSetting('target_type', 'flight_experience_type')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -1,
                ])
                ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
                ->setDisplayOptions('form', [
                    'type' => 'list_default',
                    'weight' => -1,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['status'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Publishing status'))
                ->setDescription(t('A boolean indicating whether the Attendee Experience is published.'))
                ->setDefaultValue(TRUE);

        $fields['created'] = BaseFieldDefinition::create('created')
                ->setLabel(t('Created'))
                ->setDescription(t('The time that the entity was created.'));

        $fields['changed'] = BaseFieldDefinition::create('changed')
                ->setLabel(t('Changed'))
                ->setDescription(t('The time that the entity was last edited.'));

        return $fields;
    }

    public function preSave(EntityStorageInterface $storage) {
        parent::preSave($storage);
        $name = $this->getFlightHours() . ' - FH - ' . $this->getAircraftName();
        $this->setName($name);
    }

}
