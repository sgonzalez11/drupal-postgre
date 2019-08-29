<?php

namespace Drupal\eauthority_appointments\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\Plugin\WebformElement\TextField;

/**
 * Provides a 'autocomplete' element populated from external sources.
 *
 * @WebformElement(
 *   id = "webform_autocomplete_rest",
 *   label = @Translation("Autocomplete (REST-Populated)"),
 *   description = @Translation("Provides a text field element with auto completion, populated from an external source."),
 *   category = @Translation("Advanced elements"),
 * )
 */
class WebformAutocompleteRest extends TextField {

  /**
   * {@inheritdoc}
   */
  public function getDefaultProperties() {
    $properties = [
      // Autocomplete settings.
      'rest_endpoint' => '',
      'autocomplete_existing' => FALSE,
      'autocomplete_items' => [],
      'autocomplete_limit' => 10,
      'autocomplete_match' => 3,
      'autocomplete_match_operator' => 'CONTAINS',
    ] + parent::getDefaultProperties() + $this->getDefaultMultipleProperties();
    // Remove autocomplete property which is not applicable to this autocomplete
    // element.
    unset($properties['autocomplete']);
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    parent::prepare($element, $webform_submission);

    $element['#type'] = 'webform_autocomplete';

    if (isset($element['#rest_endpoint'])) {
      $element['#autocomplete_route_name'] = 'eauthority_appointments.element.autocomplete';
      $element['#autocomplete_route_parameters'] = [
        'webform' => $webform_submission->getWebform()->id(),
        'key' => $element['#webform_key'],
      ];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $form['autocomplete'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Autocomplete settings'),
    ];
    $form['autocomplete']['autocomplete_items'] = [
      '#type' => 'webform_element_options',
      '#custom__type' => 'webform_multiple',
      '#title' => $this->t('Autocomplete values'),
    ];
    $form['autocomplete']['autocomplete_existing'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Include existing submission values'),
      '#description' => $this->t("If checked, all existing submission values will be visible to the webform's users."),
      '#return_value' => TRUE,
    ];
    $form['autocomplete']['autocomplete_limit'] = [
      '#type' => 'number',
      '#title' => $this->t('Autocomplete limit'),
      '#description' => $this->t("The maximum number of matches to be displayed."),
      '#min' => 1,
      '#weight' => 2,
    ];
    $form['autocomplete']['autocomplete_match'] = [
      '#type' => 'number',
      '#title' => $this->t('Autocomplete minimum number of characters'),
      '#description' => $this->t('The minimum number of characters a user must type before a search is performed.'),
      '#min' => 1,
      '#weight' => 3,
    ];
    $form['autocomplete']['autocomplete_match_operator'] = [
      '#type' => 'radios',
      '#title' => $this->t('Autocomplete matching operator'),
      '#description' => $this->t('Select the method used to collect autocomplete suggestions.'),
      '#options' => [
        'STARTS_WITH' => $this->t('Starts with'),
        'CONTAINS' => $this->t('Contains'),
      ],
      '#weight' => 4,
    ];

    // Adding custom endpoint field for reference
    $form['autocomplete']['rest_endpoint'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Options Endpoint'),
      '#description' => $this->t('External URL to retrieve options.'),
      '#access' => TRUE,
      '#weight' => 0,
    ];

    // Unsetting default settings from fieldset.
    unset($form['autocomplete']['autocomplete_items']);
    unset($form['autocomplete']['autocomplete_items']);
    unset($form['autocomplete']['autocomplete_existing']);
    return $form;
  }

}
