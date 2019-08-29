<?php

namespace Drupal\eauthority_amos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Medical Assessment edit forms.
 *
 * @ingroup eauthority_amos
 */
class MedicalAssessmentForm extends ContentEntityForm {

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        /* @var $entity \Drupal\eauthority_amos\Entity\MedicalAssessment */
        $form = parent::buildForm($form, $form_state);
        $entity = $this->entity;
        $form['#theme'] = 'medical_assessment__crud';
        $form['#attached']['library'][] = 'eauthority_atos/eauthority-atos-library';
        $form['#attached']['library'][] = 'eauthority_amos/eauthority-amos-library';
        $form['#entityId'] = $this->entity->id();
        unset($form['assessment_medical_assessor']['widget']['target_id']['#title']);
        unset($form['assessment_recommendation']['widget']['#title']);
        unset($form['assessment_limitations']['widget'][0]['value']['#title']);
        unset($form['amo_examination_notes']['widget'][0]['value']['#title']);
        unset($form['assessment_notes']['widget'][0]['value']['#title']);
        unset($form['customer_amo']['widget']['target_id']['#title']);
        if (isset($entity->get('customer_applicant')->referencedEntities()[0])) {
            $form['#applicant_information'] = $entity->get('customer_applicant')->referencedEntities()[0];
        }
        if (isset($entity->get('amo_medical_examiner')->referencedEntities()[0])) {
            $form['#amo_medical_examiner'] = $entity->get('amo_medical_examiner')->referencedEntities()[0];
        }
        if (isset($entity->get('medical_history')->referencedEntities()[0])) {
            $form['#form_submission'] = $entity->get('medical_history')->referencedEntities()[0];
        }

        $query = db_select('medical_examination', 'me');
        $query->innerJoin('medical_examination_field_data', 'mefd', "me.id = mefd.id");
        $query->innerJoin('medical_assessment', 'ma', "mefd.medical_assessment = ma.id");
        $query->innerJoin('medical_assessment_field_data', 'mafd', "ma.id = mafd.id");
        $query->condition('mafd.id', $this->entity->id(), "=");
        $query->fields('me', array('id'));
        $result = $query->execute();
        foreach ($result as $key => $item) {
            $MExamination = \Drupal::entityTypeManager()->getStorage('medical_examination')->load($item->id);
                $form['#examinationItems'][$key]['id'] = $item->id;
                $form['#examinationItems'][$key]['bundle'] = $MExamination->type->entity->label();
                $form['#examinationItems'][$key]['status'] = $MExamination->getEntityStatusLabel();
                if ($MExamination->get('medical_examiner')->referencedEntities()) {
                    $form['#examinationItems'][$key]['medic'] = $MExamination->get('medical_examiner')->referencedEntities()[0]->getName();
                }
            
            $form['#destination'] = $current_path = \Drupal::service('path.current')->getPath();
        }
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
                drupal_set_message($this->t('Created the %label Medical Assessment.', [
                            '%label' => $entity->label(),
                ]));
                break;

            default:
                drupal_set_message($this->t('Saved the %label Medical Assessment.', [
                            '%label' => $entity->label(),
                ]));
        }
        $form_state->setRedirect('entity.medical_assessment.canonical', ['medical_assessment' => $entity->id()]);
    }

}
