<?php

namespace Drupal\eauthority\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Identifier Types edit forms.
 *
 * @ingroup eauthority
 */
class IdentifierTypeForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\eauthority\Entity\IdentifierType */
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
        drupal_set_message($this->t('Created the %label Identifier Types.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Identifier Types.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.identifier_type.canonical', ['identifier_type' => $entity->id()]);
  }

}
