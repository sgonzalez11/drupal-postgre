<?php

namespace Drupal\eauthority_atos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\UriLinkFormatter;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
* Provides a form for deleting Training Record entities.
*
* @ingroup eauthority_atos
*/
class TrainingRecordCancelForm extends ContentEntityForm {

  private $destination = '';
  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $this->destination = \Drupal::request()->query->get('destination');
    if(!$this->destination) {
      $this->destination = '/training/training_record';
    }
    if (!$entity->canBeCanceled()) {
      drupal_set_message(t("It is not possible to cancel the selected Training Record. The same one is not cancelleable."), "error");
      return new RedirectResponse(Url::fromUserInput($this->destination)->toString());
    }

    /* @var $entity \Drupal\eauthority_atos\Entity\TrainingRecord */
    $form = parent::buildForm($form, $form_state);

    $formId = $this->entity->id();
    $form['#theme'] = 'training_record__cancel';
    $form['#entityId'] = $formId;
    // $entity = $this->entity;

    // Special changes for this cancel form
    $form['name']['widget'][0]['value']['#attributes']['readonly'] = 'readonly';
    $form['start_date']['widget'][0]['value']['#attributes']['readonly'] = 'readonly';
    $form['finish_date']['widget'][0]['value']['#attributes']['readonly'] = 'readonly';
    $form['training_course']['widget'][0]['target_id']['#disabled'] = 'disabled';
    $form['location']['widget'][0]['value']['#attributes']['readonly'] = 'readonly';

    return $form;

  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

    /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
    $entity = $this->getEntity();

    $current_entity_status = $entity->get('entity_status')->value;
    if ($current_entity_status == 'CAN') {
      drupal_set_message(t("The Training Record has already a status set to Cancel."), 'error');
      $form_state->setError($form, t("The Training Record has already a status set to Cancel."));
    }

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
    $entity = $this->getEntity();
    $cancellation_reason = $form_state->getValue('cancellation_reason');

    $url = Url::fromUserInput($this->destination);
    $form_state->setRedirectUrl($url);

    //Call the method to change the entity status to cancel
    if($entity->cancel($cancellation_reason)) {
      // Show a success message.
      drupal_set_message(t("Status Changed to Cancel"), 'status');
    } else {
      // Show a error message.
      drupal_set_message(t("an error has occurred"), 'error');
    }

  }

  protected function actions(array $form, FormStateInterface $form_state) {

    $actions = parent::actions($form, $form_state);
    $actions['submit']['#value'] = "Confirm";

    if ($this->entity->hasLinkTemplate('cancel-form')) {
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