<?php

namespace Drupal\eauthority;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Toolbar integration handler.
 *
 * https://nireneko.com/articulo/crear-menu-toolbar-drupal-8
 */
class ToolbarHandler implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The menu link tree service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuLinkTree;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * ToolbarHandler constructor.
   *
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menu_link_tree
   *   The menu link tree service.
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   *   The current user.
   */
  public function __construct(MenuLinkTreeInterface $menu_link_tree, AccountProxyInterface $account) {
    $this->menuLinkTree = $menu_link_tree;
    $this->account = $account;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('toolbar.menu_tree'),
      $container->get('current_user')
    );
  }

  /**
   * Hook bridge.
   *
   * @return array
   *   The eauthority toolbar items render array.
   *
   * @see hook_toolbar()
   */
  public function toolbar() {
    $items['eauthority'] = [
      '#cache' => [
        'contexts' => ['user.permissions'],
      ],
    ];

    if ($this->account->hasPermission('access eauthority toolbar')) {

      $items['eauthority'] = [
        '#type' => 'toolbar_item',
        'tab' => [
          '#type' => 'link',
          '#title' => $this->t('eAuthority'),
          '#url' => Url::fromRoute('eauthority.admin'),
          '#attributes' => [
            'title' => $this->t('eAuthority'),
            'class' => [
              'toolbar-icon',
              'toolbar-icon-eauthority',
            ],
            'data-drupal-subtrees' => '',
            'data-toolbar-escape-admin' => TRUE,
          ],
        ],
        'tray' => [
          '#heading' => t('@menu_label actions', ['@menu_label' => $this->t('eAuthority')]),
          'eauthority_menu' => [
            '#type' => 'container',
            '#id' => 'eauthority',
            '#pre_render' => ['eauthority_prerender_toolbar_tray'],
            '#attributes' => [
              'class' => ['toolbar-menu-administration'],
            ],
          ],
        ],
        '#weight' => 0,
        '#attached' => [
          'library' => [
            'eauthority/toolbar',
          ],
        ],
      ];
    }

    return $items;
  }

}
