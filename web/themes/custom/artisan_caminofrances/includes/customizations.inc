<?php

/**
 * @file
 * Funtions to alter customizations.
 */

/**
 * Implements hook_artisan_customizations_alter().
 */
function artisan_caminofrances_artisan_customizations_alter(&$customizations) {
  // Add here customizations group delta, customization delta or combined.
  // E.g: 'btn', 'color', 'btn_color'.
  $skip_like = [
    'btn_success',
    'btn_outline_success',
    'btn_danger',
    'btn_outline_danger',
    'btn_warning',
    'btn_outline_warning',
    'btn_info',
    'btn_outline_info',
    'btn_light',
    'btn_outline_light',
    'btn_dark',
    'btn_outline_dark',
    'line_height',
    'font_weight',
    'font_size',
    'width',
    'opacity',
    'decoration',
    'spacing',
    'padding',
    'margin',
    'static',
    'radius',
    'shadow',
    'display',
    'height',
    'breadcrumb',
    'links',
    'header_top',
    'form',
    'palette_success',
    'palette_info',
    'palette_warning',
    'palette_danger',
    'palette_light',
    'palette_dark',
    'palette_branding',
    'btn_link',
    'header',
    'responsive',
    'footer',
  ];

  $skip_pattern = '/(?:' . implode('|', $skip_like) . ')/i';
  foreach ($customizations as $group_delta => $customization) {
    foreach (array_keys($customization['list'] ?? []) as $customization_delta) {
      if (preg_match($skip_pattern, $group_delta . '_' . $customization_delta)) {
        unset($customizations[$group_delta]['list'][$customization_delta]);
      }
    }
    if (empty($customizations[$group_delta]['list'])) {
      unset($customizations[$group_delta]);
    }
  }

  $customizations['kiosk'] = [
    'wrapper' => 'component',
    'label' => t('Kiosk'),
    'type_default' => 'textfield',
    'selector_default' => '.page-kioskh,.page-kioskv',
    'list' => [
      'color_primary' => [
        'label' => t('Color primary'),
        'type' => 'color',
      ],
      'color_secondary' => [
        'label' => t('Color secondary'),
        'type' => 'color',
      ],
    ],
  ];
}
