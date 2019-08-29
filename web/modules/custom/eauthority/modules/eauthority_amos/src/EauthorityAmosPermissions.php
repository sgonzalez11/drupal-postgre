<?php

/**
 * @file
 * Contains \Drupal\eauthority_amos\EauthorityAmosPermissions.
 */

namespace Drupal\eauthority_amos;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EauthorityAmosPermissions implements ContainerInjectionInterface {

    use StringTranslationTrait;

    /**
     * The entity type manager.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * Constructor for MyModulePermissions.
     *
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
     *   The entity type manager.
     */
    public function __construct(EntityTypeManagerInterface $entity_type_manager) {
        $this->entityTypeManager = $entity_type_manager;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
        return new static($container->get('entity_type.manager'));
    }

    /**
     * Get permissions for EauthorityAmos.
     *
     * @return array
     *   Permissions array.
     */
    public function permissions() {
        $permissions = [];
        foreach (entity_get_bundles('medical_examination') as $key => $value) {
            $permissions += [
                'allow users to view ' . $key . ' examination' => [
                    'title' => $this->t('Allow users to view ' . $value['label']),
                ]
            ];
            $permissions += [
                'allow users to edit ' . $key . ' examination' => [
                    'title' => $this->t('Allow users to edit ' . $value['label']),
                ]
            ];
        }
        return $permissions;
    }

}
