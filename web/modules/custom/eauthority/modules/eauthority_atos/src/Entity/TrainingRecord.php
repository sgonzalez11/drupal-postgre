<?php

namespace Drupal\eauthority_atos\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Training Record entity.
 *
 * @ingroup eauthority_atos
 *
 * @ContentEntityType(
 *   id = "training_record",
 *   label = @Translation("Training Record"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority_atos\TrainingRecordListBuilder",
 *     "views_data" = "Drupal\eauthority_atos\Entity\TrainingRecordViewsData",
 *     "translation" = "Drupal\eauthority_atos\TrainingRecordTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority_atos\Form\TrainingRecordForm",
 *       "add" = "Drupal\eauthority_atos\Form\TrainingRecordForm",
 *       "edit" = "Drupal\eauthority_atos\Form\TrainingRecordForm",
 *       "delete" = "Drupal\eauthority_atos\Form\TrainingRecordDeleteForm",
 *       "cancel" = "Drupal\eauthority_atos\Form\TrainingRecordCancelForm",
 *       "report_start_date" = "Drupal\eauthority_atos\Form\TrainingRecordReportStartDateForm",
 *       "finish_course" = "Drupal\eauthority_atos\Form\TrainingRecordFinishCourseForm",
 *     },
 *     "access" = "Drupal\eauthority_atos\TrainingRecordAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority_atos\TrainingRecordHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "training_record",
 *   data_table = "training_record_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer training record entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/training/training_record/{training_record}",
 *     "add-form" = "/training/training_record/add",
 *     "edit-form" = "/training/training_record/{training_record}/edit",
 *     "delete-form" = "/training/training_record/{training_record}/delete",
 *     "cancel-form" = "/training/training_record/{training_record}/cancel",
 *     "report-start-date-form" = "/training/training_record/{training_record}/report-start-date",
 *     "finish-course-form" = "/training/training_record/{training_record}/finish-course",
 *     "collection" = "/training/training_record",
 *   },
 *   field_ui_base_route = "training_record.settings"
 * )
 */
class TrainingRecord extends ContentEntityBase implements TrainingRecordInterface {

