<?php

namespace Drupal\eauthority\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;
use Drupal\search\Form\SearchBlockForm;
use Drupal\file\Entity\File;
use Drupal\block\Entity\Block;
use Drupal\image\Entity\ImageStyle;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Menu\MenuTreeParameters;

// Cart
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\commerce_cart\CartProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * @Shortcode(
 *   id = "nd_megamenu",
 *   title = @Translation("Mega Menu"),
 *   description = @Translation("Render megamenu"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-bars"
 * )
 */
class MegamenuShortcode extends ShortcodeBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new MegamenuShortcode.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\commerce_cart\CartProviderInterface $cart_provider
   *   The cart provider.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $cart_provider = NULL, EntityTypeManagerInterface $entity_type_manager = NULL) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      \Drupal::moduleHandler()->moduleExists('commerce') ? $container->get('commerce_cart.cart_provider') : [],
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $menu_name = isset($attrs['menu']) ? $attrs['menu'] : 'main';
    $type = isset($attrs['type']) ? $attrs['type'] : '';

	  $attrs['class'] = isset($attrs['font_color']) ? $attrs['font_color'] : '';
	  $classes = $type . ' ';
	  $classes .= isset($attrs['mobile_color']) ? $attrs['mobile_color'] . ' ' : '';
	  $body_classes = 'c-layout-header-mobile-fixed';
	  $body_classes .= ' c-header-not-fixed';

	  // Menu.
	  if (\Drupal::moduleHandler()->moduleExists('tb_megamenu')) {
		$tb_megamenu_theme = [
		  '#theme' => 'tb_megamenu',
		  '#menu_name' => $menu_name,
		];
		$menu = render($tb_megamenu_theme);
	  }
	  else {
		$menu = render_menu($menu_name);
	  }

	  // BG Color.
	  $bg_color = isset($_GET['mega_menu_bg_color']) ? $_GET['mega_menu_bg_color'] : theme_get_setting('mega_menu_bg_color');
	  // Clearing the cache due to $bg_color.
	  \Drupal::service('page_cache_kill_switch')->trigger();


	  // Login.
	  $is_authenticated = \Drupal::currentUser()->isAuthenticated() ? TRUE : FALSE;

	  // Dev Host.
	  $is_dev_host = isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'nikadevs') !== FALSE || $_SERVER['HTTP_HOST'] == 'development') ? TRUE : FALSE;

	  // Sitename.
	  $config = \Drupal::config('system.site');
	  $site_name = $config->get('name');

	  // Language.
	  $language = isset($attrs['language']) ? $attrs['language'] : FALSE;
	  $lang_code = '';
	  if ($language && \Drupal::moduleHandler()->moduleExists('language')) {
		$block = \Drupal::entityTypeManager()->getStorage('block')->load('languageswitcher');
		if(method_exists($block, 'getPlugin')) {
		  $block = $block->getPlugin()->build();
		  $language = render($block);
		  $lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
		}
	  }

	  // Menu template.
	  $theme = 'eauthority_megamenu';

	  $theme_array = [
		'#theme' => $theme,
		'#menu' => $menu,
		'#width' => isset($attrs['width']) ? $attrs['width'] : FALSE,
		'#type' => $type,
		'#class' => $classes,
		'#bg_color' => $bg_color,
		'#login' => isset($attrs['login']) ? $attrs['login'] : FALSE,
		'#is_authenticated' => $is_authenticated,
		'#is_dev_host' => $is_dev_host,
		'#language' => $language,
		'#lang_code' => $lang_code,
		'#site_name' => $site_name,
		'#body_classes' => $body_classes,
	  ];
	  $output = $this->render($theme_array);

    $attrs_output = _jango_shortcodes_shortcode_attributes($attrs);
    if ($attrs_output) {
      $output = '<div ' . $attrs_output . '>' . $output . '</div>';
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function settings($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form = [];
    $menus = menu_ui_get_menus();
    $form['menu'] = [
      '#type' => 'select',
      '#title' => t('Menu'),
      '#default_value' => isset($attrs['menu']) ? $attrs['menu'] : '',
      '#options' => $menus,
      '#attributes' => ['class' => ['form-control']],
      '#prefix' => '<div class="row"><div class="col-sm-4">',
      '#suffix' => '</div>',
    ];
    $form['width'] = [
      '#type' => 'select',
      '#title' => t('Width'),
      '#default_value' => isset($attrs['width']) ? $attrs['width'] : '',
      '#options' => ['' => t('Default'), '-fluid' => t('Wide')],
      '#attributes' => ['class' => ['form-control']],
      '#prefix' => '<div class="col-sm-4">',
      '#suffix' => '</div>',
    ];
    // 2nd.
    $types = [
      'c-layout-header-4' => t('White'),
      'c-layout-header-3 c-layout-header-3-custom-menu' => t('Dark'),
    ];
    $form['type'] = [
      '#type' => 'select',
      '#title' => t('Type'),
      '#options' => $types,
      '#default_value' => isset($attrs['type']) ? $attrs['type'] : 'white',
      '#attributes' => ['class' => ['form-control']],
      '#prefix' => '<div class="row"><div class="col-sm-4">',
      '#suffix' => '</div>',
    ];
    $types = [
      'c-layout-header-default-mobile' => t('White'),
      'c-layout-header-dark-mobile' => t('Dark'),
    ];
    $form['mobile_color'] = [
      '#type' => 'select',
      '#title' => t('Mobile Color'),
      '#default_value' => isset($attrs['mobile_color']) ? $attrs['mobile_color'] : '',
      '#options' => $types,
      '#attributes' => ['class' => ['form-control']],
      '#prefix' => '<div class="col-sm-4">',
      '#suffix' => '</div>',
      '#states' => [
        'visible' => [
          'select[name="type"]' => ['!value' => 'menu-drop-down'],
          'select[name="type"], .abc' => ['!value' => 'c-layout-header-5'],
        ],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function description($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $value = '';
    if (isset($attrs['admin_url']) && strpos($attrs['admin_url'], 'admin/structure/menu') !== FALSE) {
      $form = MenuShortcode::settings($attrs, $text);
      $link_text = $form['admin_url']['#options'][$attrs['admin_url']];
      $link_url = Url::fromUri('internal:/' . $attrs['admin_url'], ['attributes' => ['target' => '_blank']]);
      $link = Link::fromTextAndUrl($link_text, $link_url)->toString();
      $value = $link->getGeneratedLink();
    }
    return $value;
  }
}
