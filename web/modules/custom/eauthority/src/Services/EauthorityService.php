<?php

namespace Drupal\eauthority\Services;

use Drupal\Core\Ajax\CommandInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\domain\DomainNegotiatorInterface;


class EauthorityService implements CommandInterface {

  protected $domainNegotiator;

  protected $entity_type_manager;

  protected $connection;

  protected $language_manager;

  /**
   * {@inheritdoc}
   */
  public function __construct(DomainNegotiatorInterface $domainNegotiator, EntityTypeManagerInterface $entity_type_manager, Connection $connection, LanguageManagerInterface $language_manager) {
    $this->domainNegotiator = $domainNegotiator;
    $this->entity_type_manager = $entity_type_manager;
    $this->connection = $connection;
    $this->language_manager = $language_manager;
  }

  /**
   * Get Current Training Organization.
   */
  public function getCurrentOrganization() {

    $domain = $this->domainNegotiator->getActiveDomain(TRUE);
    if($domain->id() === 'atos_portal') {
      $session_customer = (isset($_SESSION["current_ato_id"])) ? intval($_SESSION["current_ato_id"]) : 9;
    } elseif ($domain->id() === 'amos_portal') {
      $session_customer = (isset($_SESSION["current_amo_id"])) ? intval($_SESSION["current_amo_id"]) : 25;
    } else {
      $session_customer = 9;
    }

    // $imageUrl = $node->get('field_image')->entity->uri->value;
    // $node->field_machine_name->value; or $node->get('field_machine_name')->getValue(); // IS THE SAME. JUST FOR REFERENCE
    $query = $this->entity_type_manager->getStorage('customer')->load($session_customer);

    $name = '';
    $party_image = '';
    if($query) {
      // Obtain name of customer organization entity
      $name = $query->label();
      $party_image = $query->get('party_image')->entity;
    }
    // Obtain uri of file image field of customer entity
    $uri = ($party_image) ? $party_image->uri->value : '';

    $data['name'] = $name;
    $data['uri'] = $uri;

    return $data;

    /*
    $query = $this->connection->select('file_managed', 'fm');
    $query->join('customer_field_data', 'cfd', 'fm.fid = cfd.party_image__target_id');
    $query->fields('cfd', ['name']);
    $query->fields('fm', ['uri']);
    $query->where('(cfd.id = :cid)', array(':cid' => 9));
    $result =  $query->execute()->fetchAll();
    $data = [];
    foreach ($result as $record) {
      $data['name'] = $record->name;
      $data['uri'] = $record->uri;
    }
    return $data;
    */
  }

  /**
   * Retrieve courses that belong to
   */
  public function getCurrentCoursesOrganization(){

    $session_customer = (isset($_SESSION["current_ato_id"])) ? intval($_SESSION["current_ato_id"]) : 9;
    // SELECT ctcfd.name from customer_training_courses_field_data as ctcfd
    // join customer__customer_training_courses as cctc on ctcfd.id = cctc.customer_training_courses_target_id
    // join customer_field_data as cfd on cctc.entity_id = cfd.id
    // where cfd.id = 9
    $query = $this->connection->select('customer_training_courses_field_data', 'ctcfd');
    $query->join('customer__customer_training_courses', 'cctc', 'ctcfd.id = cctc.customer_training_courses_target_id');
    $query->join('customer_field_data', 'cfd', 'cctc.entity_id = cfd.id');
    $query->fields('ctcfd', ['name']);
    $query->where('(cfd.id = :cid)', array(':cid' => $session_customer));
    $result =  $query->execute();
    // print_r($result->getQueryString());exit; // View the query

    $courses = [];
    foreach ($result as $key => $record) {
      $courses[$key][] = $record->name;
    }

    return $courses;
  }


  public function getCourseType($tci) {
    $customerTrainingCourse = $this->entity_type_manager->getStorage('customer_training_courses')->load($tci);
    $type = $customerTrainingCourse->get('type')->value;
    return $type;
  }

  public function getCurrentMedicalOrganization() {
    // $imageUrl = $node->get('field_image')->entity->uri->value;
    // $node->field_machine_name->value; or $node->get('field_machine_name')->getValue(); // IS THE SAME. JUST FOR REFERENCE
    $session_customer = (isset($_SESSION["current_amo_id"])) ? intval($_SESSION["current_amo_id"]) : 25;

    $query = $this->entity_type_manager->getStorage('customer')->load($session_customer);

    $name = '';
    $party_image = '';
    if($query) {
      // Obtain name of customer organization entity
      $name = $query->label();
      $party_image = $query->get('party_image')->entity;
    }
    // Obtain uri of file image field of customer entity
    $uri = ($party_image) ? $party_image->uri->value : '';

    $data['name'] = $name;
    $data['uri'] = $uri;

    return $data;

  }

  /**
   * Return an array to be run through json_encode and sent to the client.
   */
  public function render() {
    // TODO: Implement render() method.
  }
}