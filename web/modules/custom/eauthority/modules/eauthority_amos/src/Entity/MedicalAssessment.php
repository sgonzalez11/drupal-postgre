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
use Drupal\eauthority_amos\Entity\MedicalExamination;

/**
 * Defines the Medical Assessment entity.
 *
 * @ingroup eauthority_amos
 *
 * @ContentEntityType(
 *   id = "medical_assessment",
 *   label = @Translation("Medical Assessment"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\eauthority_amos\MedicalAssessmentListBuilder",
 *     "views_data" = "Drupal\eauthority_amos\Entity\MedicalAssessmentViewsData",
 *     "translation" = "Drupal\eauthority_amos\MedicalAssessmentTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\eauthority_amos\Form\MedicalAssessmentForm",
 *       "add" = "Drupal\eauthority_amos\Form\MedicalAssessmentForm",
 *       "edit" = "Drupal\eauthority_amos\Form\MedicalAssessmentForm",
 *       "delete" = "Drupal\eauthority_amos\Form\MedicalAssessmentDeleteForm",
 *       "cancel" = "Drupal\eauthority_amos\Form\MedicalAssessmentCancelForm",
 *       "send_to_authority" = "Drupal\eauthority_amos\Form\MedicalAssessmentSendToAuthorityForm",
 *       "close" = "Drupal\eauthority_amos\Form\MedicalAssessmentCloseForm",
 *     },
 *     "access" = "Drupal\eauthority_amos\MedicalAssessmentAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\eauthority_amos\MedicalAssessmentHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "medical_assessment",
 *   data_table = "medical_assessment_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer medical assessment entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/medical/medical_assessment/{medical_assessment}",
 *     "add-form" = "/medical/medical_assessment/add",
 *     "edit-form" = "/medical/medical_assessment/{medical_assessment}/edit",
 *     "delete-form" = "/medical/medical_assessment/{medical_assessment}/delete",
 *     "cancel-form" = "/medical/medical_assessment/{medical_assessment}/cancel",
 *     "send-to-authority-form" = "/medical/medical_assessment/{medical_assessment}/send-to-authority",
 *     "close-form" = "/medical/medical_assessment/{medical_assessment}/close",
 *     "collection" = "/medical/medical_assessment",
 *     "report" = "/medical/medical_assessment/{medical_assessment}/report",
 *   },
 *   field_ui_base_route = "medical_assessment.settings"
 * )
 */
