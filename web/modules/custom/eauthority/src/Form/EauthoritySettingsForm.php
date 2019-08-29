<?php

namespace Drupal\eauthority\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EauthoritySettingsForm.
 */
class EauthoritySettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'eauthority.eauthoritysettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'eauthority_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('eauthority.eauthoritysettings');

    $form['eauthority_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Transactions settings'),
    ];
    $form['eauthority_settings']['api_url_core'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Url'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => $config->get('api_url'),
    ];
    $form['eauthority_settings']['user_core'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('user'),
    ];
    $form['eauthority_settings']['password_core'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#maxlength' => 64,
      '#size' => 64,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('eauthority.eauthoritysettings')
      ->set('api_url', $form_state->getValue('api_url_core'))
      ->set('user', $form_state->getValue('user_core'))
      ->set('password', $form_state->getValue('password_core'))
      ->save();
  }

}
