<?php

namespace Drupal\eauthority_atos\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Attendee entity.
 *
 * @ingroup eauthority_atos
 *
 * @ContentEntityType(
 *   id = "training_record_attendee",
 *   label = @Translation("Attendee"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority_atos\TrainingRecordAttendeeListBuilder",
 *     "views_data" = "Drupal\eauthority_atos\Entity\TrainingRecordAttendeeViewsData",
 *     "translation" = "Drupal\eauthority_atos\TrainingRecordAttendeeTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority_atos\Form\TrainingRecordAttendeeForm",
 *       "add" = "Drupal\eauthority_atos\Form\TrainingRecordAttendeeForm",
 *       "edit" = "Drupal\eauthority_atos\Form\TrainingRecordAttendeeForm",
 *       "delete" = "Drupal\eauthority_atos\Form\TrainingRecordAttendeeDeleteForm",
 *     },
 *     "access" = "Drupal\eauthority_atos\TrainingRecordAttendeeAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority_atos\TrainingRecordAttendeeHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "training_record_attendee",
 *   data_table = "training_record_attendee_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer attendee entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/training/training_record_attendee/{training_record_attendee}",
 *     "add-form" = "/training/training_record_attendee/add",
 *     "edit-form" = "/training/training_record_attendee/{training_record_attendee}/edit",
 *     "delete-form" = "/training/training_record_attendee/{training_record_attendee}/delete",
 *     "collection" = "/training/training_record_attendee",
 *   },
 *   field_ui_base_route = "training_record_attendee.settings"
 * )
 */
class TrainingRecordAttendee extends ContentEntityBase implements TrainingRecordAttendeeInterface {

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
    public function getCustomer() {
        return $this->get('customer')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerName() {
        $target_id = $this->get('customer')->target_id;
        return $entity = \Drupal::entityTypeManager()->getStorage('customer')->load($target_id);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomer($customer) {
        $this->set('customer', $customer);
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
                ->setDescription(t('The user ID of author of the Attendee entity.'))
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

        $fields['customer'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Attendee'))
                ->setSetting('target_type', 'customer')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -3,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'entity_reference_autocomplete',
                    'weight' => -3,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['result_set_value'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Result'))
                ->setSetting('target_type', 'training_record_attendee_result')
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
                ->setDisplayConfigurable('form', TRUE);

        $fields['certificates'] = BaseFieldDefinition::create('file')
                ->setLabel(t('Certificates'))
                ->setSettings([
                    'uri_scheme' => 'public',
                    'file_directory' => 'credentialing_providers',
                    'file_extensions' => 'png jpg jpeg pdf',
                ])
                ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
                ->setDisplayOptions('view', array(
                    'label' => 'above',
                    'weight' => -1,
                ))
                ->setDisplayOptions('form', array(
                    'weight' => -1,
                ))
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['training_record_attendee_exps'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Attendee Experiences'))
                ->setSetting('target_type', 'training_record_attendee_exp')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -0,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'entity_reference_autocomplete',
                    'weight' => -0,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['status'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Publishing status'))
                ->setDescription(t('A boolean indicating whether the Attendee is published.'))
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
        $customer = $this->getCustomerName()->getName();
        $this->setName($customer);
    }

}
