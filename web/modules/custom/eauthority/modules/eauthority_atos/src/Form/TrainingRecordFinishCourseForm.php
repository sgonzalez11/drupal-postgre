<?php

namespace Drupal\eauthority_atos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\UriLinkFormatter;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides a form for deleting Training Record entities.
 *
 * @ingroup eauthority_atos
 */
class TrainingRecordFinishCourseForm extends ContentEntityForm {

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
        if (!$this->destination) {
            $this->destination = '/training/training_record';
        }

        $eReport = $entity->checkCanBeFinished();
        if ($eReport['errorExist'] === TRUE) {
            foreach ($eReport as $key => $value) {
                if (is_numeric($key)) {
                    drupal_set_message(t($value), "error");
                }
            }
            return new RedirectResponse(Url::fromUserInput($this->destination)->toString());
        }

        /* @var $entity \Drupal\eauthority_atos\Entity\TrainingRecord */
        $form = parent::buildForm($form, $form_state);

        $formId = $this->entity->id();
        $form['#theme'] = 'training_record__finish_course';
        $form['#entityId'] = $formId;
        // $entity = $this->entity;
        // Special changes for this report start date form
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
        if ($current_entity_status == 'CMP') {
            drupal_set_message(t("The Training Record has already a status setted to complete."), 'error');
            $form_state->setError($form, t("The Training Record has already a status setted to complete."));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {

        /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
        $entity = $this->getEntity();

        $url = Url::fromUserInput($this->destination);
        $form_state->setRedirectUrl($url);

        //Call the method to change the entity status to finished
        if ($entity->finishCourse()) {
            // Show a success message.
            drupal_set_message(t("Status Changed to Complete"), 'status');
        } else {
            // Show a error message.
            drupal_set_message(t("an error has occurred"), 'error');
        }
    }

    protected function actions(array $form, FormStateInterface $form_state) {

        $actions = parent::actions($form, $form_state);
        $actions['submit']['#value'] = "Confirm";

        if ($this->entity->hasLinkTemplate('finish-course-form')) {
            $route_info = $this->entity->urlInfo('collection');
            $destination = \Drupal::request()->query->get('destination');
            if ($destination) {
                $route_info = Url::fromUserInput($destination);
            }
            $actions['finish_course'] = [
                '#type' => 'link',
                '#title' => $this->t('Report Start Date'),
                '#access' => $this->entity->access('finish_course'),
                '#attributes' => [
                    'class' => ['button', 'button'],
                ],
            ];
            $actions['finish_course']['#url'] = $route_info;
        }

        return $actions;
    }

}
