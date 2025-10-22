<?php

namespace Drupal\analytics\Entity;

use Drupal\analytics\Plugin\ServicePluginCollection;
use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityWithPluginCollectionInterface;

/**
 * Defines the analytics instance entity.
 *
 * @ConfigEntityType(
 *   id = "analytics_service",
 *   label = @Translation("Analytics service"),
 *   label_collection = @Translation("Analytics services"),
 *   label_singular = @Translation("analytics service"),
 *   label_plural = @Translation("analytics services"),
 *   label_count = @PluralTranslation(
 *     singular = "@count analytics service",
 *     plural = "@count analytics services",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\analytics\AnalyticsServiceListBuilder",
 *     "form" = {
 *       "add" = "Drupal\analytics\Form\AnalyticsServiceForm",
 *       "edit" = "Drupal\analytics\Form\AnalyticsServiceForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "service",
 *   admin_permission = "administer analytics",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "status" = "status",
 *     "weight" = "weight",
 *   },
 *   links = {
 *     "add-form" = "/admin/config/services/analytics/add",
 *     "edit-form" = "/admin/config/services/analytics/{analytics_service}",
 *     "delete-form" = "/admin/config/services/analytics/{analytics_service}/delete",
 *     "enable" = "/admin/config/services/analytics/{analytics_service}/enable",
 *     "disable" = "/admin/config/services/analytics/{analytics_service}/disable",
 *     "collection" = "/admin/config/services/analytics",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "service",
 *     "service_configuration",
 *     "weight",
 *     "status",
 *   },
 * )
 */
class AnalyticsService extends ConfigEntityBase implements AnalyticsServiceInterface, EntityWithPluginCollectionInterface {

  /**
   * The analytics instance ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The analytics instance label.
   *
   * @var string
   */
  protected $label;

  /**
   * The plugin ID of the service.
   *
   * @var string
   */
  protected $service;

  /**
   * The configuration of the service.
   *
   * @var array
   */
  protected $service_configuration = [];

  /**
   * The weight of the configuration when splitting several folders.
   *
   * @var int
   */
  protected $weight = 0;

  /**
   * The status, whether to be used by default.
   *
   * @var bool
   */
  protected $status = TRUE;

  /**
   * An indicator whether the service is locked.
   *
   * @var bool
   */
  protected $locked = FALSE;

  /**
   * The plugin collection that stores action plugins.
   *
   * @var \Drupal\analytics\Plugin\ServicePluginCollection
   */
  protected $pluginCollection;

  /**
   * Encapsulates the creation of the services's LazyPluginCollection.
   *
   * @return \Drupal\Component\Plugin\LazyPluginCollection
   *   The service's plugin collection.
   */
  protected function servicePluginCollection() {
    if (!isset($this->pluginCollection) && isset($this->service)) {
      $this->pluginCollection = new ServicePluginCollection($this->analyticsServicePluginManager(), $this->service, $this->service_configuration, $this->id());
    }
    return $this->pluginCollection;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections() {
    return [
      'service_configuration' => $this->servicePluginCollection(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getService() {
    return $this
      ->servicePluginCollection()
      ->get($this->service);
  }

  /**
   * {@inheritdoc}
   */
  public function setService($plugin_id, array $configuration = []) {
    $this->service = $plugin_id;
    $this->service_configuration = $configuration;
    $this->servicePluginCollection()
      ->addInstanceID($plugin_id, $configuration);
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight() {
    return $this->weight;
  }

  /**
   * Gets the analytics service plugin manager.
   *
   * @return \Drupal\analytics\AnalyticsServiceManager
   */
  protected function analyticsServicePluginManager() {
    return \Drupal::service('plugin.manager.analytics.service');
  }

}


