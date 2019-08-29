<?php

/**
 * @file
 * Contains medical_assessment.page.inc.
 *
 * Page callback for Medical Assessment entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Medical Assessment templates.
 *
 * Default template: medical_assessment.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_medical_assessment(array &$variables) {
  // Fetch MedicalAssessment Entity Object.
  $medical_assessment = $variables['elements']['#medical_assessment'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
