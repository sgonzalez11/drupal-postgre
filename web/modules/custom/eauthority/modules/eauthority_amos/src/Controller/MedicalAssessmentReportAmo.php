<?php

namespace Drupal\eauthority_amos\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\eauthority_amos\Entity\MedicalAssessmentInterface;
use Drupal\webform\Entity\WebformSubmission;

class MedicalAssessmentReportAmo extends ControllerBase {

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * @param \Drupal\eauthority_amos\Entity\MedicalAssessmentInterface $medical_assessment
   *
   * @return array
   */
  public function getReport(MedicalAssessmentInterface $medical_assessment) {

    $medical_examinations = $this->_getMedicalExaminations($medical_assessment->id());
    $medical_history = $this->_getMedicalHistory($medical_assessment->get('medical_history')->target_id);

    return array(
      '#theme' => 'medical_assessment__report_amo',
      '#title' => 'Medical Assessment Inform',
      '#medical_assessment' => $medical_assessment,
      '#medical_examinations' => $medical_examinations,
      '#medical_history' => $medical_history,
    );

  }

  /**
   * @param $maid
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function _getMedicalExaminations($maid) {

    $examinations = $this->entityTypeManager()->getStorage('medical_examination')->loadByProperties(['medical_assessment' => $maid]);

    return $examinations;

  }

  /**
   * @param $medical_history
   *
   * @return \Drupal\Core\Entity\EntityInterface|\Drupal\webform\Entity\WebformSubmission|null
   */
  protected function _getMedicalHistory($medical_history) {

    $applicant_webform_submission = WebformSubmission::load($medical_history);

    return $applicant_webform_submission;

  }

}