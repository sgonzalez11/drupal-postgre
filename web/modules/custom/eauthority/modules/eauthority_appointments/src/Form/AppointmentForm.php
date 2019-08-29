<?php

namespace Drupal\eauthority_appointments\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Ajax\OpenDialogCommand;
use Drupal\eauthority_appointments\Controller\ConfirmationController;

/**
 * Form controller for Appoinment forms.
 *
 * @ingroup eauthority_appoinment
 */
class AppointmentForm extends FormBase {

    const EVENT_TYPE = 'appointment';

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'appointment_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $values = $form_state->getValues();
        (float)$transactionId = \Drupal::request()->query->get('transactionId');
        $transaction = \Drupal::entityTypeManager()->getStorage('transaction')->load($transactionId);
        if (!$transaction) {
            $transaction = \Drupal::entityTypeManager()->getStorage('transaction')->load(16);
        }
        (float)$serviceId = $transaction->get('service')->first()->getValue();
        $service = \Drupal::entityTypeManager()->getStorage('node')->load($serviceId['target_id']);
        if (isset($transaction) && $transaction->get('need_appointment')->value !== '1') {
            drupal_set_message("This transaction don't require an appointment1", 'error');
        }

        $UTReferencedEntities = $service->get('field_unit_types')->referencedEntities();
        foreach ($UTReferencedEntities as $unit_type) {
            $UTid = $unit_type->id();
            $title = $unit_type->get('name')->value;
            $unitTypes[$UTid] = $title;
        }
        $form['library'] = [
            '#attached' => ['library' => ['bat_event_ui/bat_event_ui', 'bat_fullcalendar/bat-fullcalendar-scheduler', 'eauthority_appointments/eauthority_js']],
        ];

        $form['unit_types'] = [
            '#type' => 'select',
            '#empty_value' => '',
            '#empty_option' => t('- Select a service center -'),
            '#default_value' => '',
            '#options' => $unitTypes,
            '#ajax' => [
                'callback' => [$this, 'selectChangeEvent'],
                'wrapper' => 'unit-wrapper',
            ],
            '#validated' => TRUE,
        ];

        $form['unit'] = [
            '#type' => 'select',
            '#empty_option' => t('- Select a attention line -'),
            '#prefix' => '<div id="unit-wrapper">',
            '#suffix' => '</div>',
            '#options' => [],
            '#ajax' => [
                'callback' => [$this, 'selectCalendarEvent'],
            ],
            '#validated' => TRUE,
        ];

        $form['calendar_wrapper'] = [
            '#prefix' => '<div id="calendar-wrapper">',
            '#suffix' => '</div>',
            '#markup' => '<div class="calendar-set"></div>',
        ];

        $form['event_start'] = [
            '#type' => 'textfield',
            '#default_value' => '',
            '#value' => (isset($form_state->getUserInput()['event_start']) && !empty($form_state->getUserInput()['event_start']) ? $form_state->getUserInput()['event_start'] : ''),
            '#ajax' => [
                'callback' => [$this, 'openConfirmationForm'],
                'event' => 'change',
            ]
        ];

        $form['event_id'] = [
            '#type' => 'textfield',
            '#default_value' => '',
            '#value' => '',
        ];

        $form['event_title'] = [
            '#type' => 'textfield',
            '#default_value' => '',
            '#value' => '',
        ];

        $form['event_end'] = [
            '#type' => 'textfield',
            '#default_value' => '',
            '#value' => '',
        ];

        $form['action_buttons'] = [
            '#prefix' => '<div class="confirmation-button">',
            '#suffix' => '</div>'
        ];

        $form['action_buttons']['appointment_details'] = [
            '#prefix' => '<div >' . t('Do you confirm the appointment for '),
            '#suffix' => '<br></div>',
            '#markup' => (isset($form_state->getUserInput()['event_start']) && !empty($form_state->getUserInput()['event_start']) ? $form_state->getUserInput()['event_start'] : ''),
        ];

        $form['action_buttons']['confirm_appointment'] = [
            '#type' => 'button',
            '#value' => t('Confirm'),
            '#attributes' => [
                'class' => [
                    'confirm_appointment btn btn-default'
                ]
            ]
        ];

