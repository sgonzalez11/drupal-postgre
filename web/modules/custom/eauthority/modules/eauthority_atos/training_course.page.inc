<?php

/**
 * @file
 * Contains training_course.page.inc.
 *
 * Page callback for Training Course entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Training Course templates.
 *
 * Default template: training_course.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_training_course(array &$variables) {
  // Fetch TrainingCourse Entity Object.
  $training_course = $variables['elements']['#training_course'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
