<?php

namespace Drupal\eauthority_atos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Training Course edit forms.
 *
 * @ingroup eauthority_atos
 */
class TrainingCourseForm extends ContentEntityForm {

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        /* @var $entity \Drupal\eauthority_atos\Entity\TrainingCourse */
        $form = parent::buildForm($form, $form_state);
        
        $formId = $this->entity->id();
        $form['#theme'] = 'training_course__crud';
        $form['#attached']['library'][] = 'eauthority_atos/eauthority-atos-library';
        $form['#entityId'] = $formId;

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
                drupal_set_message($this->t('Created the %label Training Course.', [
                            '%label' => $entity->label(),
                ]));
                break;

            default:
                drupal_set_message($this->t('Saved the %label Training Course.', [
                            '%label' => $entity->label(),
                ]));
        }
        $form_state->setRedirect('entity.training_course.canonical', ['training_course' => $entity->id()]);
    }

}