class MedicalAssessment extends ContentEntityBase implements MedicalAssessmentInterface {

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
    public function getClass() {
        return $this->get('class')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setClass($class) {
        $this->set('class', $class);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerAmo() {
        return $this->get('customer_amo')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerAmo($customer_amo) {
        $this->set('customer_amo', $customer_amo);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getScheduledAppointmentDate() {
        return $this->get('scheduled_appointment_date')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setScheduledAppointmentDate($scheduled_appointment_date) {
        $this->set('scheduled_appointment_date', $scheduled_appointment_date);
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

    /**
     * {@inheritdoc}
     */
    public function getAssessmentDate() {
        return $this->get('assessment_date')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setAssessmentDate($timestamp) {
        $this->set('assessment_date', $timestamp);
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
                ->setDescription(t('The user ID of author of the Medical Assessment entity.'))
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
                ->setReadOnly(TRUE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'weight' => -16,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'string_textfield',
                    'weight' => -16,
                ])
                ->setDisplayConfigurable('view', TRUE);

        $fields['entity_status'] = BaseFieldDefinition::create('list_string')
                ->setLabel(t('Status'))
                ->setTranslatable(true)
                ->setDefaultValue('OPN')
                ->setSettings([
                    'allowed_values' => [
                        'OPN' => 'OPEN',
                        'SCH' => 'SCHEDULED',
                        'IPR' => 'IN PROGRESS',
                        'PVA' => 'PENDING VALIDATION',
                        'PAS' => 'PENDING ASSESSMENT',
                        'ASC' => 'ASSESSMENT SCHEDULED',
                        'AIP' => 'ASSESSMENT IN PROGRESS',
                        'PRE' => 'PENDING RECOMMENDATION',
                        'CLO' => 'CLOSED',
                        'CAN' => 'CANCELED',
                    ],
                ])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -14,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'options_select',
                    'weight' => -14,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['class'] = BaseFieldDefinition::create('list_string')
                ->setLabel(t('Certificate Class'))
                ->setTranslatable(true)
                ->setDefaultValue('')
                ->setSettings([
                    'allowed_values' => [
                        'C1' => 'Class 1',
                        'C2' => 'Class 2',
                        'C3' => 'Class 3',
                    ],
                ])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -12,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'options_select',
                    'weight' => -12,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['assessment_type'] = BaseFieldDefinition::create('list_string')
                ->setLabel(t('Submition Type'))
                ->setTranslatable(true)
                ->setDefaultValue('')
                ->setSettings([
                    'allowed_values' => [
                        'ASC' => 'Ascent',
                        'INI' => 'Initial',
                        'REN' => 'Renewal',
                        'REA' => 'Re-assessment',
                    ],
                ])
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -12,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'options_select',
                    'weight' => -12,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['customer_amo'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Aero medical Center'))
                ->setSetting('target_type', 'customer')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -10,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'list_default',
                    'weight' => -10,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['customer_applicant'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Applicant'))
                ->setSetting('target_type', 'customer')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -10,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'list_default',
                    'weight' => -10,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE)
                ->setRequired(TRUE);

        $fields['medical_history'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Medical History'))
                ->setSetting('target_type', 'webform_submission')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => -6,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'list_default',
                    'weight' => -6,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
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
                    'weight' => -4,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'datetime_default',
                    'weight' => -4,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

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
                    'weight' => -2,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'datetime_default',
                    'weight' => -2,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['amo_medical_examiner'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Responsible Medical Examiner'))
                ->setSetting('target_type', 'customer')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => 0,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'list_default',
                    'weight' => 0,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['amo_examination_date'] = BaseFieldDefinition::create('datetime')
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
                    'weight' => 2,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'datetime_default',
                    'weight' => 2,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['amo_examination_notes'] = BaseFieldDefinition::create('string_long')
                ->setLabel(t('Examination Notes'))
                ->setTranslatable(true)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'text_default',
                    'weight' => 4,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'text_textarea',
                    'weight' => 4,
                    'rows' => 6,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['transaction'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Transaction'))
                ->setSetting('target_type', 'transaction')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => 6,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'list_default',
                    'weight' => 6,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['assessment_medical_assessor'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Responsible Medical Assessor'))
                ->setSetting('target_type', 'user')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => 8,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'list_default',
                    'weight' => 8,
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
                    'weight' => 10,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'datetime_default',
                    'weight' => 10,
                ])
                ->setDisplayConfigurable('form', TRUE)
                ->setDisplayConfigurable('view', TRUE);

        $fields['assessment_notes'] = BaseFieldDefinition::create('string_long')
                ->setLabel(t('Assessment Notes'))
                ->setTranslatable(true)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'text_default',
                    'weight' => 12,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'text_textarea',
                    'weight' => 12,
                    'rows' => 6,
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['assessment_recommendation'] = BaseFieldDefinition::create('entity_reference')
                ->setLabel(t('Assessment Recommendation'))
                ->setSetting('target_type', 'med_assessmt_recommendation')
                ->setSetting('handler', 'default')
                ->setTranslatable(FALSE)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'list_default',
                    'weight' => 6,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'list_default',
                    'weight' => 6,
                    'settings' => [
                        'match_operator' => 'CONTAINS',
                        'size' => '60',
                        'placeholder' => t(''),
                    ],
                ])
                ->setDisplayConfigurable('view', TRUE)
                ->setDisplayConfigurable('form', TRUE);

        $fields['assessment_limitations'] = BaseFieldDefinition::create('string_long')
                ->setLabel(t('Assessment Limitations'))
                ->setTranslatable(true)
                ->setDisplayOptions('view', [
                    'label' => 'visible',
                    'type' => 'text_default',
                    'weight' => 16,
                ])
                ->setDisplayOptions('form', [
                    'type' => 'text_textarea',
                    'weight' => 16,
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
                ->setDescription(t('A boolean indicating whether the Medical Assessment is published.'))
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
            //$this->setEntityStatus($this->getInferredEntityStatus());
        } else {
            //new element
            $query = \Drupal::entityQuery('document_parameter')
                    ->condition('status', 1)
                    ->condition('name', 'medical_assessment', 'LIKE')
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
                    ->condition('name', 'medical_assessment', 'LIKE')
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
            $bundlesTypes = array();
            foreach (entity_get_bundles('medical_examination') as $key => $bundle) {
                $bundlesTypes[$key] = $bundle['label'];
                //Create an examination for each bundle
                $newElement = MedicalExamination::create([
                            'type' => $key,
                            'medical_assessment' => $this->id(),
                ]);
                $newElement->save();
            }
        }
    }

    public function cancel($cancellation_reason) {

        $cancellation_date = date('Y-m-d\TH:i:s', time());
        $cancellation_user = \Drupal::currentUser()->getAccountName();

        if ($this->canBeCanceled()) {
            // Set the entity_status to Cancel.
            $this->set('entity_status', 'CAN');
            $this->set('cancellation_reason', $cancellation_reason);
            $this->set('cancellation_user', $cancellation_user);
            $this->set('cancellation_date', $cancellation_date);
            // Save the entity
            $this->save();

            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function canBeCanceled() {
        $status = $this->get('entity_status')->value;
        if ($status !== 'OPN' && $status !== 'SCH' && $status !== 'IPR') {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function sendToAuthority() {

        $AssessmentStatus = $this->get('entity_status')->value;
        $examinationStatuses = $this->searchAsociations($this->id());
        foreach ($examinationStatuses as $value) {
            $allStatuses[] = $value['status'];
            $allIds[] = $value['id'];
        }
        if ($AssessmentStatus == 'PVA' && count(array_unique($allStatuses)) === 1 && end($allStatuses) === 'CMP') {
            $this->set('entity_status', 'PAS');
            $this->save();
            foreach ($allIds as $examinationId) {
                $instance = MedicalExamination::load($examinationId);
                $instance->set('entity_status', 'PAS');
                $instance->save();
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function canBeSended() {

        $AssessmentStatus = $this->get('entity_status')->value;
        $examinationStatuses = $this->searchAsociations($this->id());
        foreach ($examinationStatuses as $value) {
            $allStatuses[] = $value['status'];
        }
        if ($AssessmentStatus == 'PVA' && count(array_unique($allStatuses)) === 1 && end($allStatuses) === 'CMP') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function close() {

        if ($this->canBeClosed()) {
            // Set the entity_status to Close.
            $this->set('entity_status', 'CLO');
            // Save the entity
            $this->save();

            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function canBeClosed() {
        $status = $this->get('entity_status')->value;
        if ($status !== 'PRE') {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function access($operation, \Drupal\Core\Session\AccountInterface $account = NULL, $return_as_object = FALSE) {
        parent::access($operation, $account, $return_as_object);
        $status = $this->get('entity_status')->value;
        if (($status == 'OPN' && !\Drupal::currentUser()->hasPermission('view open medical assessment')) ||
                ($status == 'SCH' && !\Drupal::currentUser()->hasPermission('view scheduled medical assessment')) ||
                ($status == 'IPR' && !\Drupal::currentUser()->hasPermission('view in progress medical assessment')) ||
                ($status == 'PVA' && !\Drupal::currentUser()->hasPermission('view pending validation medical assessment')) ||
                ($status == 'PAS' && !\Drupal::currentUser()->hasPermission('view pending assessment medical assessment')) ||
                ($status == 'ASC' && !\Drupal::currentUser()->hasPermission('view assessment scheduled medical assessment')) ||
                ($status == 'AIP' && !\Drupal::currentUser()->hasPermission('view assessment in progress medical assessment')) ||
                ($status == 'PRE' && !\Drupal::currentUser()->hasPermission('view pending recommendation medical assessment')) ||
                ($status == 'CLO' && !\Drupal::currentUser()->hasPermission('view closed medical assessment')) ||
                ($status == 'CAN' && !\Drupal::currentUser()->hasPermission('view canceled medical assessment'))) {
            throw new AccessDeniedHttpException();
        } else {
            return AccessResult::allowed();
        }
    }

    public function getInferredEntityStatus() {

        $newAssessmentStatus = '';
        $AssessmentStatus = $this->get('entity_status')->value;
        $examinationStatuses = $this->searchAsociations($this->id());
        foreach ($examinationStatuses as $value) {
            $allStatuses[] = $value['status'];
        }
        foreach ($examinationStatuses as $ExaminationStatus) {
            if ($AssessmentStatus == 'OPN' && $ExaminationStatus['status'] === 'SCH') {
                $newAssessmentStatus = 'SCH';
                break;
            }
            if ($AssessmentStatus == 'SCH' && $ExaminationStatus['status'] === 'CMP') {
                $newAssessmentStatus = 'IPR';
            }
            if ($AssessmentStatus == 'IPR' && count(array_unique($allStatuses)) === 1 && end($allStatuses) === 'CMP') {
                $newAssessmentStatus = 'PVA';
            }
            if ($AssessmentStatus == 'PVA' && $ExaminationStatus['status'] === 'SCH') {
                $newAssessmentStatus = 'IPR';
            }
        }
        if (!$newAssessmentStatus || $newAssessmentStatus == '') {
            return $AssessmentStatus;
        } else {
            return $newAssessmentStatus;
        }
    }

    public function updateEntityStatus() {
        $this->setEntityStatus($this->getInferredEntityStatus());
        $this->save();
    }

    public function searchAsociations($assessmentId) {
        $query = db_select('medical_examination', 'me');
        $query->innerJoin('medical_examination_field_data', 'mefd', "me.id = mefd.id");
        $query->innerJoin('medical_assessment', 'ma', "mefd.medical_assessment = ma.id");
        $query->innerJoin('medical_assessment_field_data', 'mafd', "ma.id = mafd.id");
        $query->condition('mafd.id', $assessmentId, "=");
        $query->fields('me', array('id'));
        $result = $query->execute();
        $asoc = array();
        foreach ($result as $key => $item) {
            $MExamination = \Drupal::entityTypeManager()->getStorage('medical_examination')->load($item->id);
            $asoc[$key]['status'] = $MExamination->getEntityStatus();
            $asoc[$key]['id'] = $item->id;
        }
        return $asoc;
    }

}
