<?php

/**
 * @file
 * Theme and preprocess functions for nodes.
 */

/**
 * Implements hook_preprocess_node() for case town_hall vlsuite_full_content_bottom.
 */
function artisan_caminofrances_preprocess_node__town_hall__vlsuite_full_content_bottom(&$variables) {
  $variables['practical_information'] = views_embed_view('town_hall_practical_information', 'main', $variables['elements']['#node']->id());
}

/**
 * Implements hook_preprocess_node() for case town_hall vlsuite_full_content_top_kioskh.
 */
function artisan_caminofrances_preprocess_node__town_hall__vlsuite_full_content_top_kioskh(&$variables) {
  $node = $variables['elements']['#node'];
  $variables['kiosk_slides_count'] = process_kiosk_slides($node);
}

/**
 * Implements hook_preprocess_node() for case practical_informationl vlsuite_full_content_top_kioskh.
 */
function artisan_caminofrances_preprocess_node__practical_information__vlsuite_full_content_top_kioskh(&$variables) {
  $node = $variables['elements']['#node'];
  $variables['kiosk_slides_count'] = process_kiosk_slides($node);
}

/**
 * Helper to get number of media entities referenced on field_kiosk_slider.
 */
function process_kiosk_slides($node) {
  if ($node->hasField('field_kiosk_slides') && !$node->get('field_kiosk_slides')->isEmpty()) {
    return count($node->get('field_kiosk_slides')->referencedEntities());
  }
  return 0;
}
