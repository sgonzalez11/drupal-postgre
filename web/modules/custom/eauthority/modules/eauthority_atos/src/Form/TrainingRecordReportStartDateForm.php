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
class TrainingRecordReportStartDateForm extends ContentEntityForm {

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
        if (!$entity->canBeStartDateReported()) {
            drupal_set_message(t("It is not possible to report start date the selected Training Record. The same one is not reportable."), "error");
            return new RedirectResponse(Url::fromUserInput($this->destination)->toString());
        }

        /* @var $entity \Drupal\eauthority_atos\Entity\TrainingRecord */
        $form = parent::buildForm($form, $form_state);

        $formId = $this->entity->id();
        $form['#theme'] = 'training_record__report_start_date';
        $form['#entityId'] = $formId;

        // Special changes for this report start date form
        $form['name']['widget'][0]['value']['#attributes']['readonly'] = 'readonly';
        $form['training_course']['widget'][0]['target_id']['#disabled'] = 'disabled';
        $form['location']['widget'][0]['value']['#attributes']['readonly'] = 'readonly';

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {

        $EstimatedStartDate = $form_state->getValue('estimated_start_date');
        $EstimatedFinishDate = $form_state->getValue('estimated_finish_date');
        if ($EstimatedStartDate > $EstimatedFinishDate) {
            $form_state->setErrorByName('estimated_start_date', $this->t('Start estimated date is GREATER than finish estimated date'));
        }

        $form_state->setTemporaryValue('entity_validated', TRUE);
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {

        /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
        $entity = $this->getEntity();
        
        $startDate = new DrupalDateTime($form_state->getValue('estimated_start_date')[0]['value'], DATETIME_STORAGE_TIMEZONE);
        $EstimatedStartDate = $startDate->format('Y-m-d');
        
        $finishDate = new DrupalDateTime($form_state->getValue('estimated_finish_date')[0]['value'], DATETIME_STORAGE_TIMEZONE);
        $EstimatedFinishDate = $finishDate->format('Y-m-d');

        $url = Url::fromUserInput($this->destination);
        $form_state->setRedirectUrl($url);

        //Call the method to change the entity status to reported
        if ($entity->reportStartDate($EstimatedStartDate, $EstimatedFinishDate)) {
            // Show a success message.
            drupal_set_message(t("Status Changed to Scheduled"), 'status');
        } else {
            // Show a error message.
            drupal_set_message(t("an error has occurred"), 'error');
        }
    }

    protected function actions(array $form, FormStateInterface $form_state) {

        $actions = parent::actions($form, $form_state);
        $actions['submit']['#value'] = "Confirm";

        if ($this->entity->hasLinkTemplate('report-start-date-form')) {
            $route_info = $this->entity->urlInfo('collection');
            $destination = \Drupal::request()->query->get('destination');
            if ($destination) {
                $route_info = Url::fromUserInput($destination);
            }
            $actions['report_start_date'] = [
                '#type' => 'link',
                '#title' => $this->t('Report Start Date'),
                '#access' => $this->entity->access('report_start_date'),
                '#attributes' => [
                    'class' => ['button', 'button'],
                ],
            ];
            $actions['report_start_date']['#url'] = $route_info;
        }

        return $actions;
    }

}
