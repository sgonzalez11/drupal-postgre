<?php

namespace Drupal\eauthority_atos\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\eauthority\Services\EauthorityService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TrainingRecordHelper extends ControllerBase {


  /**
   * @var \Drupal\eauthority\Services\EauthorityService;
   */
  protected $eauthority_service;

  /**
   * TrainingRecordHelper constructor.
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
   * checkCourseType retrieve type.
   *
   * @return string
   *   A string.
   */
  public function checkCourseType($tci) {

    $ajax_response = new AjaxResponse();
    $type = $this->eauthority_service->getCourseType($tci);

    $ajax_response->addCommand(new HtmlCommand('#ahi-va-el-id',$type));

    return $ajax_response;
  }

}