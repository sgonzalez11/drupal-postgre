<?php

namespace Drupal\eauthority\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Document Parameter edit forms.
 *
 * @ingroup eauthority
 */
class DocumentParameterForm extends ContentEntityForm {

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        /* @var $entity \Drupal\eauthority\Entity\DocumentParameter */
        $form = parent::buildForm($form, $form_state);
        $formId = $this->entity->id();
        $form['#theme'] = 'document-parameter__crud';
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
                drupal_set_message($this->t('Created the %label Document Parameter.', [
                            '%label' => $entity->label(),
                ]));
                break;

            default:
                drupal_set_message($this->t('Saved the %label Document Parameter.', [
                            '%label' => $entity->label(),
                ]));
        }
        $form_state->setRedirect('entity.document_parameter.canonical', ['document_parameter' => $entity->id()]);
    }

}
