<?php

/**
 * @file
 * Contains customer_profiles.page.inc.
 *
 * Page callback for Customer Profiles entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Customer Profiles templates.
 *
 * Default template: customer_profiles.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_customer_profiles(array &$variables) {
  // Fetch CustomerProfiles Entity Object.
  $customer_profiles = $variables['elements']['#customer_profiles'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
