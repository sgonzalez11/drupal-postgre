<?php

namespace Drupal\eauthority_amos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides a form for Send to Authority Medical Assessment entities.
 *
 * @ingroup eauthority_amos
 */
class MedicalAssessmentSendToAuthorityForm extends ContentEntityForm {

    private $destination = '';

    /**
     * @param array $form
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        
        $user = \Drupal::currentUser();
        $entity = $this->getEntity();
        $status = $entity->getEntityStatus();
        $form['#attached']['library'][] = 'eauthority_amos/eauthority-amos-library';
        
        $this->destination = \Drupal::request()->query->get('destination');
        if (!$this->destination) {
            $this->destination = '/medical/medical_assessment';
        }
        if (!$entity->canBeSended()) {
            drupal_set_message(t("It is not possible to send the selected Medical Assessment. The same one can not be sended."), "error");
            return new RedirectResponse(Url::fromUserInput($this->destination)->toString());
        }

        /* @var $entity \Drupal\eauthority_amos\Entity\MedicalAssessment */
        $form = parent::buildForm($form, $form_state);

        $formId = $this->entity->id();
        $form['#theme'] = 'medical_assessment__send_to_authority';
        $form['#entityId'] = $formId;

        // Special changes for this send to authority form
        $form['name']['widget'][0]['value']['#attributes']['readonly'] = 'readonly';

        //Examination Notes
        unset($form['amo_examination_notes']['widget'][0]['value']['#title']);

        if ($status !== 'PVA' || !$user->hasPermission("edit responsible medical examiner's declaration page of the medical assessment form")) {
            $form['amo_examination_notes']['widget'][0]['value']['#attributes']['readonly'] = 'readonly';
            $form['#checkbox_active'] = 0;
        } else {
            $form['#checkbox_active'] = 1;
        }
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {

        /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
        $entity = $this->getEntity();

        $current_entity_status = $entity->get('entity_status')->value;
        if ($current_entity_status == 'PAS') {
            drupal_set_message(t("The Medical Assessment has already sended to Authority."), 'error');
            $form_state->setError($form, t("The Medical Assessment has already sended to Authority."));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {

        /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
        $entity = $this->getEntity();

        $url = Url::fromUserInput($this->destination);
        $form_state->setRedirectUrl($url);

        //Call the method to change the entity status to sended
        if ($entity->sendToAuthority()) {
            // Show a success message.
            drupal_set_message(t("Status Changed to Pending Assessment"), 'status');
        } else {
            // Show a error message.
            drupal_set_message(t("an error has occurred"), 'error');
        }
    }

    protected function actions(array $form, FormStateInterface $form_state) {

        $actions = parent::actions($form, $form_state);
        $actions['submit']['#value'] = "Confirm";

        if ($this->entity->hasLinkTemplate('send-to-authority-form')) {
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
