<?php

namespace Drupal\kiosk_nav;

use Drupal\Core\Routing\AdminContext;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Defines the helper service for kiosk navigation.
 */
class KioskNavHelper {

  /**
   * Constructs a KioskNavHelper object.
   */
  public function __construct(
    private readonly RequestStack $requestStack,
    private readonly AdminContext $adminContext,
  ) {}

  /**
   * Kiosk navigation get current mode.
   *
   * @return string|null
   *   Kiosk navigation get current mode or null.
   */
  public function getCurrentMode() {
    $current_request = $this->requestStack->getCurrentRequest();
    $query = $current_request instanceof Request ? $current_request->query : NULL;
    $kiosk_nav_modes = self::getModes();
    $kiosk_nav_mode = array_reduce($kiosk_nav_modes, function ($mode, $currentParam) use ($query) {
      return $mode === NULL && $query instanceof InputBag && $query->get($currentParam) !== NULL ? $currentParam : $mode;
    }, NULL);
    return !$this->adminContext->isAdminRoute() ? $kiosk_nav_mode : NULL;
  }

  /**
   * Kiosk navigation get modes.
   *
   * @return array
   *   Kiosk navigation modes.
   */
  public static function getModes() {
    return array_keys(static::getModesOptions());
  }

  /**
   * Kiosk navigation get modes options.
   *
   * @return array
   *   Kiosk navigation modes with labels.
   */
  public static function getModesOptions() {
    return [
      KioskNavModes::Horizontal->value => KioskNavModes::Horizontal->label(),
      KioskNavModes::Vertical->value => KioskNavModes::Vertical->label(),
    ];
  }

}
