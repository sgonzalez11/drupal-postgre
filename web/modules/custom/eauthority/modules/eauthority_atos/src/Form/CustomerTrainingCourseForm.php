<?php

namespace Drupal\eauthority_atos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Customer Training Course edit forms.
 *
 * @ingroup eauthority_atos
 */
class CustomerTrainingCourseForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\eauthority_atos\Entity\CustomerTrainingCourse */
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
        drupal_set_message($this->t('Created the %label Customer Training Course.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Customer Training Course.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.customer_training_courses.canonical', ['customer_training_courses' => $entity->id()]);
  }

}
