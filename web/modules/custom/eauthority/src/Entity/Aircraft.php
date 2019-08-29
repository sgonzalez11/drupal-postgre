<?php

namespace Drupal\eauthority\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Aircraft entity.
 *
 * @ingroup eauthority
 *
 * @ContentEntityType(
 *   id = "aircraft",
 *   label = @Translation("Aircraft"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority\AircraftListBuilder",
 *     "views_data" = "Drupal\eauthority\Entity\AircraftViewsData",
 *     "translation" = "Drupal\eauthority\AircraftTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority\Form\AircraftForm",
 *       "add" = "Drupal\eauthority\Form\AircraftForm",
 *       "edit" = "Drupal\eauthority\Form\AircraftForm",
 *       "delete" = "Drupal\eauthority\Form\AircraftDeleteForm",
 *     },
 *     "access" = "Drupal\eauthority\AircraftAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority\AircraftHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "aircraft",
 *   data_table = "aircraft_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer aircraft entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/customers-mgmt/aircraft/{aircraft}",
 *     "add-form" = "/customers-mgmt/aircraft/add",
 *     "edit-form" = "/customers-mgmt/aircraft/{aircraft}/edit",
 *     "delete-form" = "/customers-mgmt/aircraft/{aircraft}/delete",
 *     "collection" = "/customers-mgmt/aircraft",
 *   },
 *   field_ui_base_route = "aircraft.settings"
 * )
 */
class Aircraft extends ContentEntityBase implements AircraftInterface {

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
    public function setManufacturer($manufacturer) {
        $this->set('manufacturer', $manufacturer);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getManufacturer() {
        return $this->get('manufacturer')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getModel() {
        return $this->get('model')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setModel($model) {
        $this->set('model', $model);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType() {
        return $this->get('type')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type) {
        $this->set('type', $type);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getVariant() {
        return $this->get('variant')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setVariant($variant) {
        $this->set('variant', $variant);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegistration() {
        return $this->get('registration')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setRegistration($registration) {
        $this->set('registration', $registration);
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
                ->setDescription(t('The user ID of author of the Aircraft entity.'))
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
                    'weight' => -16,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -16,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE)
                ->setRequired(TRUE);

        $fields['registration'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Registration'))
                ->setSettings([
                    'max_length' => 50,
                    'text_processing' => 0,
                ])
                ->setDefaultValue('')
                ->setDisplayOptions('view', [
                    'label' => 'above',
                    'type' => 'string',
                    'weight' => -14,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -14,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE)
                ->setRequired(TRUE);

        $fields['serial_number'] = BaseFieldDefinition::create('string')
                ->setLabel(t('S/N'))
                ->setSettings([
                    'max_length' => 50,
                    'text_processing' => 0,
                ])
                ->setDefaultValue('')
                ->setDisplayOptions('view', [
                    'label' => 'above',
                    'type' => 'string',
                    'weight' => -12,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -12,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['manufacturer'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Manufacturer'))
                ->setSettings([
                    'max_length' => 50,
                    'text_processing' => 0,
                ])
                ->setDefaultValue('')
                ->setDisplayOptions('view', [
                    'label' => 'above',
                    'type' => 'string',
                    'weight' => -10,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -10,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['model'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Model'))
                ->setSettings([
                    'max_length' => 50,
                    'text_processing' => 0,
                ])
                ->setDefaultValue('')
                ->setDisplayOptions('view', [
                    'label' => 'above',
                    'type' => 'string',
                    'weight' => -8,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -8,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['type'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Type'))
                ->setSettings([
                    'max_length' => 50,
                    'text_processing' => 0,
                ])
                ->setDefaultValue('')
                ->setDisplayOptions('view', [
                    'label' => 'above',
                    'type' => 'string',
                    'weight' => -6,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -6,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['variant'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Variant'))
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

        $fields['designator'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Designator'))
                ->setSettings([
                    'max_length' => 50,
                    'text_processing' => 0,
                ])
                ->setDefaultValue('')
                ->setDisplayOptions('view', [
                    'label' => 'above',
                    'type' => 'string',
                    'weight' => -2,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -2,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['status'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Publishing status'))
                ->setDescription(t('A boolean indicating whether the Aircraft is published.'))
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
        //$name = $this->getManufacturer() . ' ' . $this->getModel() . '-' . $this->getType() . ' ' . $this->getVariant() . ' ' . $this->getRegistration();
        //$this->setName($name);
    }

}
