<?php

/**
 * @file
 * Contains training_record_attendee.page.inc.
 *
 * Page callback for Attendee entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Attendee templates.
 *
 * Default template: training_record_attendee.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_training_record_attendee(array &$variables) {
  // Fetch TrainingRecordAttendee Entity Object.
  $training_record_attendee = $variables['elements']['#training_record_attendee'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
