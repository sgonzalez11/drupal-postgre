<?php

namespace Drupal\eauthority_amos\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Access\AccessResult;
use Drupal\eauthority_amos\Entity\MedicalAssessment;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Defines the Medical Examination entity.
 *
 * @ingroup eauthority_amos
 *
 * @ContentEntityType(
 *   id = "medical_examination",
 *   label = @Translation("Medical Examination"),
 *   bundle_label = @Translation("Medical Examination type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority_amos\MedicalExaminationListBuilder",
 *     "views_data" = "Drupal\eauthority_amos\Entity\MedicalExaminationViewsData",
 *     "translation" = "Drupal\eauthority_amos\MedicalExaminationTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority_amos\Form\MedicalExaminationForm",
 *       "add" = "Drupal\eauthority_amos\Form\MedicalExaminationForm",
 *       "edit" = "Drupal\eauthority_amos\Form\MedicalExaminationForm",
 *       "delete" = "Drupal\eauthority_amos\Form\MedicalExaminationDeleteForm",
 *       "cancel" = "Drupal\eauthority_amos\Form\MedicalExaminationCancelForm",
 *       "finish" = "Drupal\eauthority_amos\Form\MedicalExaminationFinishForm",
 *       "undo_completed" = "Drupal\eauthority_amos\Form\MedicalExaminationUndoCompletedForm",
 *       "undo_closed" = "Drupal\eauthority_amos\Form\MedicalExaminationUndoClosedForm",
 *       "close" = "Drupal\eauthority_amos\Form\MedicalExaminationCloseForm",
 *     },
 *     "access" = "Drupal\eauthority_amos\MedicalExaminationAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority_amos\MedicalExaminationHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "medical_examination",
 *   data_table = "medical_examination_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer medical examination entities",
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
 *     "canonical" = "/medical/medical_examination/{medical_examination}",
 *     "add-page" = "/medical/medical_examination/add",
 *     "add-form" = "/medical/medical_examination/add/{medical_examination_type}",
 *     "edit-form" = "/medical/medical_examination/{medical_examination}/edit",
 *     "delete-form" = "/medical/medical_examination/{medical_examination}/delete",
 *     "cancel-form" = "/medical/medical_examination/{medical_examination}/cancel",
 *     "finish-form" = "/medical/medical_examination/{medical_examination}/finish",
 *     "undo-completed-form" = "/medical/medical_examination/{medical_examination}/undo-completed",
 *     "undo-closed-form" = "/medical/medical_examination/{medical_examination}/undo-closed",
 *     "close-form" = "/medical/medical_examination/{medical_examination}/close",
 *     "collection" = "/medical/medical_examination",
 *   },
 *   bundle_entity_type = "medical_examination_type",
 *   field_ui_base_route = "entity.medical_examination_type.edit_form"
 * )
 */
