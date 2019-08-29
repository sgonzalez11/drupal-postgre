<?php

/**
 * @file
 * Contains document_parameter.page.inc.
 *
 * Page callback for Document Parameter entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Document Parameter templates.
 *
 * Default template: document_parameter.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_document_parameter(array &$variables) {
  // Fetch DocumentParameter Entity Object.
  $document_parameter = $variables['elements']['#document_parameter'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
