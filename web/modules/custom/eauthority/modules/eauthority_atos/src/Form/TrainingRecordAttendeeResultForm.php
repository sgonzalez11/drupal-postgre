<?php

namespace Drupal\eauthority_atos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Training Record Attendee Results edit forms.
 *
 * @ingroup eauthority_atos
 */
class TrainingRecordAttendeeResultForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\eauthority_atos\Entity\TrainingRecordAttendeeResult */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

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
        drupal_set_message($this->t('Created the %label Training Record Attendee Results.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Training Record Attendee Results.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.training_record_attendee_result.canonical', ['training_record_attendee_result' => $entity->id()]);
  }

}
