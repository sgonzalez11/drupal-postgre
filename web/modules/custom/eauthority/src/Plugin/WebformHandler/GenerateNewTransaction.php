<?php

namespace Drupal\eauthority\Plugin\WebformHandler;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Render\Markup;
use \Drupal\user\Entity\User;
use Drupal\eauthority_transactions\Entity\Transaction;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformInterface;
use Drupal\webform\WebformSubmissionConditionsValidatorInterface;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\WebformTokenManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Serialization\Yaml;
use Drupal\webform\Utility\WebformYaml;

/**
 * Webform example handler.
 *
 * @WebformHandler(
 *   id = "generate_transaction",
 *   label = @Translation("Generate New Entity Transaction"),
 *   category = @Translation("eAuthority"),
 *   description = @Translation("Handler that generate programmatically a new Transaction Entity."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_IGNORED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */
class GenerateNewTransaction extends WebformHandlerBase {

  /**
   * The token manager.
   *
   * @var \Drupal\webform\WebformTokenManagerInterface
   */
  protected $tokenManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactoryInterface $logger_factory, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, WebformSubmissionConditionsValidatorInterface $conditions_validator, WebformTokenManagerInterface $token_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger_factory, $config_factory, $entity_type_manager, $conditions_validator);
    $this->tokenManager = $token_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory'),
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
      $container->get('webform_submission.conditions_validator'),
      $container->get('webform.token_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    $summary = parent::getSummary();
    return parent::getSummary();
    // [webform_submission:values:service_code]
    // medical_certificate_application
    /*  payment_reference: purro
        payment_amount: 50
        payment_status: PEN
        field_observacion: [webform_submission:values:service_code]
    */
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    $form['transaction'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Transactions settings'),
    ];
    $form['transaction']['service'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Service Code'),
      '#description' => $this->t('This service code appear on Service Pages'),
      '#required' => TRUE,
      '#default_value' => $this->configuration['service'],
    ];
    $form['transaction']['transaction_type'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Transaction Type'),
      '#description' => $this->t('Machine name that belongs to type of Transaction, eg: medical_certificate_application'),
      '#required' => TRUE,
      '#default_value' => $this->configuration['transaction_type'],
    ];

    $form['transaction']['custom_options'] = [
      '#type' => 'webform_codemirror',
      '#mode' => 'yaml',
      '#title' => $this->t('Fields Mapping Options'),
      '#description' => $this->t('Use one line per mapping indicating a transaction field and setting the value, eg: payment_reference: my text (tokens are available)'),
      '#default_value' =>  $this->configuration['custom_options'],
    ];

    $this->elementTokenValidate($form);

    return $this->setSettingsParents($form);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $data = $form_state->getValue('transaction');
    $this->configuration['service'] = $data['service'];
    $this->configuration['transaction_type'] = $data['transaction_type'];
    $this->configuration['custom_options'] = $data['custom_options'];
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param \Drupal\webform\WebformSubmissionInterface $webform_submission
   */
  public function submitForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {
    parent::submitForm($form, $form_state, $webform_submission);
  }

  /**
   * @param \Drupal\webform\WebformSubmissionInterface $webform_submission
   * @param bool $update
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {

    // Token that actually used on configuration form: [webform_submission:values:service_code]

    // Call the parent function
    parent::postSave($webform_submission, $update);

    // Validate and Convert token into text
    $data = $this->tokenManager->replace($this->configuration, $webform_submission);
    $service_code = $data['service'];
    $transaction_type = $data['transaction_type'];
    $custom_options = Yaml::decode($data['custom_options']);

    // Obtain all the services nodes. Expected just one
    $service_node = \Drupal::entityTypeManager()->getStorage('node')
      ->loadByProperties(['type' => 'service', 'field_service_code' => $service_code]);

    // Obtain just the first one
    $nid = '';
    $transaction_name = '';
    foreach ($service_node as $service) {
      $nid = $service->id();
      $document_parameter = $service->field_document_parameter->entity;
      $transaction_name = $document_parameter->getPrefix().'-'.$document_parameter->getNextNumber();
      break;
    }

    // Obtain the current uid using the interface
    $current_user = \Drupal::currentUser();
    $user = User::load($current_user->id());
    // Obtain the customer reference inside user entity
    $cid = $user->field_customer->target_id;

    // Create and Save the Transaction
    $transaction = Transaction::create([
      'type' => $transaction_type,
      'name' => $transaction_name,
      'service' => isset($nid) ? $nid : '',
      'customer' => isset($cid) ? $cid : '',
      'submission' => $webform_submission->id(),
      'transaction_status' => '1', // By Default take the open value
    ]);

    // Save the custom options
    if(isset($custom_options)) {
      foreach ($custom_options as $key => $value) {
        $transaction->set($key, $value);
      }
    }
    // Save the transaction entity
    $transaction->save();

    $document_parameter->incrementNextNumber();

  }

}