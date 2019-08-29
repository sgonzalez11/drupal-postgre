<?php

/**
 * @file
 * Contains customer_training_courses.page.inc.
 *
 * Page callback for Customer Training Course entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Customer Training Course templates.
 *
 * Default template: customer_training_courses.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_customer_training_courses(array &$variables) {
  // Fetch CustomerTrainingCourse Entity Object.
  $customer_training_courses = $variables['elements']['#customer_training_courses'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
