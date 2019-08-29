<?php

/**
 * @file
 * Contains identifier_type.page.inc.
 *
 * Page callback for Identifier Types entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Identifier Types templates.
 *
 * Default template: identifier_type.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_identifier_type(array &$variables) {
  // Fetch IdentifierType Entity Object.
  $identifier_type = $variables['elements']['#identifier_type'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
