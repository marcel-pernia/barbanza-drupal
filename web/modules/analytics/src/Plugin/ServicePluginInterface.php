<?php

namespace Drupal\analytics\Plugin;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Component\Plugin\DependentPluginInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Defines an interface for analytics service plugins.
 */
interface ServicePluginInterface extends PluginInspectionInterface, DependentPluginInterface, ConfigurableInterface, PluginFormInterface {

  /**
   * Sets the current service config entity ID that is using this plugin.
   *
   * @param string $service_id
   *   The service config entity ID.
   */
  public function setServiceId($service_id);

  /**
   * Gets the current service config entity ID that is using this plugin.
   *
   * @return string
   *   The service config entity ID.
   */
  public function getServiceId();

  /**
   * Returns the label of the analytics service.
   *
   * @return string
   *   The label of this analytics service.
   */
  public function getLabel();

  /**
   * Determines if the current service can track the current request.
   *
   * @return bool
   *   TRUE if the service should output on the current page, otherwise FALSE.
   */
  public function canTrack();

  /**
   * Returns the output of the analytics service.
   *
   * @return array
   *   A structured, renderable array.
   */
  public function getOutput();

  /**
   * @return array
   */
  public function getCacheableUrls();

}
