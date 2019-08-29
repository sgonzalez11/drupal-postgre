<?php

/**
 * @file
 * Contains training_record_attendee_exp.page.inc.
 *
 * Page callback for Attendee Experience entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Attendee Experience templates.
 *
 * Default template: training_record_attendee_exp.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_training_record_attendee_exp(array &$variables) {
  // Fetch TrainingRecordAttendeeExperience Entity Object.
  $training_record_attendee_exp = $variables['elements']['#training_record_attendee_exp'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
