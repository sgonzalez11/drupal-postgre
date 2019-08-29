<?php

namespace Drupal\eauthority_transactions\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Transaction Status edit forms.
 *
 * @ingroup eauthority_transactions
 */
class TransactionStatusForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\eauthority_transactions\Entity\TransactionStatus */
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
        drupal_set_message($this->t('Created the %label Transaction Status.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Transaction Status.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.transaction_status.canonical', ['transaction_status' => $entity->id()]);
  }

}
