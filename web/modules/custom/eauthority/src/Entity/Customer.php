<?php

namespace Drupal\eauthority\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Customers entity.
 *
 * @ingroup eauthority
 *
 * @ContentEntityType(
 *   id = "customer",
 *   label = @Translation("Customers"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority\CustomerListBuilder",
 *     "views_data" = "Drupal\eauthority\Entity\CustomerViewsData",
 *     "translation" = "Drupal\eauthority\CustomerTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority\Form\CustomerForm",
 *       "add" = "Drupal\eauthority\Form\CustomerForm",
 *       "edit" = "Drupal\eauthority\Form\CustomerForm",
 *       "delete" = "Drupal\eauthority\Form\CustomerDeleteForm",
 *     },
 *     "access" = "Drupal\eauthority\CustomerAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority\CustomerHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "customer",
 *   data_table = "customer_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer customers entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/customers-mgmt/customer/{customer}",
 *     "add-form" = "/customers-mgmt/customer/add",
 *     "edit-form" = "/customers-mgmt/customer/{customer}/edit",
 *     "delete-form" = "/customers-mgmt/customer/{customer}/delete",
 *     "collection" = "/customers-mgmt/customer",
 *   },
 *   field_ui_base_route = "customer.settings"
 * )
 */
class Customer extends ContentEntityBase implements CustomerInterface {

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
    public function getIdNumber() {
        return $this->get('id_number')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setIdNumber($id_number) {
        $this->set('id_number', $id_number);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstName() {
        return $this->get('first_name')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstName($first_name) {
        $this->set('first_name', $first_name);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastName() {
        return $this->get('last_name')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastName($last_name) {
        $this->set('last_name', $last_name);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdType() {
        return $this->get('id_type')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setIdType($id_type) {
        $this->set('id_type', $id_type);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBirthDate() {
        return $this->get('birth_date')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setBirthDate($birth_date) {
        $this->set('birth_date', $birth_date);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAge() {
        $date = $this->get('birth_date')->value;
        $birthDate = explode("-", $date);
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md") ? ((date("Y") - $birthDate[0]) - 1) : (date("Y") - $birthDate[0]));
        return $age;
    }

    /**
     * {@inheritdoc}
     */
    public function getGender() {
        return $this->get('gender')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setGender($gender) {
        $this->set('gender', $gender);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress() {
        return $this->get('address')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setAddress($address) {
        $this->set('address', $address);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTelephone() {
        return $this->get('telephone')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setTelephone($telephone) {
        $this->set('telephone', $telephone);
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
                ->setDescription(t('The user ID of author of the Customers entity.'))
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
                    'type' => 'string_textfield',
                    'weight' => -4,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE)
                ->setRequired(TRUE);

        $fields['party_name'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Party Name'))
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
                ->setDisplayConfigurable('view', TRUE);

        $fields['id_type'] = BaseFieldDefinition::create('string')
                ->setLabel(t('ID Type'))
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'string',
                    'weight' => -3,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -3,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['id_number'] = BaseFieldDefinition::create('integer')
                ->setLabel(t('ID Number'))
                ->setReadOnly(TRUE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'weight' => -2,
                ])
                ->setDisplayOptions('form', [
            'weight' => -2,
        ]);

        $fields['type'] = BaseFieldDefinition::create('list_string')
                ->setLabel(t('Type'))
                ->setTranslatable(true)
                ->setSettings([
                    'allowed_values' => [
                        'PER' => 'Person',
                        'ORG' => 'Organization',
                    ],
                ])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -1,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'options_select',
                    'weight' => -1,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['aircraft'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('A/C'))
                ->setSetting('target_type', 'aircraft')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => 0,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'entity_reference_autocomplete',
                    'weight' => 0,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['instructors'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Instructors'))
                ->setSetting('target_type', 'customer')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => 1,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'entity_reference_autocomplete',
                    'weight' => 1,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['customer_training_courses'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Customer Training Courses'))
                ->setSetting('target_type', 'customer_training_courses')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => 3,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'entity_reference_autocomplete',
                    'weight' => 3,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['customer_profiles'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Customer Profiles'))
                ->setSetting('target_type', 'customer_profiles')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => 2,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'entity_reference_autocomplete',
                    'weight' => 2,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['customer_users'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Customer Users'))
                ->setSetting('target_type', 'customer_user')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => 4,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'entity_reference_autocomplete',
                    'weight' => 4,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['first_name'] = BaseFieldDefinition::create('string')
                ->setLabel(t('First Name'))
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
                ->setDisplayConfigurable('view', TRUE);

        $fields['middle_name'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Middle Name'))
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
                ->setDisplayConfigurable('view', TRUE);

        $fields['last_name'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Last Name'))
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
                ->setDisplayConfigurable('view', TRUE);

        $fields['birth_date'] = BaseFieldDefinition::create('datetime')
                ->setLabel(t('Birth Date'))
                ->setSettings([
                    'datetime_type' => 'date',
                ])
                ->setDefaultValue('')
                ->setDisplayOptions('view', [
                    'label' => 'above',
                    'type' => 'datetime_default',
                    'settings' => [
                        'format_type' => 'medium',
                    ],
                    'weight' => -10,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'datetime_default',
                    'weight' => -10,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['gender'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Gender'))
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
                ->setDisplayConfigurable('view', TRUE);

        $fields['is_active'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Is Active'))
                ->setDefaultValue(FALSE)
                ->setSettings(['on_label' => 'Yes', 'off_label' => 'No'])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'boolean',
                    'weight' => 11,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'boolean_checkbox',
                    'weight' => 11,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['party_image'] = BaseFieldDefinition::create('image')
                ->setLabel(t('Image'))
                ->setSettings([
                    'alt_field_required' => FALSE,
                    'file_extensions' => 'png jpg jpeg',
                ])
                ->setDisplayOptions('view', array(
                    'label' => 'hidden',
                    'type' => 'default',
                    'weight' => 0,
                ))
                ->setDisplayOptions('form', array(
                    'label' => 'hidden',
                    'type' => 'image_image',
                    'weight' => 0,
                ))
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['address'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Address'))
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
                ->setDisplayConfigurable('view', TRUE);

        $fields['telephone'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Telephone'))
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
                ->setDisplayConfigurable('view', TRUE);

        $fields['status'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Publishing status'))
                ->setDescription(t('A boolean indicating whether the Customers is published.'))
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
