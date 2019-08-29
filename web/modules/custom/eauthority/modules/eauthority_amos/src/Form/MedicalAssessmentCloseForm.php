<?php

namespace Drupal\eauthority_amos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Provides a form for deleting Medical Assessment entities.
 *
 * @ingroup eauthority_amos
 */
class MedicalAssessmentCloseForm extends ContentEntityForm {

    private $destination = '';

    /**
     * @param array $form
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = parent::buildForm($form, $form_state);
        /* @var $entity \Drupal\eauthority_amos\Entity\MedicalAssessment */

        $form['#attached']['library'][] = 'eauthority_amos/medical-assessment-close';

        /* $form['#attached']['js'] = array(
          drupal_get_path('module', 'eauthority_amos') . '/medical_assessment_close.js',
          ); */
        //$form['#attached']['library'][] = array('eauthority_amos', 'medical_assessment_close.js');
        $entity = $this->getEntity();
        $this->destination = \Drupal::request()->query->get('destination');
        if (!$this->destination) {
            $this->destination = '/medical/medical_assessment';
        }
        if (!$entity->canBeClosed()) {
            drupal_set_message(t("It is not possible to close the selected Medical Assessment. The same one is not closeable."), "error");
            return new RedirectResponse(Url::fromUserInput($this->destination)->toString());
        }
        $formId = $this->entity->id();
        $form['#theme'] = 'medical_assessment__close';
        $form['#entityId'] = $formId;

        // Special changes for this close form
        $form['name']['widget'][0]['value']['#attributes']['readonly'] = 'readonly';

        //Responsible Medical Assessor's Declaration
        //Responsible Medical Assessor
        $form['assessment_medical_assessor']['widget']['target_id']['#disabled'] = 'disabled';
        unset($form['assessment_medical_assessor']['widget']['target_id']['#title']);
        $form['assessment_medical_assessor']
                ['widget']
                ['target_id']
                ['#default_value'] = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        //Assessment Date
        $date = new DrupalDateTime('now');
        $date->setTimezone(new \DateTimezone(DATETIME_STORAGE_TIMEZONE));
        $assessmentDate = $date->format(DATETIME_DATETIME_STORAGE_FORMAT);
        $form['assessment_date']['widget'][0]['value']['#default_value'] = DrupalDateTime::createFromTimestamp(strtotime($assessmentDate));
        $form['assessment_date']['widget'][0]['value']['#attributes']['readonly'] = 'readonly';
        //Assessment Recommendation
        //$form['assessment_recommendation']['widget']['#disabled'] = 'disabled';
        unset($form['assessment_recommendation']['widget']['#title']);
        //Assessment Limitations
        //$form['assessment_limitations']['widget'][0]['value']['#attributes']['readonly'] = 'readonly';
        unset($form['assessment_limitations']['widget'][0]['value']['#title']);
        //Assessment Notes
        //$form['assessment_notes']['widget'][0]['value']['#attributes']['readonly'] = 'readonly';        
        unset($form['assessment_notes']['widget'][0]['value']['#title']);

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {

        /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
        $entity = $this->getEntity();

        $current_entity_status = $entity->get('entity_status')->value;
        if ($current_entity_status == 'CAN') {
            drupal_set_message(t("The Medical Assessment has already a status set to Close."), 'error');
            $form_state->setError($form, t("The Medical Assessment has already a status set to Close."));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {

        /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
        $entity = $this->getEntity();

        $url = Url::fromUserInput($this->destination);
        $form_state->setRedirectUrl($url);

        //Call the method to change the entity status to close
        if ($entity->close()) {
            // Show a success message.
            drupal_set_message(t("Status Changed to Close"), 'status');
        } else {
            // Show a error message.
            drupal_set_message(t("an error has occurred"), 'error');
        }
    }

    protected function actions(array $form, FormStateInterface $form_state) {

        $actions = parent::actions($form, $form_state);
        $actions['submit']['#value'] = "Confirm";

        if ($this->entity->hasLinkTemplate('close-form')) {
            $route_info = $this->entity->urlInfo('collection');
            $destination = \Drupal::request()->query->get('destination');
            if ($destination) {
                $route_info = Url::fromUserInput($destination);
            }
            $actions['close'] = [
                '#type' => 'link',
                '#title' => $this->t('Close'),
                '#access' => $this->entity->access('close'),
                '#attributes' => [
                    'class' => ['button', 'button'],
                ],
            ];
            $actions['close']['#url'] = $route_info;
        }

        return $actions;
    }

}
