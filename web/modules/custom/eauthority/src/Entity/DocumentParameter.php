<?php

namespace Drupal\eauthority\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Document Parameter entity.
 *
 * @ingroup eauthority
 *
 * @ContentEntityType(
 *   id = "document_parameter",
 *   label = @Translation("Document Parameter"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority\DocumentParameterListBuilder",
 *     "views_data" = "Drupal\eauthority\Entity\DocumentParameterViewsData",
 *     "translation" = "Drupal\eauthority\DocumentParameterTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority\Form\DocumentParameterForm",
 *       "add" = "Drupal\eauthority\Form\DocumentParameterForm",
 *       "edit" = "Drupal\eauthority\Form\DocumentParameterForm",
 *       "delete" = "Drupal\eauthority\Form\DocumentParameterDeleteForm",
 *     },
 *     "access" = "Drupal\eauthority\DocumentParameterAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority\DocumentParameterHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "document_parameter",
 *   data_table = "document_parameter_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer document parameter entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/document_parameter/{document_parameter}",
 *     "add-form" = "/admin/structure/document_parameter/add",
 *     "edit-form" = "/admin/structure/document_parameter/{document_parameter}/edit",
 *     "delete-form" = "/admin/structure/document_parameter/{document_parameter}/delete",
 *     "collection" = "/admin/structure/document_parameter",
 *   },
 *   field_ui_base_route = "document_parameter.settings"
 * )
 */
class DocumentParameter extends ContentEntityBase implements DocumentParameterInterface {

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
    public function getPrefix() {
        return $this->get('prefix')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setPrefix($prefix) {
        $this->set('prefix', $prefix);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getNextNumber() {
        return $this->get('next_number')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setNextNumber($number) {
        $this->set('next_number', $number);
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

    public function incrementNextNumber() {
      $this->setNextNumber($this->getNextNumber() + 1);
      $this->save();
    }

    /**
     * {@inheritdoc}
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
        $fields = parent::baseFieldDefinitions($entity_type);

        $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Authored by'))
                ->setDescription(t('The user ID of author of the Document Parameter entity.'))
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

        //prefix
        $fields['prefix'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Prefix'))
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'string',
                    'weight' => -2,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -2,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['next_number'] = BaseFieldDefinition::create('integer')
                ->setLabel(t('Next Number'))
                ->setReadOnly(TRUE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'weight' => -1,
                ])
                ->setDisplayOptions('form', [
                    'weight' => -1,
                ])
                ->setRequired(TRUE);

        $fields['status'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Publishing status'))
                ->setDescription(t('A boolean indicating whether the Document Parameter is published.'))
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
