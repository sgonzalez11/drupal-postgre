<?php

namespace Drupal\eauthority_atos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\Core\Url;

/**
 * Form controller for Training Record edit forms.
 *
 * @ingroup eauthority_atos
 */
class TrainingRecordForm extends ContentEntityForm {

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        /* @var $entity \Drupal\eauthority_atos\Entity\TrainingRecord */
        $form = parent::buildForm($form, $form_state);
        $formId = $this->entity->id();
        $form['#theme'] = 'training_record__crud';
        $form['#attached']['library'][] = 'eauthority_atos/eauthority-atos-library';
        $form['#entityId'] = $formId;
        $entity = $this->entity;
        $form['#destination'] = \Drupal::service('path.current')->getPath();
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {

        $EstimatedStartDate = $form_state->getValue('estimated_start_date');
        $EstimatedFinishDate = $form_state->getValue('estimated_finish_date');
        if ($EstimatedStartDate > $EstimatedFinishDate) {
            $form_state->setErrorByName('estimated_start_date', $this->t('La fecha de estimación inicio es MAYOR a la fecha de de estimación final.'));
        }

        $StartDate = $form_state->getValue('start_date');
        $FinishDate = $form_state->getValue('finish_date');
        if ($StartDate > $FinishDate) {
            $form_state->setErrorByName('start_date', $this->t('La fecha de inicio es MAYOR a la fecha de fin.'));
        }

        $form_state->setTemporaryValue('entity_validated', TRUE);
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $form, FormStateInterface $form_state) {
        $entity = $this->entity;
        $status = parent::save($form, $form_state);
        switch ($status) {
            case SAVED_NEW:
                drupal_set_message($this->t('Se creo el curso de entrenamiento %label', [
                            '%label' => $entity->label(),
                ]));
                break;

            default:
                drupal_set_message($this->t('Se guardaron los cambios en el curso de entrenamiento %label', [
                            '%label' => $entity->label(),
                ]));
        }
        //$form_state->setRedirectUrl(Url::fromRoute('view.atos_all_training_record.all_training_record'));
    }

}
