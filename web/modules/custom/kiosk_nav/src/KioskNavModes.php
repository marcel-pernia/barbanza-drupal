<?php

namespace Drupal\kiosk_nav;

/**
 * Defines kiosk navigation modes.
 */
enum KioskNavModes: string {
  case Horizontal = 'kioskh';
  case Vertical = 'kioskv';

  /**
   * Get mode label.
   */
  public function label(): string {
    return match($this) {
      static::Horizontal => t('Horizontal'),
      static::Vertical => t('Vertical'),
    };
  }

}
