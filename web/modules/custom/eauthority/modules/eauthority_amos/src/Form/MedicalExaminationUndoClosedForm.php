<?php

namespace Drupal\eauthority_amos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Provides a form for deleting Medical Examination entities.
 *
 * @ingroup eauthority_amos
 */
class MedicalExaminationUndoClosedForm extends ContentEntityForm {

    private $destination = '';

    /**
     * @param array $form
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = parent::buildForm($form, $form_state);
        /* @var $entity \Drupal\eauthority_amos\Entity\MedicalExamination */

        $entity = $this->getEntity();
        $this->destination = \Drupal::request()->query->get('destination');
        if (!$this->destination) {
            $this->destination = '/medical/medical_examination';
        }
        if (!$entity->canBeUndoClosed()) {
            drupal_set_message(t("It is not possible to undo the selected Medical Examination. The same one can not be undo."), "error");
            return new RedirectResponse(Url::fromUserInput($this->destination)->toString());
        }
        $formId = $this->entity->id();
        $form['#theme'] = 'medical_examination__undo_closed';
        $form['#entityId'] = $formId;

        // Special changes for this Undo Closed form
        $form['name']['widget'][0]['value']['#attributes']['readonly'] = 'readonly';
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {

        /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
        $entity = $this->getEntity();

        $current_entity_status = $entity->get('entity_status')->value;
        if ($current_entity_status == 'ASC') {
            drupal_set_message(t("The Medical Examination has already a status set to Assessment Scheduled."), 'error');
            $form_state->setError($form, t("The Medical Examination has already a status set to Assessment Scheduled."));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {

        /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
        $entity = $this->getEntity();

        $url = Url::fromUserInput($this->destination);
        $form_state->setRedirectUrl($url);

        //Call the method to change the entity status to Undo Closed
        if ($entity->undoClosed()) {
            // Show a success message.
            drupal_set_message(t("Status Changed to Assessment Scheduled"), 'status');
        } else {
            // Show a error message.
            drupal_set_message(t("an error has occurred"), 'error');
        }
    }

    protected function actions(array $form, FormStateInterface $form_state) {

        $actions = parent::actions($form, $form_state);
        $actions['submit']['#value'] = "Confirm";

        if ($this->entity->hasLinkTemplate('undo-closed-form')) {
            $route_info = $this->entity->urlInfo('collection');
            $destination = \Drupal::request()->query->get('destination');
            if ($destination) {
                $route_info = Url::fromUserInput($destination);
            }
            $actions['cancel'] = [
                '#type' => 'link',
                '#title' => $this->t('Cancel'),
                '#access' => $this->entity->access('cancel'),
                '#attributes' => [
                    'class' => ['button', 'button'],
                ],
            ];
            $actions['cancel']['#url'] = $route_info;
        }

        return $actions;
    }

}
