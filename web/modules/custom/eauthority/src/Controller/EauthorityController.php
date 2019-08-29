<?php

namespace Drupal\eauthority\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\eauthority\EauthorityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for System routes.
 */
class EauthorityController extends ControllerBase {

  /**
   * System Manager Service.
   *
   * @var \Drupal\eauthority\EauthorityManager
   */
  protected $eauthorityManager;

  /**
   * Constructs a new EauthorityController.
   *
   * @param \Drupal\eauthority\EauthorityManager $eauthorityManager
   *   System manager service.
   */
  public function __construct(EauthorityManager $eauthorityManager) {
    $this->eauthorityManager = $eauthorityManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('eauthority.manager')
    );
  }

  /**
   * Provides a single block from the administration menu as a page.
   */
  public function eauthorityAdminMenuBlockPage() {
    return $this->eauthorityManager->getBlockContents();
  }

}
