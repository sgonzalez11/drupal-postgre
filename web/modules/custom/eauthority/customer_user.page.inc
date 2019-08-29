<?php

/**
 * @file
 * Contains customer_user.page.inc.
 *
 * Page callback for Customer User entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Customer User templates.
 *
 * Default template: customer_user.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_customer_user(array &$variables) {
  // Fetch CustomerUser Entity Object.
  $customer_user = $variables['elements']['#customer_user'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
