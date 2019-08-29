<?php

namespace Drupal\eauthority_transactions\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Transaction entity.
 *
 * @ingroup eauthority_transactions
 *
 * @ContentEntityType(
 *   id = "transaction",
 *   label = @Translation("Transaction"),
 *   bundle_label = @Translation("Transaction type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority_transactions\TransactionListBuilder",
 *     "views_data" = "Drupal\eauthority_transactions\Entity\TransactionViewsData",
 *     "translation" = "Drupal\eauthority_transactions\TransactionTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority_transactions\Form\TransactionForm",
 *       "add" = "Drupal\eauthority_transactions\Form\TransactionForm",
 *       "edit" = "Drupal\eauthority_transactions\Form\TransactionForm",
 *       "delete" = "Drupal\eauthority_transactions\Form\TransactionDeleteForm",
 *     },
 *     "access" = "Drupal\eauthority_transactions\TransactionAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority_transactions\TransactionHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "transaction",
 *   data_table = "transaction_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer transaction entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/transaction/{transaction}",
 *     "add-page" = "/admin/structure/transaction/add",
 *     "add-form" = "/admin/structure/transaction/add/{transaction_type}",
 *     "edit-form" = "/admin/structure/transaction/{transaction}/edit",
 *     "delete-form" = "/admin/structure/transaction/{transaction}/delete",
 *     "collection" = "/admin/structure/transaction",
 *   },
 *   bundle_entity_type = "transaction_type",
 *   field_ui_base_route = "entity.transaction_type.edit_form"
 * )
 */
class Transaction extends ContentEntityBase implements TransactionInterface {

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
                ->setDescription(t('The user ID of author of the Transaction entity.'))
                ->setRevisionable(TRUE)
                ->setSetting('target_type', 'user')
                ->setSetting('handler', 'default');

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
                    'weight' => -20,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -20,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['service'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Service'))
                ->setSetting('target_type', 'node')
                ->setSetting('handler', 'default:node')
                ->setSetting('handler_settings', ['target_bundles' => ['service' => 'service']])
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -19,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'list_default',
                    'weight' => -19,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['customer'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Customer'))
                ->setSetting('target_type', 'customer')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -18,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'list_default',
                    'weight' => -18,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['has_appointment'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Has appointment?'))
                ->setDefaultValue(false)
                ->setDisplayOptions('form', [
                    'type' => 'boolean_checkbox',
                ])
                ->setDisplayConfigurable('form', TRUE);

        $fields['need_appointment'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Need an Appointment?'))
                ->setDefaultValue(false)
                ->setDisplayOptions('form', [
                    'type' => 'boolean_checkbox',
                ])
                ->setDisplayConfigurable('form', TRUE);

        $fields['transaction_status'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Transaction Status'))
                ->setSetting('target_type', 'transaction_status')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -17,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'list_default',
                    'weight' => -17,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['payment_reference'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Payment Reference'))
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
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['payment_status'] = BaseFieldDefinition::create('list_string')
                ->setLabel(t('Payment Status'))
                ->setTranslatable(true)
                ->setDefaultValue('OPN')
                ->setSettings([
                    'allowed_values' => [
                        'PAI' => 'PAID',
                        'PEN' => 'PENDING',
                    ],
                ])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -15,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'options_select',
                    'weight' => -15,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['payment_amount'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Payment Amount'))
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'string',
                    'weight' => -14,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -14,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['payment_date'] = BaseFieldDefinition::create('datetime')
                ->setLabel(t('Payment Date'))
                ->setSettings(['datetime_type' => 'date'])
                ->setDefaultValue('')
                ->setDisplayOptions('view', [
                    'label' => 'above',
                    'type' => 'datetime_default',
                    'settings' => [
                        'format_type' => 'medium',
                    ],
                    'weight' => -13,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'datetime_default',
                    'weight' => -13,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['submission'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Webform submission'))
                ->setSetting('target_type', 'webform_submission')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                  'label' => 'visible',
                  'type' => 'list_default',
                  'weight' => -18,
                ])
                ->setDisplayOptions('form', [
                  'type' => 'list_default',
                  'weight' => -18,
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
                ->setDescription(t('A boolean indicating whether the Transaction is published.'))
                ->setDefaultValue(TRUE)
                ->setDisplayOptions('form', [
            'type' => 'boolean_checkbox',
            'weight' => -3,
        ]);

        $fields['created'] = BaseFieldDefinition::create('created')
                ->setLabel(t('Created'))
                ->setDescription(t('The time that the entity was created.'));

        $fields['changed'] = BaseFieldDefinition::create('changed')
                ->setLabel(t('Changed'))
                ->setDescription(t('The time that the entity was last edited.'));

        return $fields;
    }

}
