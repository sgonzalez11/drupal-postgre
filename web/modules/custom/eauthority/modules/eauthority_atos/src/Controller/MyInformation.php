<?php

namespace Drupal\eauthority_atos\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\eauthority\Services\EauthorityService;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MyInformation extends ControllerBase {

  use StringTranslationTrait;

  /**
   * @var \Drupal\eauthority_atos\Repositories\AtosRepositoryService
   */
  protected $repository;

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
    $courses = $this->eauthority_service->getCurrentCoursesOrganization();

    return array(
      '#theme' => 'my_information',
      '#organization' => $organization,
      '#courses' => $courses,
      '#title' => t('My Training Record')
    );

  }

}