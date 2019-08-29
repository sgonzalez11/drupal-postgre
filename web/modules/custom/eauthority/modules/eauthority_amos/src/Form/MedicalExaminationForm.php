<?php

namespace Drupal\eauthority_amos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\Component\Utility\Unicode;

/**
 * Form controller for Medical Examination edit forms.
 *
 * @ingroup eauthority_amos
 */
class MedicalExaminationForm extends ContentEntityForm {

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        /* @var $entity \Drupal\eauthority_amos\Entity\MedicalExamination */
        $form = parent::buildForm($form, $form_state);
        $entity = $this->entity;
        if ($entity->get('medical_assessment')->referencedEntities()) {
            $medicalAssessment = $entity->get('medical_assessment')->referencedEntities()[0]->id();
        } else {
            //REMOVE THIS CONDITION WHEN TESTING WAS FINISHED
            $medicalAssessment = 1;
        }
        $entityMAssessment = \Drupal::entityTypeManager()
                ->getStorage('medical_assessment')
                ->load($medicalAssessment);
        $form['#theme'] = 'medical_examination__crud';
        $form['#entityId'] = $this->entity->id();
        $form['#bundle_selected'] = $entity->bundle();
        $bTitle = str_replace('_', ' ', $entity->bundle());
        $form['#bundle_title'] = strtoupper($bTitle);
        foreach ($form as $key => $value) {
            if (strpos($key, 'field_number_float') !== false) {
                unset($form[$key]['widget'][0]['value']['#title']);
            }
            if (strpos($key, 'field_text_list') !== false) {
                unset($form[$key]['widget']['#title']);
            }
            if (strpos($key, 'field_text_plain') !== false) {
                unset($form[$key]['widget'][0]['value']['#title']);
            }
            if (strpos($key, 'field_text_plain_long') !== false) {
                unset($form[$key]['widget'][0]['value']['#title']);
            }
        }
        unset($form['examination_notes']['widget'][0]['value']['#title']);
        unset($form['assessment_result']['widget']['#title']);
        unset($form['assessment_notes']['widget'][0]['value']['#title']);
        $form['#assessment'] = $entityMAssessment;
        $customer_amo = $entityMAssessment->get('customer_amo')->referencedEntities()[0];
        $form['#customer_amo'] = $customer_amo->getName();
        if (isset($entityMAssessment->get('customer_applicant')
                                ->referencedEntities()[0])) {
            $form['#applicant_information'] = $entityMAssessment->get('customer_applicant')
                            ->referencedEntities()[0];
        }
        if (isset($entityMAssessment->get('medical_history')
                                ->referencedEntities()[0])) {
            $form['#form_submission'] = $entityMAssessment->get('medical_history')
                            ->referencedEntities()[0];
        }
        if (isset($entity->get('medical_examiner')
                                ->referencedEntities()[0])) {
            $form['#medical_examaminer'] = $entity->get('medical_examiner')
                            ->referencedEntities()[0];
        }
        if (isset($entity->get('medical_assessor')
                                ->referencedEntities()[0])) {
            $form['#medical_assessor'] = $entity->get('medical_assessor')
                            ->referencedEntities()[0];
        }
        $form['#destination'] = $current_path = \Drupal::service('path.current')->getPath();
        
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $form, FormStateInterface $form_state) {
        $entity = $this->entity;

        $status = parent::save($form, $form_state);

        switch ($status) {
            case SAVED_NEW:
                drupal_set_message($this->t('Created the %label Medical Examination.', [
                            '%label' => $entity->label(),
                ]));
                break;

            default:
                drupal_set_message($this->t('Saved the %label Medical Examination.', [
                            '%label' => $entity->label(),
                ]));
        }
        $form_state->setRedirect('entity.medical_examination.canonical', ['medical_examination' => $entity->id()]);
    }

}
