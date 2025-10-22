<?php

/**
 * @file
 * Theme and preprocess functions for blocks.
 */

/**
 * Implements hook_preprocess_links__language_block().
 */
function artisan_caminofrances_preprocess_links__language_block(mixed &$variables): void {
  // Since we are printing the language selector as a bootstrap dropdown, we
  // need the current language as text for the dropdown button.
  $variables['current_language_name'] = \Drupal::languageManager()->getCurrentLanguage()->getName();
  $variables['current_language'] = \Drupal::languageManager()->getCurrentLanguage()->getId();
  // Bootstrap needs a class to print links as a dropdown item.
  foreach ($variables['links'] as $id => $link) {
    /** @var \Drupal\language\Entity\ConfigurableLanguage $linkLanguage */
    $linkLanguage = $link['link']['#options']['language'];
    $variables['links'][$id]['link']['#title'] = strtoupper($id);
    $variables['links'][$id]['link']['#options']['attributes']['title'] = $linkLanguage->label();
    $variables['links'][$id]['text'] = $linkLanguage->label();
    $variables['links'][$id]['link']['#options']['attributes']['class'][] = 'dropdown-item';
    if ($id === $variables['current_language']) {
      $variables['links'][$id]['link']['#access'] = FALSE;
    }
  }
}
