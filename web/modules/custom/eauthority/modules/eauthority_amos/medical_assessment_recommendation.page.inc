<?php

/**
 * @file
 * Contains med_assessmt_recommendation.page.inc.
 *
 * Page callback for Medical Assessment Recommendations entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Medical Assessment Recommendations templates.
 *
 * Default template: med_assessmt_recommendation.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_medical_assessment_recommendatio(array &$variables) {
  // Fetch MedicalAssessmentRecommendation Entity Object.
  $medical_assessment_recommendatio = $variables['elements']['#med_assessmt_recommendation'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
