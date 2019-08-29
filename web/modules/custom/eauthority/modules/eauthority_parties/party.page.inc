<?php

/**
 * @file
 * Contains party.page.inc.
 *
 * Page callback for Party entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Party templates.
 *
 * Default template: party.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_party(array &$variables) {
  // Fetch Party Entity Object.
  $party = $variables['elements']['#party'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
