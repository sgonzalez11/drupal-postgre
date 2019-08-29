<?php

namespace Drupal\eauthority\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
// cambiar el MN por customer_profile
/**
 * Defines the Customer Profiles entity.
 *
 * @ingroup eauthority
 *
 * @ContentEntityType(
 *   id = "customer_profiles",
 *   label = @Translation("Customer Profiles"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority\CustomerProfilesListBuilder",
 *     "views_data" = "Drupal\eauthority\Entity\CustomerProfilesViewsData",
 *     "translation" = "Drupal\eauthority\CustomerProfilesTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority\Form\CustomerProfilesForm",
 *       "add" = "Drupal\eauthority\Form\CustomerProfilesForm",
 *       "edit" = "Drupal\eauthority\Form\CustomerProfilesForm",
 *       "delete" = "Drupal\eauthority\Form\CustomerProfilesDeleteForm",
 *     },
 *     "access" = "Drupal\eauthority\CustomerProfilesAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority\CustomerProfilesHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "customer_profiles",
 *   data_table = "customer_profiles_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer customer profiles entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/customer-profiles/customer_profiles/{customer_profiles}",
 *     "add-form" = "/customer-profiles/customer_profiles/add",
 *     "edit-form" = "/customer-profiles/customer_profiles/{customer_profiles}/edit",
 *     "delete-form" = "/customer-profiles/customer_profiles/{customer_profiles}/delete",
 *     "collection" = "/customer-profiles/customer_profiles",
 *   },
 *   field_ui_base_route = "customer_profiles.settings"
 * )
 */
class CustomerProfiles extends ContentEntityBase implements CustomerProfilesInterface {

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
                ->setDescription(t('The user ID of author of the Customer Profiles entity.'))
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

        $fields['code'] = BaseFieldDefinition::create('string')
                ->setLabel(t('ID Type'))
                ->setSettings([
                    'max_length' => 50,
                    'text_processing' => 0,
                ])
                ->setDefaultValue('')
                ->setDisplayOptions('view', [
                    'label' => 'above',
                    'type' => 'string',
                    'weight' => -3,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -3,
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
                        'STD' => 'Student',
                        'INS' => 'Instructor',
                        'ATO' => 'Approved Training Organization',
                        'AMO' => 'Aero Medical Organization',
                        'AIR' => 'Airline',
                        'MEX' => 'Medical Examiner',
                        'MAS' => 'Medical Assessor',
                        'GEN' => 'Generic',
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
                ->setDescription(t('A boolean indicating whether the Customer Profiles is published.'))
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