class MedicalExamination extends ContentEntityBase implements MedicalExaminationInterface {

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
    public function getCreationDate() {
        return $this->get('creation_date')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreationDate($timestamp) {
        $this->set('creation_date', $timestamp);
        return $this;
    }

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
                ->setDescription(t('The user ID of author of the Medical Examination entity.'))
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
                    'weight' => -20,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -20,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['prefix'] = BaseFieldDefinition::create('string')
                ->setLabel(t('Prefix'))
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'string',
                    'weight' => -18,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -18,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['number'] = BaseFieldDefinition::create('integer')
                ->setLabel(t('Number'))
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'string',
                    'weight' => -16,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -16,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $bundlesTypes = array();
        $MEBundles = entity_get_bundles('medical_examination');
        foreach ($MEBundles as $key => $bundle) {
            $bundlesTypes[$key] = $bundle['label'];
        }
        $fields['medical_examination_type'] = BaseFieldDefinition::create('list_string')
                ->setLabel(t('Examiation Type'))
                ->setTranslatable(true)
                ->setSettings([
                    'allowed_values' => [
                        $bundlesTypes
                    ],
                ])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'weight' => -4,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'options_select',
                    'weight' => -4,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['medical_assessment'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Medical Assessment'))
                ->setSetting('target_type', 'medical_assessment')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'weight' => -12,
                ])
                ->setDisplayOptions('form', [
                    'weight' => -12,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['scheduled_appointment_date'] = BaseFieldDefinition::create('datetime')
                ->setLabel(t('Scheduled Appointment Date'))
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
                    'weight' => -14,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'datetime_default',
                    'weight' => -14,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['medical_examiner'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Medical Examiner'))
                ->setSetting('target_type', 'customer')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'weight' => -12,
                ])
                ->setDisplayOptions('form', [
                    'weight' => -12,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['examination_date'] = BaseFieldDefinition::create('datetime')
                ->setLabel(t('Examination Date'))
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

        $fields['examination_notes'] = BaseFieldDefinition::create('string_long')
                ->setLabel(t('Examination Notes'))
                ->setTranslatable(true)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'text_default',
                    'weight' => -6,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'text_textarea',
                    'weight' => -6,
                    'rows' => 6,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['entity_status'] = BaseFieldDefinition::create('list_string')
                ->setLabel(t('Status'))
                ->setTranslatable(true)
                ->setDefaultValue('OPN')
                ->setSettings([
                    'allowed_values' => [
                        'OPN' => 'OPEN',
                        'SCH' => 'SCHEDULED',
                        'CMP' => 'COMPLETED',
                        'PAS' => 'PENDING ASSESSMENT',
                        'ASC' => 'ASSESSMENT SCHEDULED',
                        'CLO' => 'CLOSED',
                        'CAN' => 'CANCEL',
                    ],
                ])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'weight' => -4,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'options_select',
                    'weight' => -4,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['creation_date'] = BaseFieldDefinition::create('datetime')
                ->setLabel(t('Creation Date'))
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
                    'weight' => -2,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'datetime_default',
                    'weight' => -2,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['documents'] = BaseFieldDefinition::create('file')
                ->setLabel('Attached Documents')
                ->setSettings([
                    'uri_scheme' => 'public',
                    'file_directory' => 'credentialing_providers',
                    'file_extensions' => 'png jpg jpeg pdf',
                ])
                ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
                ->setDisplayOptions('view', array(
                    'label' => 'above',
                    'weight' => 0,
                ))
                ->setDisplayOptions('form', array(
                    'weight' => 0,
                ))
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['medical_assessor'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Medical Assessor'))
                ->setSetting('target_type', 'user')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'weight' => 2,
                ])
                ->setDisplayOptions('form', [
                    'weight' => 2,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['assessment_date'] = BaseFieldDefinition::create('datetime')
                ->setLabel(t('Assessment Date'))
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
                    'weight' => 4,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'datetime_default',
                    'weight' => 4,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['assessment_notes'] = BaseFieldDefinition::create('string_long')
                ->setLabel(t('Assessment Notes'))
                ->setTranslatable(true)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'text_default',
                    'weight' => 6,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'text_textarea',
                    'weight' => 6,
                    'rows' => 6,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['assessment_reassessment_required'] = BaseFieldDefinition::create('boolean')
                ->setLabel(t('Re-assessment Required'))
                ->setDefaultValue(FALSE)
                ->setSettings(['on_label' => 'Yes', 'off_label' => 'No'])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'boolean',
                    'weight' => 8,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'boolean_checkbox',
                    'weight' => 8,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['assessment_result'] = BaseFieldDefinition::create('list_string')
                ->setLabel(t('Assessment Result'))
                ->setTranslatable(true)
                ->setDefaultValue('')
                ->setSettings([
                    'allowed_values' => [
                        'APV' => 'Approved',
                        'NAP' => 'Non-approved',
                        'RAR' => 'Re-assessment Required',
                    ],
                ])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'weight' => 10,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'options_select',
                    'weight' => 10,
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
                ->setLabel(t('Cancellation By'))
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
                    'type' => 'text_default',
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
                ->setDescription(t('A boolean indicating whether the Medical Examination is published.'))
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
            //edit element
            $this->setEntityStatus($this->getInferredEntityStatus());
        } else {
            //new element
            $query = \Drupal::entityQuery('document_parameter')
                    ->condition('status', 1)
                    ->condition('name', 'medical_examination', 'LIKE')
                    ->range(0, 1);
            $entity_ids = $query->execute();
            foreach ($entity_ids as $value) {
                $entity_id[] = $value;
            }
            $entityDP = \Drupal::entityTypeManager()->getStorage('document_parameter')->load($entity_id[0]);
            $number = $entityDP->getNextNumber();
            $prefix = $entityDP->getPrefix();
            $this->setNumber($number);
            $this->setPrefix($prefix);
            $this->setName($prefix . '-' . $number);
            $this->setCreationDate($this->getCreatedTime());
        }
    }

    public function postSave(EntityStorageInterface $storage, $update = TRUE) {
        parent::postSave($storage, $update);
        if ($update == 0) {
            $query = \Drupal::entityQuery('document_parameter')
                    ->condition('status', 1)
                    ->condition('name', 'medical_examination', 'LIKE')
                    ->range(0, 1);
            $entity_ids = $query->execute();
            foreach ($entity_ids as $value) {
                $entity_id[] = $value;
            }
            $entityDP = \Drupal::entityTypeManager()->getStorage('document_parameter')->load($entity_id[0]);
            $number = $entityDP->getNextNumber();
            $number++;
            $entityDP->set('next_number', $number);
            $entityDP->save();
        } else {
            $assessmentId = $this->get('medical_assessment')->first()->getValue();
            $instance = MedicalAssessment::load($assessmentId['target_id']);
            $instance->updateEntityStatus();
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
        if ($status !== 'OPN' && $status !== 'SCH') {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function finish() {
        if ($this->canBeFinished()) {
            $this->set('entity_status', 'CMP');
            $date = new DrupalDateTime('now');
            $date->setTimezone(new \DateTimezone(DATETIME_STORAGE_TIMEZONE));
            $examinationDate = $date->format(DATETIME_DATE_STORAGE_FORMAT);
            $this->set('examination_date', $examinationDate);
            $this->save();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function canBeFinished() {
        if ($this->get('entity_status')->value == 'SCH') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function undoCompleted() {
        if ($this->canBeUndoCompleted()) {
            $this->set('entity_status', 'SCH');
            $this->save();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function canBeUndoCompleted() {
        if ($this->get('entity_status')->value == 'CMP') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function undoClosed() {
        if ($this->canBeUndoClosed()) {
            $this->set('entity_status', 'ASC');
            $this->save();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function canBeUndoClosed() {
        if ($this->get('entity_status')->value == 'CLO') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function close() {
        if ($this->canBeClosed()) {
            $this->set('entity_status', 'CLO');
            $this->save();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function canBeClosed() {
        if ($this->get('entity_status')->value !== 'ASC') {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function access($operation, \Drupal\Core\Session\AccountInterface $account = NULL, $return_as_object = FALSE) {
        parent::access($operation, $account, $return_as_object);
        $status = $this->get('entity_status')->value;
        if (($status == 'OPN' && !\Drupal::currentUser()->hasPermission('view open medical examination')) ||
                ($status == 'SCH' && !\Drupal::currentUser()->hasPermission('view scheduled medical examination')) ||
                ($status == 'CMP' && !\Drupal::currentUser()->hasPermission('view completed medical examination')) ||
                ($status == 'PAS' && !\Drupal::currentUser()->hasPermission('view pending assessment medical examination')) ||
                ($status == 'ASC' && !\Drupal::currentUser()->hasPermission('view assessment scheduled medical examination')) ||
                ($status == 'CLO' && !\Drupal::currentUser()->hasPermission('view closed medical examination')) ||
                ($status == 'CAN' && !\Drupal::currentUser()->hasPermission('view canceled medical examination'))) {
            throw new AccessDeniedHttpException();
        } else {
            return AccessResult::allowed();
        }
    }

    public function getInferredEntityStatus() {
        $status = $this->get('entity_status')->value;
        $medicalExaminer = $this->get('medical_examiner');
        if (isset($medicalExaminer)) {
            if ($status === 'OPN' && isset($medicalExaminer)) {
                $status = 'SCH';
            }
        }

        return $status;
    }

}
