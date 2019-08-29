<?php

namespace Drupal\eauthority_parties\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class PartyTypeForm.
 */
class PartyTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $party_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $party_type->label(),
      '#description' => $this->t("Label for the Party type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $party_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\eauthority_parties\Entity\PartyType::load',
      ],
      '#disabled' => !$party_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $party_type = $this->entity;
    $status = $party_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Party type.', [
          '%label' => $party_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Party type.', [
          '%label' => $party_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($party_type->toUrl('collection'));
  }

}
