<?php

namespace Drupal\analytics\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a analytics service configuration entity.
 */
interface AnalyticsServiceInterface extends ConfigEntityInterface {

  /**
   * Returns the service plugin.
   *
   * @return \Drupal\analytics\Plugin\ServicePluginInterface
   *   The analytics service plugin used by this analytics service entity.
   */
  public function getService();

  /**
   * Sets the service plugin.
   *
   * @param string $plugin_id
   *   The service plugin ID.
   * @param array $configuration
   *   The optional plugin configuration.
   */
  public function setService($plugin_id, array $configuration = []);

  /**
   * Returns the weight for the service.
   *
   * @return int
   *   The service weight.
   */
  public function getWeight();

}
