<?php

namespace Drupal\eauthority_amos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Medical Assessment Recommendations edit forms.
 *
 * @ingroup eauthority_amos
 */
class MedicalAssessmentRecommendationForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\eauthority_amos\Entity\MedicalAssessmentRecommendation */
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
        drupal_set_message($this->t('Created the %label Medical Assessment Recommendations.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Medical Assessment Recommendations.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.med_assessmt_recommendation.canonical', ['med_assessmt_recommendation' => $entity->id()]);
  }

}
