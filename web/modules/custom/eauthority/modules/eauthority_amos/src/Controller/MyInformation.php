<?php

namespace Drupal\eauthority_amos\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\eauthority\Services\EauthorityService;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MyInformation extends ControllerBase {

  /**
   * @var \Drupal\eauthority\Services\EauthorityService
   */
  protected $eauthority_service;

  /**
   * MyTrainingRecord constructor.
   *
   * @param \Drupal\eauthority\Services\EauthorityService $eauthority_service
   */
  public function __construct(EauthorityService $eauthority_service) {
    $this->eauthority_service = $eauthority_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('eauthority.storage')
    );
  }

  /**
   * @return array
   */
  public function contentMyInformation() {

    $organization = $this->eauthority_service->getCurrentOrganization();

/*    return array(
      '#markup' => 'test',
    );*/

    return array(
      '#theme' => 'medical_information',
      '#organization' => $organization,
      '#title' => 'My Medical Record'
    );

  }

}