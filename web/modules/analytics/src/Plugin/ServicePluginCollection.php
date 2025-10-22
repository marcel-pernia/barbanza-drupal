<?php

namespace Drupal\analytics\Plugin;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Plugin\DefaultSingleLazyPluginCollection;

class ServicePluginCollection extends DefaultSingleLazyPluginCollection {

  /**
   * The unique ID for the search page using this plugin collection.
   *
   * @var string
   */
  protected $analyticsServiceId;

  /**
   * Constructs a new SearchPluginCollection.
   *
   * @param \Drupal\Component\Plugin\PluginManagerInterface $manager
   *   The manager to be used for instantiating plugins.
   * @param string $instance_id
   *   The ID of the plugin instance.
   * @param array $configuration
   *   An array of configuration.
   * @param string $analytics_service_id
   *   The unique ID of the analytics service using this plugin.
   */
  public function __construct(PluginManagerInterface $manager, $instance_id, array $configuration, $analytics_service_id) {
    $this->analyticsServiceId = $analytics_service_id;
    parent::__construct($manager, $instance_id, $configuration);
  }

  /**
   * {@inheritdoc}
   *
   * @return \Drupal\analytics\Plugin\ServicePluginInterface
   */
  public function &get($instance_id) {
    return parent::get($instance_id);
  }

  /**
   * {@inheritdoc}
   */
  protected function initializePlugin($instance_id) {
    parent::initializePlugin($instance_id);
    /** @var \Drupal\analytics\Plugin\ServicePluginBase $plugin_instance */
    $plugin_instance = $this->pluginInstances[$instance_id];
    $plugin_instance->setServiceId($this->analyticsServiceId);
  }

}
