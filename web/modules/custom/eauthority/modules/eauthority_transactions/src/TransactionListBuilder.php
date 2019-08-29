<?php

namespace Drupal\eauthority_transactions;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\taxonomy\Entity\Term;
use Drupal\eauthority_transactions\Entity\TransactionStatus;
/**
 * Defines a class to build a listing of Transaction entities.
 *
 * @ingroup eauthority_transactions
 */
class TransactionListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Transaction ID');
    $header['name'] = $this->t('Name');
    $header['bundle'] = $this->t('type');
    $header['transaction_status'] = $this->t('Status');  
    //$header['notes'] = $this->t('Notes');  
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\eauthority_transactions\Entity\Transaction */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.transaction.edit_form',
      ['transaction' => $entity->id()]
    );
      
    $row['bundle'] = $entity->bundle();  
	  
    $tsid = $entity->get('transaction_status')->target_id;
    
    if ($tsid) {
        $transaction_status = TransactionStatus::load($tsid);
        $row['transaction_status'] = $transaction_status->getName();
    } else {
        $row['transaction_status'] = '#undefined#';
    }
      
      //$row['notes'] = $entity->get('notes')->value;

      return $row + parent::buildRow($entity);
      
  }

}
