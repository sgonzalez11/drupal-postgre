<?php //
//
//namespace Drupal\eauthority_appointments\Form;
//
//use Drupal\Core\Form\FormBase;
//use Drupal\Core\Form\FormStateInterface;
//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
//use Drupal\Core\Ajax\AjaxResponse;
//use Drupal\Core\Ajax\InvokeCommand;
//use Drupal\Core\Datetime\DrupalDateTime;
//use Drupal\Core\Ajax\OpenModalDialogCommand;
//use Drupal\Core\Ajax\OpenDialogCommand;
//
///**
// * Implements an example form.
// */
//class TurneroForm extends FormBase {
//
//  const EVENT_TYPE = 'availability_example';
//
//  /**
//   * {@inheritdoc}
//   */
//  public function getFormId() {
//    return 'turnero_form';
//  }
//
//  /**
//   * {@inheritdoc}
//   */
//  public function buildForm(array $form, FormStateInterface $form_state) {
//    // Get parameter from URL if exists
//    $procedure_type = \Drupal::request()->query->get('procedureTypeId');
//    //dpm($procedure_type);
//
//    // Get the form values and raw input (unvalidated values).
//    $values = $form_state->getValues();
//
//    // Why submitting this?
//    // if (!empty($values['procedure']) && !empty($values['centro_medico']) && !empty($values['date_info']))
//      // $this->submitForm($form, $form_state);
//
//    // Define a wrapper id to populate new content into.
//    $ajax_wrapper = 'my-ajax-wrapper';
//
//    // Get procedure list (term)
//    // Get procedure list 
//    // $vid = 'procedure';
//    // $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree( $vid);
//    // foreach ($terms as $term) {
//    // $procedures[$term->tid] = $term->name;
//    // }
//
//    // @Todo: to add this into panel
//    // - related term
//    // - if content type or term, etc
//    $procedures = [];
//
//    // Get procedure list (node)
//    $vid = 'procedure_type_topics';
//    $terms =\Drupal::entityTypeManager()
//      ->getStorage('taxonomy_term')
//      ->loadByProperties([
//        'vid' => $vid,
//        'name' => 'Salud',
//      ]);
//    $tid = key($terms);
//
//    $content_type = 'procedure_type';
//    $nids = \Drupal::entityQuery('node')
//      ->condition('type', $content_type)
//      ->condition('field_isappointmentneeded', TRUE)
//      ->execute();
//    //$nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
//    foreach ($nodes as $node) {
//      $nid = $node->id();
//      $title = $node->getTitle();
//      $length = $node->get('field_appointment_length')->value;
//      $appointment_length = !empty($length) ? (int)$length : 15;
//      $procedures[$nid] = $title;
//    }
//
//    $form['library'] = [
//      '#attached' => ['library' => ['bat_event_ui/bat_event_ui', 'bat_fullcalendar/bat-fullcalendar-scheduler', 'eauthority_appointments/eauthority_js']],
//    ];
//
//    $form['procedure'] = [
//      '#type' => 'select',
//      '#empty_value' => '',
//      '#empty_option' => t('- Select a procedure -'),
//      '#default_value' => (isset($values['procedure']) ? $values['procedure'] : $procedure_type),
//      '#options' => $procedures,
//      '#ajax' => [
//        'callback' => [$this, 'selectChangeEvent'],
//        'wrapper' => 'centro-medico-wrapper',
//      ],
//    ];
//
//    if (!empty($procedure_type))
//      $form['procedure']['#attributes'] = [
//        'disabled' => 'disabled'
//      ];
//
//    if (!isset($values['procedure']) && !empty($procedure_type)) {
//      $values['procedure'] = $procedure_type;
//    }
//
//    $form['centro_medico'] = [
//      '#type' => 'select',
//      '#empty_value' => '',
//      '#empty_option' => t('- Select a medical center -'),
//      '#default_value' => (isset($values['centro_medico']) ? $values['centro_medico'] : ''),
//      '#prefix' => '<div id="centro-medico-wrapper">',
//      '#suffix' => '</div>',
//      '#options' => (isset($values['procedure']) ? $this->getMedicalCenters($values['procedure']) : []),
//      '#ajax' => [
//        'callback' => [$this, 'selectCalendarEvent'],
//        //'wrapper' => 'calendar-wrapper'
//      ]
//    ];
//
//    $form['calendar_wrapper'] = [
//      '#prefix' => '<div id="calendar-wrapper">',
//      '#suffix' => '</div>',
//      '#markup' => '<div class="calendar-set"></div>', //(isset($values['centro_medico']) ? $this->displayCalendar($values['centro_medico'], 'medical_center_availability') : t('Please select a medical center.'))
//    ];
//
//    $form['date_info'] = [
//      '#type' => 'textfield',
//      '#default_value' => '',//(isset($form_state->getUserInput()['date_info']) && !empty($form_state->getUserInput()['date_info']) ? $form_state->getUserInput()['date_info'] : ''),
//      '#value' => (isset($form_state->getUserInput()['date_info']) && !empty($form_state->getUserInput()['date_info']) ? $form_state->getUserInput()['date_info'] : ''),
//      '#ajax' => [
//        'callback' => [$this, 'openConfirmationForm'],
//        'event' => 'change',
//      ]
//    ];
//
//    $form['action_buttons'] = [
//      '#prefix' => '<div class="confirmation-button">',
//      '#suffix' => '</div>'
//    ];
//
//    $form['action_buttons']['appointment_details'] = [
//      '#prefix' => '<div >'.t('Do you confirm the appointment for '),
//      '#suffix' => '<br></div>',
//      '#markup' => (isset($form_state->getUserInput()['date_info']) && !empty($form_state->getUserInput()['date_info']) ? $form_state->getUserInput()['date_info'] : ''),
//    ];
//
//    $form['action_buttons']['confirm_appointment'] = [
//      '#type' => 'button',
//      '#value' => t('Confirm'),
//      '#attributes' => [
//        'class' => [
//          'confirm_appointment'
//        ]
//      ]
//    ];
//
//    $form['action_buttons']['cancel_appointment'] = [
//      '#type' => 'button',
//      '#value' => t('Cancel'),
//      '#attributes' => [
//        'class' => [
//          'cancel_appointment'
//        ]
//      ]
//    ];
//
//    $form['submit'] = [
//      '#type' => 'submit',
//      '#value' => t('Confirm'),
//      // '#submit' => '::submitForm'
//    ];
//    
//    // if (isset($form_state->getUserInput()['date_info']) && !empty($form_state->getUserInput()['date_info']))
//      // $this->openConfirmationForm($form, $form_state);
//
//    return $form;
//  }
//
//  public function getMedicalCenters($procedure) {
//    // $query = \Drupal::entityQuery('bat_unit_type');
//    // $query->condition('type', 'medical_center');
//    // $query->condition('field_available_procedures.entity.tid', $procedure);
//    // $entity_ids = $query->execute();
//
//    // Getting Service centers from node
//    $query = \Drupal::entityQuery('node');
//    $query->condition('nid', $procedure);
//    $nid = $query->execute();
//    $node = \Drupal\node\Entity\Node::load(reset($nid));
//
//    if (isset($node)) {
//      $entity_values = $node->get('field_service_desks')->getValue();
//      $entity_ids = [];
//      if (isset($entity_values)) {
//        foreach ( $entity_values as $entity_id) {
//          $entity_ids[] = $entity_id['target_id'];
//        }
//      }
//    }
//
//    $medical_centers = \Drupal::entityTypeManager()
//      ->getStorage('bat_unit_type')
//      ->loadMultiple($entity_ids);
//
//    $medical_center_values = [];
//
//    foreach ($medical_centers as $id => $value)
//      $medical_center_values[$id] = $value->label();
//
//    return $medical_center_values;
//  }
//
//  public function selectChangeEvent(array &$form, FormStateInterface $form_state) {
//    return $form['centro_medico'];
//  }
//
//  public function getAppointmentLength($procedure_type) {
//    $node = \Drupal\node\Entity\Node::load($procedure_type);
//    $length = $node->get('field_appointment_length')->value;
//    return !empty($length) ? (int)$length : 15;
//  }
//
//  public function selectCalendarEvent(array &$form, FormStateInterface $form_state) {
//    $values = $form_state->getValues();
//    // @Todo: Add panel with event type
//    $unit_type = $values['centro_medico'];
//
//    $procedure_type = $values['procedure'];
//    $appointment_length = $this->getAppointmentLength($procedure_type);
//
//    if (empty($unit_type))
//      return '';
//      
//    $unit = \Drupal::entityQuery('bat_unit');
//    $unit->condition('unit_type_id.entity.id', $unit_type);
//    $unit_ids = $unit->execute();
//    $unit_count = count($unit_ids);
//
//    // Adding today date format to get events from today
//    $date = new DrupalDateTime('now');
//    $date->setTimezone(new \DateTimezone(DATETIME_STORAGE_TIMEZONE));
//    $formatted = $date->format(DATETIME_DATETIME_STORAGE_FORMAT);
//
//    $booked_state = [
//      bat_event_load_state_by_machine_name('not_available'),
//      bat_event_load_state_by_machine_name('booked'),
//    ];
//
//    $booked_state_ids = [];
//    foreach ($booked_state as $bs) {
//      $booked_state_ids[] = $bs->id();
//    }
//
//    // $events = \Drupal::entityQuery('bat_event');
//    $events = \Drupal::entityQueryAggregate('bat_event');
//    $events->groupBy('event_dates.value');
//    $events->aggregate('event_dates.value', 'COUNT');
//    $events->condition('event_bat_unit_reference.entity.id', $unit_ids, 'IN');
//    $events->condition('event_dates.value', $formatted, '>=');
//    $events->condition('event_state_reference.entity.id', $booked_state_ids, 'IN');
//    $event_dates = $events->execute();
//
//    // $full_event_info = \Drupal::entityTypeManager()
//      // ->getStorage('bat_event')
//      // ->loadMultiple($event_ids);
//
//    $events = [];
//
//    foreach ($event_dates as $event_info) {
//      // To jump out of loop if event_datesvalue_count == # of units 
//      $event_count = $event_info['event_datesvalue_count'];
//
//      // If any of the doctors is available, we jump the loop
//      if ($unit_count < $event_count)
//        continue;
//      
//      // to get start / end dates
//      $hours = floor($appointment_length / 60);
//      $minutes = ($appointment_length % 60);
//      $interval_addition = '+' . $hours . ' hours +' . $minutes . ' minutes';
//      
//      $start = new DrupalDateTime($event_info['event_dates_value']);
//      $end = new DrupalDateTime($event_info['event_dates_value']);
//      $end->modify($interval_addition);
//
//      $obj = new \stdClass();
//      $obj->start = $start->format('Y-m-d H:i:s');
//      $obj->end = $end->format('Y-m-d H:i:s');
//      $obj->title = 'Not available';
//      $obj->backgroundColor = 'red';
//      $obj->textColor = 'white';
//      $events[] = $obj;
//    }
//
//    $units = \Drupal::entityTypeManager()
//      ->getStorage('bat_unit')
//      ->loadMultiple($unit_ids);
//    
//    $type = bat_type_load($unit_type);
//
//    $timezone = new DrupalDateTime();
//    $timezone = $timezone->format('e');
//
//    // $cal_arguments = ['events' => json_encode($events)];
//    $cal_arguments = [
//      'timezone' => $timezone,
//      'appointment_length' => date('H:i', mktime(0, $appointment_length)),
//      'events' => $events
//    ];
//
//    $encoded = json_encode($cal_arguments);
//
//    $ajax_response = new AjaxResponse();
//    $ajax_response->addCommand(new InvokeCommand(null, 'myAjaxCallback', [$cal_arguments]));
//    return $ajax_response;
//  }
//
//  public function dateChangeEvent(array &$form, FormStateInterface $form_state) {
//    return $form['date_info'];
//  }
//
//  public function openConfirmationForm(array &$form, FormStateInterface $form_state) {
//    $values = $form_state->getValues();
//
//    if (isset($form_state->getUserInput()['date_info']) && !empty($form_state->getUserInput()['date_info']))
//      $values['date_info'] = $form_state->getUserInput()['date_info'];
//    
//    // Get the modal form using the form builder.
//    // $modal_form = \Drupal::formBuilder()->getForm('Drupal\eauthority_appointments\Form\ModalForm', $values['procedure'], $values['centro_medico'], $values['date_info']);
//
//    $ajax_response = new AjaxResponse();
//    $wrapper = $form['action_buttons'];
//    $open_dialog = new OpenDialogCommand('#date_info_modal', t('Confirmation'), $wrapper, ['width' => '600']);
//    // $open_dialog = new OpenDialogCommand('#date_info', t('Confirmation'), t('Do you confirm the appointment for @date?', ['@date' => $values['date_info']]), ['width' => '600']);
//    $ajax_response->addCommand($open_dialog);
//    // $ajax_response->addCommand(new OpenModalDialogCommand('Confirm appointment details', $modal_form, ['width' => '600']));
//    return $ajax_response;
//  }
//
//
//  /**
//   * {@inheritdoc}
//   */
//  public function validateForm(array &$form, FormStateInterface $form_state) {
//  }
//
//  /**
//   * {@inheritdoc}
//   */
//  public function submitForm(array &$form, FormStateInterface $form_state) {
//    $values = $form_state->getValues();
//    $appointment_id = '';
//    $event = null;
//    $unit_type = $values['centro_medico'];
//    $start_date_string = $values['date_info'];
//
//    // Get Appointment length
//    $procedure_type = $values['procedure'];
//    $appointment_length = $this->getAppointmentLength($procedure_type);
//
//
//    if (!empty($start_date_string)) {
//      //$start_date_string = '2019-02-18 02:00';
//      $start_date = new DrupalDateTime($start_date_string);
//      //$start_date_string = '2019-02-18 22:00';
//      $end_date = new DrupalDateTime($start_date_string);
//
//      $hours = floor($appointment_length / 60);
//      $minutes = ($appointment_length % 60);
//      $interval_addition = '+' . $hours . ' hours +' . $minutes . ' minutes';
//      $end_date->modify($interval_addition);
//
//      $timezone = new DrupalDateTime();
//      // $timezone = $timezone->format('e');
//
//      // Render date
//      // $time->format('Y-m-d H:i');
//      $booked_state = bat_event_load_state_by_machine_name('booked');
//
//      $event = bat_event_create(['type' => self::EVENT_TYPE]);
//      $event_dates = [
//        'value' => $start_date->format('Y-m-d\TH:i:00'),
//        // 'value' => $start_date->format('Y-m-d\TH:i:00\TO'),
//        'end_value' => $end_date->format('Y-m-d\TH:i:00'),
//        // 'end_value' => $end_date->format('Y-m-d\TH:i:00\TO'),
//      ];
//      $event->set('event_dates', $event_dates);
//      $event->set('event_bat_unit_reference', $booked_state->id());
//
//      // Todo: To provide more info about available doctors, and picking the free one.
//      // Could also provide doctor information from the form.
//      // $unit_type
//      $available_units = bat_event_get_matching_units(
//        $start_date->getPhpDateTime(), 
//        $end_date->getPhpDateTime(), 
//        ['available'],
//        $unit_type,
//        self::EVENT_TYPE,
//        true
//      );
//
//      // If false, we set the first free unit
//      if ($available_units) {
//        // To-do: to patch bat module to fix calendar in admin
//        $event->set('event_bat_unit_reference', reset($available_units));
//        $event->set('event_state_reference', $booked_state->id());
//        $event->save();
//        $appointment_id = $event->id();
//      } else {
//        drupal_set_message('Error', 'error');
//      }
//    }
//
//    if ($event !== null) {
//      $url = \Drupal\Core\Url::fromRoute('eauthority_appointments.confirmation')
//        ->setRouteParameters(array('appointment_id' => $appointment_id));
//
//      $form_state->setRedirectUrl($url);
//    } else {
//
//      \Drupal::messenger()->addMessage('Say something else');
//      $form_state->setRedirectUrl($url);
//    }
//
//
//
//
//
//  }
//}