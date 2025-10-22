<?php

namespace Drupal\analytics\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines an analytics service annotation object.
 *
 * @see plugin_api
 *
 * @Annotation
 */
class AnalyticsService extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the service.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * If this plugin supports having more than one instance.
   *
   * @var bool
   */
  public $multiple = FALSE;

}
