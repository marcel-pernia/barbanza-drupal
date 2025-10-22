<?php

/**
 * @file
 * Theme and preprocess functions for menus.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Url;
use Drupal\media\MediaInterface;
use Drupal\menu_item_extras\Entity\MenuItemExtrasMenuLinkContentInterface;
use Drupal\taxonomy\TermInterface;

/**
 * Implements hook_preprocess_menu__main__kioskv().
 */
function artisan_caminofrances_preprocess_menu__main__kioskv(&$variables) {
  // SDC does not support preprocess so use default menu template preprocess.
  _artisan_caminofrances_preprocess_menu_icon_level($variables['items'], FALSE, TRUE);
}

/**
 * Implements hook_preprocess_menu__main__kioskv().
 */
function artisan_caminofrances_preprocess_menu__main__kioskh(&$variables) {
  // SDC does not support preprocess so use default menu template preprocess.
  _artisan_caminofrances_preprocess_menu_icon_level($variables['items'], TRUE, TRUE);
}

/**
 * Implements hook_preprocess_menu__towh_hall_menu__kioskv().
 */
function artisan_caminofrances_preprocess_menu__town_hall_menu__kioskv(&$variables) {
  // SDC does not support preprocess so use default menu template preprocess.
  _artisan_caminofrances_preprocess_menu_icon_level($variables['items'], FALSE, TRUE);
}

/**
 * Implements hook_preprocess_menu__towh_hall_menu__kioskh().
 */
function artisan_caminofrances_preprocess_menu__town_hall_menu__kioskh(&$variables) {
  // SDC does not support preprocess so use default menu template preprocess.
  _artisan_caminofrances_preprocess_menu_icon_level($variables['items'], TRUE, TRUE);
}

function artisan_caminofrances_preprocess_menu__menu_kiosk__kioskh(&$variables) {
  // SDC does not support preprocess so use default menu template preprocess.
  _artisan_caminofrances_preprocess_menu_icon_level($variables['items'], TRUE, TRUE);
}

/**
 * Menu icon level process.
 *
 * @param array $items
 *   Items of current level.
 * @param bool $title_suffix
 *   Include title as suffix or just icon.
 * @param bool $no_icon_hide
 *   No icon hide..
 */
function _artisan_caminofrances_preprocess_menu_icon_level(array &$items, bool $title_suffix, bool $no_icon_hide) {
  foreach (Element::children($items) as $delta) {
    $items[$delta] = !empty($items[$delta]) ? _artisan_caminofrances_preprocess_menu_icon_level_item($items[$delta], $title_suffix, $no_icon_hide) : $items[$delta];
    if ($items[$delta] === NULL) {
      unset($items[$delta]);
    }
  }
}

/**
 * Menu icon item process.
 *
 * @param array $item
 *   Item to process.
 * @param bool $title_suffix
 *   Include title as suffix or just icon.
 * @param bool $no_icon_hide
 *   No icon hide..
 *
 * @return array|null
 *   Item with icon as title when available.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function _artisan_caminofrances_preprocess_menu_icon_level_item(array $item, bool $title_suffix, bool $no_icon_hide) {
  // Use logo as link title & set title as link attribute.
  $term_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $vlsuite_font_helper = \Drupal::service('vlsuite_icon_font.helper');
  // Icon from menu item field.
  if ($item['url'] instanceof Url && $item['attributes'] instanceof Attribute &&
    $item['entity'] instanceof MenuItemExtrasMenuLinkContentInterface && (
      ($item['entity']->hasField('field_icon') && !$item['entity']->get('field_icon')->isEmpty()) ||
      ($item['entity']->hasField('field_icon_image') && !$item['entity']->get('field_icon_image')->isEmpty())
    )) {
    $icon_image = !$item['entity']->get('field_icon_image')->isEmpty() && !empty($item['content']['field_icon_image']) ? $item['content']['field_icon_image'][0] : NULL;
    $icon = empty($icon_image) && !$item['entity']->get('field_icon')->isEmpty() && !empty($item['content']['field_icon']) ? $item['content']['field_icon'][0] : $icon_image;
    $item['attributes']->setAttribute('title', $item['title']);
    $title_icon = $icon;
    $item['title'] = $title_suffix ? $title_icon + ['#suffix' => $item['title']] : $title_icon;
  }
  // Icon from taxonomy referenced field.
  elseif ($item['url'] instanceof Url &&
    $item['url']->isRouted() &&
    $item['url']->getRouteName() == 'entity.taxonomy_term.canonical' &&
    $item['attributes'] instanceof Attribute &&
    ($tid = $item['url']->getRouteParameters()['taxonomy_term'] ?? FALSE) &&
    (($term = $term_storage->load($tid)) instanceof TermInterface) && (
      ($term->hasField('field_icon_image') && !$term->get('field_icon_image')->isEmpty()) ||
      ($term->hasField('field_icon') && !$term->get('field_icon')->isEmpty())
    )) {
    $icon_image = !$term->get('field_icon_image')->isEmpty() && $term->get('field_icon_image')->entity instanceof MediaInterface ? [
      '#theme' => 'responsive_image_formatter',
      '#item' => $term->get('field_icon_image')->entity->get($term->get('field_icon_image')->entity->getSource()->getConfiguration()['source_field'])->first(),
      '#item_attributes' => [
        'loading' => 'eager',
      ],
      '#responsive_image_style_id' => 'vlsuite_original_optimized_small',
      '#cache' => [
        'tags' => ["media:" . $term->get('field_icon_image')->entity->id()],
      ],
    ] : NULL;
    $icon = empty($icon_image) && !$term->get('field_icon')->isEmpty() ? $vlsuite_font_helper->buildIconFontIcon($term->get('field_icon')->value) : $icon_image;
    $item['attributes']->setAttribute('title', $item['title']);
    $title_icon = $icon;
    $item['title'] = $title_suffix ? $title_icon + ['#suffix' => $item['title']] : $title_icon;
  }
  // Determine action with no icon.
  elseif ($no_icon_hide) {
    $item = NULL;
  }

  if (!empty($item['below'])) {
    _artisan_caminofrances_preprocess_menu_icon_level($item['below'], $title_suffix, $no_icon_hide);
  }
  return $item;
}
