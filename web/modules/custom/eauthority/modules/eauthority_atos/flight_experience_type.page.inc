<?php

/**
 * @file
 * Contains flight_experience_type.page.inc.
 *
 * Page callback for Flight Experience Type entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Flight Experience Type templates.
 *
 * Default template: flight_experience_type.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_flight_experience_type(array &$variables) {
  // Fetch FlightExperienceType Entity Object.
  $flight_experience_type = $variables['elements']['#flight_experience_type'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
