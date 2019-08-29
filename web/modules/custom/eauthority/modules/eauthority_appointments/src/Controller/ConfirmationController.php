<?php

namespace Drupal\eauthority_appointments\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\bat_event\Entity\Event;
use Symfony\Component\HttpFoundation\Request;
//use Drupal\encrypt\Entity\EncryptionProfile;

/**
 * An example controller.
 */
class ConfirmationController extends ControllerBase {

    /**
     * Returns a render-able array for a test page.
     */
    public function content(Request $request) {

        /* $encryption_profile = EncryptionProfile::load($instance_id);
          \Drupal::service('encryption')->encrypt($string, $encryption_profile);

          $encryption_profile = EncryptionProfile::load($instance_id);
          \Drupal::service('encryption')->decrypt($string, $encryption_profile); */
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        $user__customer = $user->get('field_customer')->entity;
        $user__customerId = $user__customer->get('id')->value;
        $appointmentId = $request->query->get('appointmentId');
        $eventLoad = Event::load($appointmentId);
        $eventUnit = $eventLoad->get('event_bat_unit_reference')->entity;
        $event['unitName'] = $eventUnit->get('name')->value;
        $eventUnitType = $eventUnit->get('unit_type_id')->entity;
        $event['unitTypeName'] = $eventUnitType->get('name')->value;
        $transaction = $eventLoad->get('field_transaction')->entity;
        $event['transaction'] = $transaction->get('name')->value;
        $customer = $transaction->get('customer')->entity;
        $event['customerFirstName'] = $customer->get('first_name')->value;
        $event['customerLastName'] = $customer->get('last_name')->value;
        $customerId = $customer->get('id')->value;
        $service = $transaction->get('service')->entity;
        $event['service'] = $service->get('title')->value;
        $eventDates = $eventLoad->get('event_dates')->getValue();
        $event['eventDateStart'] = $eventDates[0]['value'];
        $event['eventDatEnd'] = $eventDates[0]['end_value'];
        $eventState = $eventLoad->get('event_state_reference')->entity;
        $event['eventState'] = $eventState->get('name')->value;
        if ($user__customerId !== $customerId) {
            drupal_set_message('This user cannot see this appointment', 'error');
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
        return [
            '#theme' => 'appointment__confirmation',
            '#event' => $event,
        ];
    }

}
