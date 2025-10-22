<?php

namespace Drupal\analytics\Plugin;

trait ServiceDataTrait {

  /**
   * @return array
   */
  abstract public function defaultData();

  /**
   * @return array
   */
  public function getData() {
    $data = $this->defaultData();
    $types = [
      'analytics_' . $this->getPluginId() . '_data',
      'analytics_' . $this->getServiceId() . '_data',
    ];
    $this->moduleHandler()->alter($types, $data, $this);
    return $data;
  }

  /**
   * Wraps the module handler.
   *
   * @return \Drupal\Core\Extension\ModuleHandlerInterface
   *   The module handler.
   */
  abstract protected function moduleHandler();

}
