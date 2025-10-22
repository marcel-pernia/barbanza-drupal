<?php

namespace Drupal\analytics\Render;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Component\Render\MarkupTrait;

/**
 * Defines an object that passes safe strings through the render system.
 *
 * This object should only be constructed with a known safe string. If there is
 * any risk that the string contains user-entered data that has not been
 * filtered first, it must not be used.
 */
final class AnalyticsJsMarkup implements MarkupInterface, \Countable {
  use MarkupTrait;

  /**
   * @var bool
   */
  static $isDntEnforced;

  /**
   * @{inheritdoc}
   */
  public static function create($string) {
    if ($string instanceof self) {
      return $string;
    }
    $string = (string) $string;
    if ($string === '') {
      return '';
    }

    if (static::isDntEnforced()) {
      $string = "if (!navigator.doNotTrack && !window.doNotTrack && !navigator.msDoNotTrack) {\n" . $string . "\n}";
    }

    $safe_string = new static();
    $safe_string->string = $string;
    return $safe_string;
  }

  /**
   * Checks if the Do Not Track protection is enabled.
   *
   * @return bool
   */
  public static function isDntEnforced() {
    if (!isset(static::$isDntEnforced)) {
      static::$isDntEnforced = \Drupal::config('analytics.settings')->get('privacy.dnt');
    }
    return static::$isDntEnforced;
  }

}
