<?php

namespace Drupal\eauthority;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Document Parameter entities.
 *
 * @ingroup eauthority
 */
class DocumentParameterListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Document Parameter ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\eauthority\Entity\DocumentParameter */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.document_parameter.edit_form',
      ['document_parameter' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
