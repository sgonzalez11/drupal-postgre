<?php
namespace Drupal\maestro_insurance\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\maestro\Engine\MaestroEngine;

class CancelProcessConfirm extends ConfirmFormBase {

  /**
   * The ID of the item to delete.
   *
   * @var string
   */
  protected $id;

  /**
   * {@inheritdoc}.
   */
  public function getFormId()
  {
    return 'maestro_insurance_cancel_process_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    //Need to pull down the submission information.
    
    $sid = FALSE;
    $webform_submission = NULL;
    $sid = MaestroEngine::getEntityIdentiferByUniqueID($this->id, 'submission');
    
    if($sid) $webform_submission = \Drupal\webform\Entity\WebformSubmission::load($sid);
    if($webform_submission){
      $identifier = $webform_submission->getElementData('your_full_name');
      $identifier .= ' (' . $webform_submission->getElementData('email_address') . ')';
      
      return $this->t('Do you want to cancel the quote submission for client %email?', array('%email' => $identifier));
    }
    else {
      $processEntity = MaestroEngine::getProcessEntryById($this->id);
      return $this->t('Do you want to cancel the quote submission with an ID: %id titled: %processname?', array('%id' => $this->id, '%processname' => $processEntity->process_name->getString()));
    }
    
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('maestro_taskconsole.taskconsole');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    //a brief desccription
    return $this->t('Cancelling will remove the tasks and halt processing of this quote request.');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Cancel it Now!');
  }


  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return $this->t('Return to your tasks');
  }

  /**
   * {@inheritdoc}
   *
   * @param int $id
   *   (optional) The ID of the item to be deleted.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $processID = NULL) {
    $this->id = $processID;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    //$this->id is the processID we shall flag as being ended
    MaestroEngine::endProcess($this->id);
    //Send Notification?
    
    //What do we do about the user?  Leave it as-is?
  }
}

