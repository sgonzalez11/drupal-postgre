<?php

namespace Drupal\eauthority\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Customers edit forms.
 *
 * @ingroup eauthority
 */
class CustomerForm extends ContentEntityForm {

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        /* @var $entity \Drupal\eauthority\Entity\Customer */
        $form = parent::buildForm($form, $form_state);
        
        $formId = $this->entity->id();
        $form['#theme'] = 'customer__crud';
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
                drupal_set_message($this->t('Created the %label Customers.', [
                            '%label' => $entity->label(),
                ]));
                break;

            default:
                drupal_set_message($this->t('Saved the %label Customers.', [
                            '%label' => $entity->label(),
                ]));
        }
        $form_state->setRedirect('entity.customer.canonical', ['customer' => $entity->id()]);
    }

}