        $form['action_buttons']['cancel_appointment'] = [
            '#type' => 'button',
            '#value' => t('Cancel'),
            '#attributes' => [
                'class' => [
                    'cancel_appointment btn btn-default'
                ]
            ]
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => t('Confirm'),
        ];
        return $form;
    }

    public function getUnits($unitTypeId = NULL) {
        (float)$unitTypeId;
        $unitTypesEntities = \Drupal::entityTypeManager()
                ->getStorage('bat_unit')
                ->loadByProperties(
                ['unit_type_id' => $unitTypeId]
        );
        foreach ($unitTypesEntities as $id => $unitTypesEntity) {
            $attentionLines[$id] = $unitTypesEntity->get('name')->value;
        }
        return $attentionLines;
    }

    public function selectChangeEvent(array &$form, FormStateInterface $form_state) {
        $values = $form_state->getValues();
        $attentionLines = $this->getUnits($values['unit_types']);
        $ajax_response = new AjaxResponse();
        $ajax_response->addCommand(new InvokeCommand(null, 'unitAjaxCallback', [$attentionLines]));
        return $ajax_response;
    }

    public function selectCalendarEvent(array &$form, FormStateInterface $form_state) {
        $values = $form_state->getValues();
        (float)$unit = $values['unit'];
        (float)$transactionId = \Drupal::request()->query->get('transactionId');
        $unitEntity = \Drupal::entityTypeManager()->getStorage('bat_unit')->load($unit);
        if (!$transactionId) {
            $transaction = \Drupal::entityTypeManager()->getStorage('transaction')->load(16);
        } else {
            $transaction = \Drupal::entityTypeManager()->getStorage('transaction')->load($transactionId);
        }
        if (isset($transaction) && $transaction->get('need_appointment')->value !== '1') {
            drupal_set_message("This transaction don't require an appointment2", 'error');
        }

        $maxTime = $unitEntity->field_max_time->getString();
        $minTime = $unitEntity->field_min_time->getString();
        $weekendsVal = $unitEntity->field_weekends->getString();
        if ($weekendsVal === 1) {
            $weekends = TRUE;
        } else {
            $weekends = FALSE;
        }
        $slotDuration = $unitEntity->field_slot_duration->getString();
        !empty($slotDuration) ? (int) $slotDuration : 15;

        $getEvents = db_select('event', 'e')->fields('e', array());
        $getEvents->innerJoin('bat_event__event_bat_unit_reference', 'beebur', "e.id = beebur.entity_id");
        $getEvents->condition('beebur.event_bat_unit_reference_target_id', $values['unit'], "=");
        $events = $getEvents->execute();
        foreach ($events as $event) {
            $batEventLoad = bat_event_load($event->id);
            $stateId = $batEventLoad->get('event_state_reference')->target_id;
            $batEventStateLoad = bat_event_load_state($stateId);
            $obj = new \stdClass();
            $obj->start = $batEventLoad->getStartDate()->format('Y-m-d H:i:s');
            $obj->end = $batEventLoad->getEndDate()->format('Y-m-d H:i:s');
            $obj->title = $batEventStateLoad->getCalendarLabel();
            $obj->backgroundColor = $batEventStateLoad->getColor();
            $obj->textColor = 'white';
            $obj->id = $event->id;
            $eventsObjects[] = $obj;
        }

        $timezoneObj = new DrupalDateTime();
        $timezone = $timezoneObj->format('e');
        $cal_arguments = [
            'timezone' => $timezone,
            'max_time' => $maxTime,
            'min_time' => $minTime,
            'weekends' => $weekends,
            'slot_duration' => date('H:i', mktime(0, $slotDuration)),
            'events' => $eventsObjects,
        ];

        $encoded = json_encode($cal_arguments);
        $ajax_response = new AjaxResponse();
        $ajax_response->addCommand(new InvokeCommand(null, 'EventAjaxCallback', [$cal_arguments]));
        return $ajax_response;
    }

    public function dateChangeEvent(array &$form, FormStateInterface $form_state) {
        return $form['event_start'];
    }

    public function openConfirmationForm(array &$form, FormStateInterface $form_state) {
        $values = $form_state->getValues();

        if (isset($form_state->getUserInput()['event_start']) && !empty($form_state->getUserInput()['event_start'])) {
            $values['event_start'] = $form_state->getUserInput()['event_start'];
        }
        $ajax_response = new AjaxResponse();
        $wrapper = $form['action_buttons'];
        $open_dialog = new OpenDialogCommand('#event_start_modal', t('Confirmation'), $wrapper, ['width' => '600']);
        $ajax_response->addCommand($open_dialog);
        return $ajax_response;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

        $values = $form_state->getUserInput();
        (float)$transactionId = \Drupal::request()->query->get('transactionId');
        if (!$transactionId) {
            $transaction = \Drupal::entityTypeManager()->getStorage('transaction')->load(16);
        } else {
            $transaction = \Drupal::entityTypeManager()->getStorage('transaction')->load($transactionId);
        }
        $batEvent = bat_event_load($values['event_id']);
        $state = bat_event_load_state_by_machine_name('booked');
        $batEvent->set('event_state_reference', $state->id());
        $batEvent->set('field_transaction', $transaction->id());
        $batEvent->save();

        $url = \Drupal\Core\Url::fromRoute('eauthority_appointments.confirmation')
                ->setRouteParameters(array('appointmentId' => $values['event_id']));
        $form_state->setRedirectUrl($url);
    }

}
