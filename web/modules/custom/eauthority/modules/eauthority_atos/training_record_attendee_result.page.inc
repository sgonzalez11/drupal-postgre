<?php

/**
 * @file
 * Contains training_record_attendee_result.page.inc.
 *
 * Page callback for Training Record Attendee Results entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Training Record Attendee Results templates.
 *
 * Default template: training_record_attendee_result.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_training_record_attendee_result(array &$variables) {
  // Fetch TrainingRecordAttendeeResult Entity Object.
  $training_record_attendee_result = $variables['elements']['#training_record_attendee_result'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
