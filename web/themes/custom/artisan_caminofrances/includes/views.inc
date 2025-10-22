<?php

/**
 * @file
 * Theme and preprocess functions for views.
 */

use Drupal\views\ViewExecutable;

/**
 * Implements hook_theme_suggestions_views_view_alter().
 */
function artisan_caminofrances_theme_suggestions_views_view_alter(array &$suggestions, array $variables) {
  if ($variables['view'] instanceof ViewExecutable) {
    $suggestions[] = 'views_view__' . $variables['view']->id();
  }
}
