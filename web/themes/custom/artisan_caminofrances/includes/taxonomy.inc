<?php

/**
 * @file
 * Theme and preprocess functions for taxonomy.
 */

use Drupal\taxonomy\TermInterface;

/**
 * Implements hook_theme_suggestions_taxonomy_term_alter().
 */
function artisan_caminofrances_theme_suggestions_taxonomy_term_alter(array &$suggestions, array $variables) {
  $term = $variables['elements']['#taxonomy_term'];
  if ($term instanceof TermInterface) {
    $sanitized_view_mode = strtr($variables['elements']['#view_mode'], '.', '_');
    $suggestions[] = 'taxonomy_term__' . $sanitized_view_mode;
    $suggestions[] = 'taxonomy_term__' . $term->bundle() . '__' . $sanitized_view_mode;
  }
}
