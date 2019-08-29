<?php

namespace Drupal\eauthority_appointments\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class ModalForm extends ConfirmFormBase {

    /**
     * Date for the appointment.
     *
     * @var string
     */
    protected $date;

    /**
     * Medical center ID.
     *
     * @var int
     */
    protected $medical_center_id;

    /**
     * Procedure ID.
     *
     * @var int
     */
    protected $procedure_id;

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $build_info = $form_state->getBuildInfo()['args'];
        $this->procedure_id = $build_info[0];
        $this->medical_center_id = $build_info[1];
        $this->date = $build_info[2];
        $form = parent::buildForm($form, $form_state);
        $form['actions']['cancel'] = [
            '#type' => 'button',
            '#value' => t('Cancel'),
        ];
        $form['actions']['submit']['#submit'] = ['::submitForm'];
        $form['actions']['submit']['#type'] = 'submit';
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Todo: to get free unit to book
        // Todo: redirect to confirmation page.
        $form_state->setRedirect('eauthority_appointments.confirmation');
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return "modal_appointment_form";
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelUrl() {
        return new Url('eauthority_appointments.appointment');
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription() {
        return t('Would you like to confirm this appointment for %date?', ['%date' => $this->date]);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestion() {
        return t('Would you like to confirm this appointment for %date?', ['%date' => $this->date]);
    }

}
