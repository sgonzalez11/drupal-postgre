<?php

namespace Drupal\eauthority_amos\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MedicalExaminationTypeForm.
 */
class MedicalExaminationTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $medical_examination_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $medical_examination_type->label(),
      '#description' => $this->t("Label for the Medical Examination type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $medical_examination_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\eauthority_amos\Entity\MedicalExaminationType::load',
      ],
      '#disabled' => !$medical_examination_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $medical_examination_type = $this->entity;
    $status = $medical_examination_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Medical Examination type.', [
          '%label' => $medical_examination_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Medical Examination type.', [
          '%label' => $medical_examination_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($medical_examination_type->toUrl('collection'));
  }

}
