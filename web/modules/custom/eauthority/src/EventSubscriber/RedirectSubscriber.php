<?php

namespace Drupal\eauthority\EventSubscriber;

use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Drupal\domain\DomainNegotiatorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;


/**
 * Subscribes to the Kernel Request event and redirects to the homepage
 * when the user has the "non_grata" role.
 */
class RedirectSubscriber implements EventSubscriberInterface {

  /**
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $currentRouteMatch;

  /**
   * The domain negotiator service.
   *
   * @var \Drupal\domain\DomainNegotiatorInterface
   */
  protected $domainNegotiator;

  /**
   * @var \Drupal\Core\Language\LanguageManagerInterface $language_manager
   */
  protected $languageManager;

  /**
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $request;

  /**
   * EauthorityRedirectSubscriber constructor.
   *
   * @param \Drupal\domain\DomainNegotiatorInterface $negotiator
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   * @param \Drupal\Core\Routing\CurrentRouteMatch $currentRouteMatch
   * @param \Drupal\Core\Language\LanguageManagerInterface
   * @param \Symfony\Component\HttpFoundation\RequestStack $request
   */
  public function __construct(DomainNegotiatorInterface $negotiator, AccountProxyInterface $currentUser, CurrentRouteMatch $currentRouteMatch, LanguageManagerInterface $languageManager, RequestStack $request) {
    $this->domainNegotiator = $negotiator;
    $this->currentUser = $currentUser;
    $this->currentRouteMatch = $currentRouteMatch;
    $this->languageManager = $languageManager;
    $this->request = $request;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['checkAuthStatus',0];
    return $events;
  }

  /**
   * Handler for the kernel request event.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   */
  public function checkAuthStatus(GetResponseEvent $event) {

    $domain = $this->domainNegotiator->getActiveDomain(TRUE);
    $current_prefix = $this->languageManager->getCurrentLanguage()->getId();
    $default_languagecode = $this->languageManager->getDefaultLanguage()->getId();
/*    if ($this->currentRouteMatch->getRouteName() == 'entity.medical_assessment.edit_form') {
      // obtain the url
      $ma = $this->currentRouteMatch->getCurrentRouteMatch();
      // obtain the entity from url
      $medical_assessment = $ma->getParameters()->get('medical_assessment');
      // put the title that you want
      $route = $this->request->getCurrentRequest()->attributes->get(\Symfony\Cmf\Component\Routing\RouteObjectInterface::ROUTE_OBJECT) ;
      $route->setDefault('_title', "el titulo que vos quieras");
    }*/

    global $base_url;
    if ($this->currentUser->isAnonymous() &&
        $this->currentRouteMatch->getRouteName() != 'user.login' &&
        $this->currentRouteMatch->getRouteName() != 'user.reset' &&
        $this->currentRouteMatch->getRouteName() != 'user.reset.form' &&
        $this->currentRouteMatch->getRouteName() != 'user.reset.login' &&
        $this->currentRouteMatch->getRouteName() != 'user.pass' &&
        $domain->id() != 'customers_portal') {

        $route_name = $this->currentRouteMatch->getRouteName();
        if (strpos($route_name, 'view') === 0 && strpos($route_name, 'rest_') !== FALSE) {
          return;
        }
        // Checked because the default language(spanish) doesn't have a prefix, removed by configuration
        if ($default_languagecode == $current_prefix && $current_prefix == "es") {
          $response = new RedirectResponse($base_url . '/user/login', 301);
        } else {
          $response = new RedirectResponse($base_url . '/' . $current_prefix . '/user/login', 301);
        }
        $response->send();
        return;

    }
//    $roles = $this->currentUser->getRoles();
//    if(in_array('anonymous', $roles)) {
//      $url = Url::fromUri('internal:/');
//      $event->setResponse(new RedirectResponse($url->toString()));
//    }
  }
}