<?php

namespace Drupal\eauthority_atos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Attendee edit forms.
 *
 * @ingroup eauthority_atos
 */
class TrainingRecordAttendeeForm extends ContentEntityForm {

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        /* @var $entity \Drupal\eauthority_atos\Entity\TrainingRecordAttendee */
        $form = parent::buildForm($form, $form_state);

        $formId = $this->entity->id();
        $form['#theme'] = 'training_record_attendee__crud';
        $form['#attached']['library'][] = 'eauthority_atos/eauthority-atos-library';
        $form['#entityId'] = $formId;

        $entity = $this->entity;
        if (isset($_POST['name'][0]['value'])){
            $parentEntityName = $_POST['name'][0]['value'];
            $parent = \Drupal::entityTypeManager()->getStorage('training_record')->loadByProperties(['name' => $parentEntityName]);
            $parent = reset($parent);
            $TRStatus = $parent->get('entity_status')->value;
            $form['#entityStatus'] = $TRStatus;
        } else {
            $form['#entityStatus'] = null;
        }
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
                drupal_set_message($this->t('Se ha creado el asistente %label', [
                            '%label' => $entity->label(),
                ]));
                break;

            default:
                drupal_set_message($this->t('Se ha actulizado el asistente %label', [
                            '%label' => $entity->label(),
                ]));
        }
        $form_state->setRedirect('entity.training_record_attendee.canonical', ['training_record_attendee' => $entity->id()]);
    }

}
