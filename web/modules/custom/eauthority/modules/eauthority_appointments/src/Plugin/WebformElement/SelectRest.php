<?php

namespace Drupal\eauthority_appointments\Plugin\WebformElement;

use Drupal\webform\Plugin\WebformElement\OptionsBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a 'select' element populated from external sources.
 *
 * @WebformElement(
 *   id = "select_rest",
 *   api = "https://api.drupal.org/api/drupal/core!lib!Drupal!Core!Render!Element!Select.php/class/Select",
 *   label = @Translation("Select (REST-Populated)"),
 *   description = @Translation("Provides a form element for a drop-down menu or scrolling selection box, populated from external source."),
 *   category = @Translation("Options elements"),
 * )
 */
class SelectRest extends OptionsBase {

  /**
   * {@inheritdoc}
   */
  public function getDefaultProperties() {
    return [
      // Options settings.
      'multiple' => FALSE,
      'multiple_error' => '',
      'rest_endpoint' => '',
      'empty_option' => '',
      'empty_value' => '',
      'select2' => FALSE,
      'chosen' => FALSE,
      'placeholder' => '',
    ] + parent::getDefaultProperties();
  }

  /**
   * {@inheritdoc}
   */
  public function supportsMultipleValues() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    $config = $this->configFactory->get('webform.settings');
    $element['#type'] = 'select';

    // Retrieving data from endpoint.
    if (isset($element['#rest_endpoint'])) {
      $options = \_eauthority_appointments_get_values($element['#rest_endpoint']);
      $element['#options'] = $options;
    }

    parent::prepare($element, $webform_submission);
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    // Adding custom endpoint field for reference
    $form['options']['rest_endpoint'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Options Endpoint'),
      '#description' => $this->t('External URL to retrieve options.'),
      '#access' => TRUE,
      '#weight' => 0,
    ];

    // Cleaning up options by unsetting elements
    unset($form['options']['options']);
    unset($form['options']['options_display_container']);
    unset($form['options']['options_randomize']);
    unset($form['options']['options_randomize']);
    unset($form['options']['chosen']);
    unset($form['options']['select2']);
    unset($form['options']['empty_option']);
    unset($form['options']['empty_value']);

    return $form;
  }

}
