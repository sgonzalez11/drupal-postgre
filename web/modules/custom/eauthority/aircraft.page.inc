<?php

/**
 * @file
 * Contains aircraft.page.inc.
 *
 * Page callback for Aircraft entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Aircraft templates.
 *
 * Default template: aircraft.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_aircraft(array &$variables) {
  // Fetch Aircraft Entity Object.
  $aircraft = $variables['elements']['#aircraft'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
