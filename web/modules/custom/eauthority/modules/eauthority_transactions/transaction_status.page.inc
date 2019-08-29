<?php

/**
 * @file
 * Contains transaction_status.page.inc.
 *
 * Page callback for Transaction Status entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Transaction Status templates.
 *
 * Default template: transaction_status.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_transaction_status(array &$variables) {
  // Fetch TransactionStatus Entity Object.
  $transaction_status = $variables['elements']['#transaction_status'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
