<?php
/**
 * @file
 * Contains Drupal\eauthority_appointments\CodeSamplesServiceProvider
 */

namespace Drupal\eauthority_appointments;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Modifies two sample services from core.
 */
class EauthorityHelperServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    // Overrides authentication class to test custom authentication capabilities.
    $definition = $container->getDefinition('authentication');
    $definition->setClass('Drupal\eauthority_appointments\CustomAuthenticationManager');
  }
}
