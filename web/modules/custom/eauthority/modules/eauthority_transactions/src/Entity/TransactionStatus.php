<?php

namespace Drupal\eauthority_transactions\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Transaction Status entity.
 *
 * @ingroup eauthority_transactions
 *
 * @ContentEntityType(
 *   id = "transaction_status",
 *   label = @Translation("Transaction Status"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority_transactions\TransactionStatusListBuilder",
 *     "views_data" = "Drupal\eauthority_transactions\Entity\TransactionStatusViewsData",
 *     "translation" = "Drupal\eauthority_transactions\TransactionStatusTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority_transactions\Form\TransactionStatusForm",
 *       "add" = "Drupal\eauthority_transactions\Form\TransactionStatusForm",
 *       "edit" = "Drupal\eauthority_transactions\Form\TransactionStatusForm",
 *       "delete" = "Drupal\eauthority_transactions\Form\TransactionStatusDeleteForm",
 *     },
 *     "access" = "Drupal\eauthority_transactions\TransactionStatusAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority_transactions\TransactionStatusHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "transaction_status",
 *   data_table = "transaction_status_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer transaction status entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/transaction_status/{transaction_status}",
 *     "add-form" = "/admin/structure/transaction_status/add",
 *     "edit-form" = "/admin/structure/transaction_status/{transaction_status}/edit",
 *     "delete-form" = "/admin/structure/transaction_status/{transaction_status}/delete",
 *     "collection" = "/admin/structure/transaction_status",
 *   },
 *   field_ui_base_route = "transaction_status.settings"
 * )
 */
class TransactionStatus extends ContentEntityBase implements TransactionStatusInterface {

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
                ->setDescription(t('The user ID of author of the Transaction Status entity.'))
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

        // description
        $fields['description'] = BaseFieldDefinition::create('string_long')
                ->setLabel(t('Description'))
                ->setTranslatable(true)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'text_default',
                    'weight' => -3,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'text_textarea',
                    'weight' => -3,
                    'rows' => 6,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        // Select list
        $fields['behaviorAs'] = BaseFieldDefinition::create('list_string')
                ->setLabel(t('Behavior As'))
                ->setTranslatable(true)
                ->setSettings([
                    'allowed_values' => [
                        'opn' => 'open',
                        'ipr' => 'in progress',
                        'clo' => 'closed',
                    ],
                ])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => 6,
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
                ->setDescription(t('A boolean indicating whether the Transaction Status is published.'))
                ->setDefaultValue(TRUE)
                /* ->setDisplayOptions('form', [
                  'type' => 'boolean_checkbox',
                  'weight' => -1,
                  ]) */;

        $fields['created'] = BaseFieldDefinition::create('created')
                ->setLabel(t('Created'))
                ->setDescription(t('The time that the entity was created.'));

        $fields['changed'] = BaseFieldDefinition::create('changed')
                ->setLabel(t('Changed'))
                ->setDescription(t('The time that the entity was last edited.'));

        return $fields;
    }

}
