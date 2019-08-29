<?php

namespace Drupal\eauthority_atos\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Training Course entity.
 *
 * @ingroup eauthority_atos
 *
 * @ContentEntityType(
 *   id = "training_course",
 *   label = @Translation("Training Course"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority_atos\TrainingCourseListBuilder",
 *     "views_data" = "Drupal\eauthority_atos\Entity\TrainingCourseViewsData",
 *     "translation" = "Drupal\eauthority_atos\TrainingCourseTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority_atos\Form\TrainingCourseForm",
 *       "add" = "Drupal\eauthority_atos\Form\TrainingCourseForm",
 *       "edit" = "Drupal\eauthority_atos\Form\TrainingCourseForm",
 *       "delete" = "Drupal\eauthority_atos\Form\TrainingCourseDeleteForm",
 *     },
 *     "access" = "Drupal\eauthority_atos\TrainingCourseAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority_atos\TrainingCourseHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "training_course",
 *   data_table = "training_course_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer training course entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/training/training_course/{training_course}",
 *     "add-form" = "/training/training_course/add",
 *     "edit-form" = "/training/training_course/{training_course}/edit",
 *     "delete-form" = "/training/training_course/{training_course}/delete",
 *     "collection" = "/training/training_course",
 *   },
 *   field_ui_base_route = "training_course.settings"
 * )
 */
class TrainingCourse extends ContentEntityBase implements TrainingCourseInterface {

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
    public function getCode() {
        return $this->get('code')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setCode($code) {
        $this->set('code', $code);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle() {
        return $this->get('title')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title) {
        $this->set('title', $title);
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
                ->setDescription(t('The user ID of author of the Training Course entity.'))
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

        $fields['code'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Code'))
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
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['title'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Title'))
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

        $fields['status'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Publishing status'))
                ->setDescription(t('A boolean indicating whether the Training Course is published.'))
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
        $this->setName($this->getCode() . '-' . $this->getTitle());
    }

}