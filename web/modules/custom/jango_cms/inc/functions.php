<?php

/**
 * @return mixed
 */
function drupal_get_title() {
  $request = \Drupal::request();
  $route_match = \Drupal::routeMatch();
  $title_obj = \Drupal::service('title_resolver');
  $route = $route_match->getRouteObject();
  return $title_obj && $route ? $title_obj->getTitle($request, $route) : '';
}
