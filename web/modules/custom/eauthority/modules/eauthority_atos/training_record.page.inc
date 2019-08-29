<?php

/**
 * @file
 * Contains training_record.page.inc.
 *
 * Page callback for Training Record entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Training Record templates.
 *
 * Default template: training_record.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_training_record(array &$variables) {
  // Fetch TrainingRecord Entity Object.
  $training_record = $variables['elements']['#training_record'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