    //const DOCUMENT_PARAMETER = 'training_record';
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
    public function getEntityStatus() {
        return $this->get('entity_status')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityStatus($status) {
        $this->set('entity_status', $status);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityStatusLabel() {
        $allowed_values = $this->getFieldDefinition('entity_status')->getFieldStorageDefinition()->getSetting('allowed_values');
        $selected_value = $this->get('entity_status')->value;
        foreach ($allowed_values as $key => $value) {
            if ($selected_value === $key) {
                return $value;
            }
        }
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
    public function getNumber() {
        return $this->get('number')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setNumber($number) {
        $this->set('number', $number);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTrainingCourse() {
        return $this->get('training_course')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setTrainingCourse($training_course) {
        $this->set('training_course', $training_course);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocation() {
        return $this->get('location')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocation($location) {
        $this->set('location', $location);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccreditFlightExperience() {
        return $this->get('accredit_flight_experience')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getAircraftList() {
        $aircraft = array();
        if (isset($this->values['aircraft']['x-default'])) {
            foreach ($this->values['aircraft']['x-default'] as $key => $value) {
                $entityA = \Drupal::entityTypeManager()->getStorage('customer')->load($value['target_id'])->getName();
                $aircraft[] = $entityA;
            }
        }
        return $aircraft;
    }

    /**
     * {@inheritdoc}
     */
    public function getInstructorsList() {
        $instructors = array();
        if (isset($this->values['instructors']['x-default'])) {
            foreach ($this->values['instructors']['x-default'] as $key => $value) {
                $entityI = \Drupal::entityTypeManager()->getStorage('customer')->load($value['target_id'])->getName();
                $instructors[] = $entityI;
            }
        }
        return $instructors;
    }

    /**
     * {@inheritdoc}
     */
    public function getInstructors() {
        return $this->get('instructors')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setInstructors($instructors) {
        $this->set('instructors', $instructors);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStartDate() {
        return $this->get('start_date')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setStartDate($date) {
        $this->set('start_date', $date);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFinishDate() {
        return $this->get('finish_date')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setFinishDate($date) {
        $this->set('finish_date', $date);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEstimatedStartDate() {
        return $this->get('estimated_start_date')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setEstimatedStartDate($date) {
        $this->set('estimated_start_date', $date);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEstimatedFinishDate() {
        return $this->get('estimated_finish_date')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setEstimatedFinishDate($date) {
        $this->set('estimated_finish_date', $date);
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
                ->setDescription(t('The user ID of author of the Training Record entity.'))
                ->setRevisionable(TRUE)
                ->setSetting('target_type', 'user')
                ->setSetting('handler', 'default')
                ->setTranslatable(TRUE);

        $fields['name'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Identifier'))
                ->setSettings([
                    'max_length' => 50,
                    'text_processing' => 0,
                ])
                ->setDefaultValue('')
                ->setDisplayOptions('view', [
                    'label' => 'above',
                    'type' => 'string',
                    'weight' => -15,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -15,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['prefix'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Prefix'))
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'weight' => -14,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'hidden',
                    'weight' => -14,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['number'] = BaseFieldDefinition::create('integer')
                ->setLabel(t('Number'))
                ->setReadOnly(TRUE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'weight' => -13,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'hidden',
                    'weight' => -13,
                ])
                ->setDisplayConfigurable('view', TRUE);

        $fields['entity_status'] = BaseFieldDefinition::create('list_string')
                ->setLabel(t('Status'))
                ->setTranslatable(true)
                ->setDefaultValue('DRF')
                ->setSettings([
                    'allowed_values' => [
                        'DRF' => 'Draft',
                        'SCH' => 'Scheduled',
                        'IPR' => 'In Progress',
                        'CMP' => 'Completed',
                        'CLO' => 'Closed',
                        'CAN' => 'Cancel',
                    ],
                ])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -16,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'options_select',
                    'weight' => -16,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['customer'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Training Organization'))
                ->setSetting('target_type', 'customer')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['training_course'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Training Course'))
                ->setSetting('target_type', 'customer_training_courses')
                ->setSetting('handler', 'customer:trainingOrganization')
                ->setTranslatable(FALSE)
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['accredit_flight_experience'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Accredits Practical Flight Experience'))
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

        $fields['estimated_start_date'] = BaseFieldDefinition::create('datetime')
                ->setLabel(t('Start Date'))
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

        $fields['estimated_finish_date'] = BaseFieldDefinition::create('datetime')
                ->setLabel(t('Finish Date'))
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
                    'weight' => -9,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'datetime_default',
                    'weight' => -9,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['start_date'] = BaseFieldDefinition::create('datetime')
                ->setLabel(t('Start Date'))
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
                    'weight' => -8,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'datetime_default',
                    'weight' => -8,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['finish_date'] = BaseFieldDefinition::create('datetime')
                ->setLabel(t('Finish Date'))
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
                    'weight' => -7,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'datetime_default',
                    'weight' => -7,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['location'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Location'))
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'string',
                    'weight' => -6,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -6,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['training_record_attendee'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Attendee'))
                ->setSetting('target_type', 'training_record_attendee')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -5,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'entity_reference_autocomplete',
                    'weight' => -5,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['aircraft'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Aircraft'))
                ->setSetting('target_type', 'aircraft')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -4,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'entity_reference_autocomplete',
                    'weight' => -4,
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
                ->setSetting('handler', 'customer:instructor')
                ->setTranslatable(FALSE)
                ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -3,
                ])
                ->setDisplayOptions('form', array(
                    'type' => 'options_select',
                    'settings' => array(
                        'match_operator' => 'CONTAINS',
                        'size' => 60,
                        'placeholder' => '',
                    ),
                    'weight' => -2,
                ))
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['remarks'] = BaseFieldDefinition::create('string_long')
                ->setLabel(t('Remarks'))
                ->setTranslatable(true)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'text_default',
                    'weight' => -2,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'text_textarea',
                    'weight' => -2,
                    'rows' => 6,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['cancellation_date'] = BaseFieldDefinition::create('datetime')
                ->setLabel(t('Cancellation Date'))
                ->setSettings([
                    'datetime_type' => 'datetime',
                ])
                ->setDefaultValue('')
                ->setDisplayOptions('view', [
                    'label' => 'above',
                    'type' => 'datetime_default',
                    'settings' => [
                        'format_type' => 'medium',
                    ],
                    'weight' => -1,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'datetime_default',
                    'weight' => -1,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['cancellation_user'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Cancellation User'))
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'string',
                    'weight' => 0,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => 0,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['cancellation_reason'] = BaseFieldDefinition::create('string_long')
                ->setLabel(t('Cancellation Reason'))
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'text_textarea',
                    'weight' => 1,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'text_textarea',
                    'weight' => 1,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['status'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Publishing status'))
                ->setDescription(t('A boolean indicating whether the Training Record is published.'))
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
        if ($this->id()) {
        } else {
            $query = \Drupal::entityQuery('document_parameter')
                    ->condition('status', 1)
                    ->condition('name', 'training_record', 'LIKE')
                    ->range(0, 1);
            $entity_id = $query->execute();
            $entityDP = \Drupal::entityTypeManager()->getStorage('document_parameter')->load($entity_id[1]);
            $number = $entityDP->getNextNumber();
            $prefix = $entityDP->getPrefix();
            $this->setNumber($number);
            $this->setPrefix($prefix);
            $this->setName($prefix . '-' . $number);
            $this->set('customer', $_SESSION["current_ato_id"]);
        }
    }

    public function postSave(EntityStorageInterface $storage, $update = TRUE) {
        parent::postSave($storage, $update);
        if ($update == 0) {
            $query = \Drupal::entityQuery('document_parameter')
                    ->condition('status', 1)
                    ->condition('name', 'training_record', 'LIKE')
                    ->range(0, 1);
            $entity_id = $query->execute();
            $entityDP = \Drupal::entityTypeManager()->getStorage('document_parameter')->load($entity_id[1]);
            $number = $entityDP->getNextNumber();
            $number++;
            $entityDP->set('next_number', $number);
            $entityDP->save();
        }
    }

    public function cancel($cancellation_reason) {
        $cancellation_date = date('Y-m-d\TH:i:s', time());
        $cancellation_user = \Drupal::currentUser()->getAccountName();
        if ($this->canBeCanceled()) {
            $this->set('entity_status', 'CAN');
            $this->set('cancellation_reason', $cancellation_reason);
            $this->set('cancellation_user', $cancellation_user);
            $this->set('cancellation_date', $cancellation_date);
            $this->save();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function canBeCanceled() {
        $status = $this->get('entity_status')->value;
        if ($status == 'CMP' || $status == 'CLO' || $status == 'CAN') {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function reportStartDate($EstimatedStartDate, $EstimatedFinishDate) {
        if ($this->canBeStartDateReported()) {
            // Set the entity_status to Cancel.
            $this->set('entity_status', 'SCH');
            $this->setEstimatedStartDate($EstimatedStartDate);
            $this->setEstimatedFinishDate($EstimatedFinishDate);
            // Save the entity
            $this->save();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function canBeStartDateReported() {
        if ($this->get('entity_status')->value == 'DRF') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function finishCourse() {
        if ($this->checkCanBeFinished()) {
            $this->set('entity_status', 'CMP');
            $this->save();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkCanBeFinished() {
        $eReport = array();
        $eReport['errorExist'] = FALSE;
        if ($this->get('entity_status')->value == 'IPR') {
            $getAccreditFlightExperience = $this->getAccreditFlightExperience();
            $instructors = $this->getInstructorsList();
            if (!isset($instructors[0])) {
                $eReport['errorExist'] = TRUE;
                $eReport[] = t('Al menos un instructor debe ser seleccionado');
            }

            if (isset($getAccreditFlightExperience) && $getAccreditFlightExperience === '1') {
                $aircrafts = $this->getAircraftList();
                if (!isset($aircrafts[0])) {
                    $eReport['errorExist'] = TRUE;
                    $eReport[] = t('Al menos una aeronave debe ser seleccionada');
                }
            }
            return $eReport;
        } else {
            $eReport['errorExist'] = TRUE;
            $eReport[] = "No es posible finalizar el curso seleccionado. El mismo no es finalizable.";
            return $eReport;
        }
    }

}
