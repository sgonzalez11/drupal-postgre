<?php

namespace Drupal\eauthority_amos\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\maestro\Engine\MaestroEngine;
use Drupal\maestro\Utility\TaskHandler;
use Drupal\maestro\Utility\MaestroStatus;
use Drupal\maestro_taskconsole\Controller\MaestroTaskConsoleController;
use Drupal\Core\Url;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Component\Serialization\Json;
use Drupal\maestro\Controller\MaestroOrchestrator;
use Drupal\system\Controller\EntityAutocompleteController;
use Drupal\views\Views;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\CssCommand;

class EauthorityTaskConsole extends MaestroTaskConsoleController {

  public function getTasks($highlightQueueID = 0) {

    $build = parent::getTasks($highlightQueueID);

    $build['task_console_table']['#header'][2] = $this->t('Start date');
    $build['task_console_table']['#header'][5] = $this->t('Transaction Number');
    $build['task_console_table']['#header'][6] = $this->t('Service Type');
    $build['task_console_table']['#header'][7] = $this->t('Requester');
    $build['task_console_table']['#header'][8] = $this->t('Certificate Class');
    $build['task_console_table']['#header'][9] = $this->t('Scheduled shift');
    $build['task_console_table']['#header'][10] = $this->t('State');


    $out = array_splice($build['task_console_table']['#header'], 5, 1);
    array_splice($build['task_console_table']['#header'], 1, 0, $out);
    $out = array_splice($build['task_console_table']['#header'], 6, 1);
    array_splice($build['task_console_table']['#header'], 2, 0, $out);
    $out = array_splice($build['task_console_table']['#header'], 7, 1);
    array_splice($build['task_console_table']['#header'], 3, 0, $out);
    $out = array_splice($build['task_console_table']['#header'], 8, 1);
    array_splice($build['task_console_table']['#header'], 4, 0, $out);
    $out = array_splice($build['task_console_table']['#header'], 9, 1);
    array_splice($build['task_console_table']['#header'], 5, 0, $out);
    $out = array_splice($build['task_console_table']['#header'], 10, 1);
    array_splice($build['task_console_table']['#header'], 6, 0, $out);

    $queueIDs = MaestroEngine::getAssignedTaskQueueIds(\Drupal::currentUser()->id());

    foreach ($queueIDs as $queueID) {

      // $processID = MaestroEngine::getProcessIdFromQueueId($queueID);
      $queueEntry = MaestroEngine::getQueueEntryById($queueID);
      $processId = $queueEntry->process_id->getString();

      // Load Submission ID
      $sid = FALSE;  // init the $sid
      $sid = MaestroEngine::getEntityIdentiferByUniqueID($processId, 'submission');

      // Load Submission using the sid.
      $webform_submission = \Drupal\webform\Entity\WebformSubmission::load($sid);

      // Get Submission Data.
      $data = $webform_submission->getData();

      // Get tipo de tramite y certificado medico.
      $service_type = $data['tipo_de_tramite'];
      $certificate_class = $data['certificado_medico'];

      // Get Transactions entity using sid.
      $transactions = $this->entityTypeManager()->getStorage('transaction')->loadByProperties(['submission' => $sid]);
      if (isset($transactions)) {
        $transaction = reset($transactions);
        $transaction_name = $transaction->get('name')->value;
      } else {
        $transaction_name = " ";
      }

      // Request variable
      $requester = MaestroEngine::getProcessVariable('username', $processId);

      // Load the requested user
      $users = $this->entityTypeManager()->getStorage('user')->loadByProperties(['name' => $requester]);
      $user = reset($users);
      $full_name = $user->get('field_first_name')->value .' '. $user->get('field_last_name')->value;

      $build['task_console_table'][$queueID]['transaction_number'] = ['#plain_text' => $transaction_name];
      $build['task_console_table'][$queueID]['service_type'] = ['#plain_text' => $service_type];
      $build['task_console_table'][$queueID]['requester'] = ['#plain_text' => ($full_name) ? $full_name : $this->t('No  data available')];
      $build['task_console_table'][$queueID]['certificate_class'] = ['#plain_text' => ($certificate_class) ? 'C'.$certificate_class : $this->t('No  data available')];
      $build['task_console_table'][$queueID]['scheduled_shift'] = ['#plain_text' => ''];
      $build['task_console_table'][$queueID]['state'] = ['#plain_text' => 'New'];

      $out = array_splice($build['task_console_table'][$queueID], 6, 1);
      array_splice($build['task_console_table'][$queueID], 2, 0, $out);
      $out = array_splice($build['task_console_table'][$queueID], 7, 1);
      array_splice($build['task_console_table'][$queueID], 3, 0, $out);
      $out = array_splice($build['task_console_table'][$queueID], 8, 1);
      array_splice($build['task_console_table'][$queueID], 4, 0, $out);
      $out = array_splice($build['task_console_table'][$queueID], 9, 1);
      array_splice($build['task_console_table'][$queueID], 5, 0, $out);
      $out = array_splice($build['task_console_table'][$queueID], 10, 1);
      array_splice($build['task_console_table'][$queueID], 6, 0, $out);
      $out = array_splice($build['task_console_table'][$queueID], 11, 1);
      array_splice($build['task_console_table'][$queueID], 7, 0, $out);
    }

    return $build;

  }

  public function getStatus($processID, $queueID) {
    return parent::getStatus($processID, $queueID);
  }

  public function closeStatus($processID, $queueID) {
    return parent::closeStatus($processID, $queueID);
  }

}