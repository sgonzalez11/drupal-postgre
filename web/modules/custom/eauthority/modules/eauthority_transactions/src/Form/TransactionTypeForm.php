<?php

namespace Drupal\eauthority_transactions\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class TransactionTypeForm.
 */
class TransactionTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $transaction_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $transaction_type->label(),
      '#description' => $this->t("Label for the Transaction type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $transaction_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\eauthority_transactions\Entity\TransactionType::load',
      ],
      '#disabled' => !$transaction_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $transaction_type = $this->entity;
    $status = $transaction_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Transaction type.', [
          '%label' => $transaction_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Transaction type.', [
          '%label' => $transaction_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($transaction_type->toUrl('collection'));
  }

}
