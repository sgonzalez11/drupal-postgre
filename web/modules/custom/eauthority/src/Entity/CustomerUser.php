<?php

namespace Drupal\eauthority\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Customer User entity.
 *
 * @ingroup eauthority
 *
 * @ContentEntityType(
 *   id = "customer_user",
 *   label = @Translation("Customer User"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority\CustomerUserListBuilder",
 *     "views_data" = "Drupal\eauthority\Entity\CustomerUserViewsData",
 *     "translation" = "Drupal\eauthority\CustomerUserTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority\Form\CustomerUserForm",
 *       "add" = "Drupal\eauthority\Form\CustomerUserForm",
 *       "edit" = "Drupal\eauthority\Form\CustomerUserForm",
 *       "delete" = "Drupal\eauthority\Form\CustomerUserDeleteForm",
 *     },
 *     "access" = "Drupal\eauthority\CustomerUserAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority\CustomerUserHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "customer_user",
 *   data_table = "customer_user_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer customer user entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/customers-mgmt/customer_user/{customer_user}",
 *     "add-form" = "/customers-mgmt/customer_user/add",
 *     "edit-form" = "/customers-mgmt/customer_user/{customer_user}/edit",
 *     "delete-form" = "/customers-mgmt/customer_user/{customer_user}/delete",
 *     "collection" = "/customers-mgmt/customer_user",
 *   },
 *   field_ui_base_route = "customer_user.settings"
 * )
 */
class CustomerUser extends ContentEntityBase implements CustomerUserInterface {

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
                ->setDescription(t('The user ID of author of the Customer User entity.'))
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

        $fields['user'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('User'))
                ->setSetting('target_type', 'user')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'weight' => -3,
                ])
                ->setDisplayOptions('form', [
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

        $fields['role'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Role'))
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
                ->setDisplayConfigurable('view', TRUE)
                ->setRequired(TRUE);

        $fields['status'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Publishing status'))
                ->setDescription(t('A boolean indicating whether the Customer User is published.'))
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
