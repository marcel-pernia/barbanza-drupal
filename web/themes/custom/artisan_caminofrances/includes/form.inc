<?php

/**
 * @file
 * Theme and preprocess functions for forms.
 */

/**
 * Implements hook_form_alter().
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function artisan_caminofrances_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'user_login_form') {
    $form['name']['#attributes']['placeholder'] = $form['name']['#title'];
    $form['pass']['#attributes']['placeholder'] = $form['pass']['#title'];
  }
  if ($form_id == 'user_pass') {
    $form['name']['#attributes']['placeholder'] = $form['name']['#title'];
  }
}
