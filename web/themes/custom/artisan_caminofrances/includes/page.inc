<?php

/**
 * @file
 * Theme and preprocess functions for nodes.
 */

/**
 * Implements hook_preprocess_html().
 */
function artisan_caminofrances_preprocess_html(&$variables) {
  if (theme_get_setting('sticky_header')) {
    $variables['attributes']['class'][] = 'sticky-header';
  }
  $request = \Drupal::request();
  $query = $request->query->all();

  if (isset($query['kioskh']) || isset($query['kioskf']) || isset($query['kioskv'])) {
    $variables['attributes']['class'][] = 'hide-cookiesjsr';
  }
}



/**
 * Implements hook_preprocess_page().
 */
function artisan_caminofrances_preprocess_page__user__login(&$variables) {
  artisan_caminofrances_get_site_config($variables);
}

/**
 * Implements hook_preprocess_page().
 */
function artisan_caminofrances_preprocess_page__user__password(&$variables) {
  artisan_caminofrances_get_site_config($variables);
}

/**
 * Helper function to get site config to be used on login/password reset pages.
 */
function artisan_caminofrances_get_site_config(&$variables) {
  $site_name = \Drupal::config('system.site')->get('name');
  $variables['site_name'] = $site_name;
  $variables['logo_path'] = '/' . theme_get_setting('logo.path');
  return $variables;
}

/**
 * Implements hook_theme_suggestions_page_alter().
 */
function artisan_caminofrances_theme_suggestions_page_alter(array &$suggestions) {
  if (in_array('page__node__fullwidth', $suggestions)) {
    if (in_array('page__kioskh', $suggestions)) {
      $suggestions[] = 'page__node__fullwidth__kioskh';
    }
    elseif (in_array('page__kioskv', $suggestions)) {
      $suggestions[] = 'page__node__fullwidth__kioskv';
    }
  }
}
