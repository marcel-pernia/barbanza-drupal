<?php

namespace Drupal\analytics\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginDependencyTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Defines a base implementation for analytics service plugins will extend.
 *
 * @ingroup analytics_api
 */
class ServicePluginBase extends PluginBase implements ServicePluginInterface {

  use StringTranslationTrait;
  use PluginDependencyTrait;

  protected $hasMultiple;

  /**
   * The ID of the service config entity using this plugin.
   *
   * @var string
   */
  protected $serviceId;

  /**
   * {@inheritdoc}
   */
  public function setServiceId($service_id) {
    $this->serviceId = $service_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getServiceId() {
    return $this->serviceId;
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    // Merge the default config.
    $this->setConfiguration($configuration);
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function canTrack() {
    // @todo Move this to an entity operation on AnalyticsService?
    $access = AccessResult::allowedIf(!\Drupal::service('router.admin_context')->isAdminRoute());
    $access->addCacheContexts(['url.path']);
    $access = $access->andIf(AccessResult::allowedIf(!\Drupal::currentUser()->hasPermission('bypass all analytics services')));
    $access->cachePerPermissions();
    \Drupal::moduleHandler()->alter('analytics_service_can_track_access', $access, $this);
    return $access->isAllowed();
  }

  public function hasMultipleInstances() {
    if (!isset($this->hasMultiple)) {
      $services = \Drupal::service('entity_type.manager')->getStorage('analytics_service')->loadMultiple();
      $count = 0;
      foreach ($services as $service) {
        if ($service->service == $this->getPluginId()) {
          $count++;
        }
      }
      $this->hasMultiple = $count >= 2;
    }
    return $this->hasMultiple;
  }

  /**
   * {@inheritdoc}
   */
  public function getOutput() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableUrls() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = NestedArray::mergeDeep(
      $this->defaultConfiguration(),
      $configuration
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    // Do nothing.
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    foreach (array_intersect_key($form_state->getValues(), $this->configuration) as $config_key => $config_value) {
      $this->configuration[$config_key] = $config_value;
    }
  }

  /**
   * Build an <amp-analytics> tag for output on an amp-enabled page request.
   *
   * @param array $settings
   *   The settings of the amp-analytics tag.
   *   - type
   *   - config_json
   *   - config_url
   *
   * @return array
   *   A structured, renderable array.
   */
  public function getAmpOutput(array $settings) {
    $output = [];
    $element = [
      '#type' => 'html_tag',
      '#tag' => 'amp-analytics',
      '#attached' => [
        'library' => 'amp/amp.analytics'
      ],
    ];
    if (!empty($settings['config_json'])) {
      $json_element = [
        '#type' => 'html_tag',
        '#tag' => 'script',
        '#attributes' => [
          'type' => 'application/ld+json',
        ],
        '#value' => $settings['config_json'],
      ];
      $element['#value'] = \Drupal::service('renderer')->renderPlain($json_element);
    }
    if (!empty($settings['type'])) {
      $element['#attributes']['type'] = $settings['type'];
    }
    if (!empty($settings['config_url'])) {
      $element['#attributes']['config'] = $settings['config_url'];
    }
    $output['analytics_' . $this->getServiceId()] = $element;

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    return [];
  }

  /**
   * Form element validation callback for a JSON textarea field.
   *
   * Note that #required is validated by _form_validate() already.
   */
  public static function validateJson(&$element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if ($value === '') {
      return;
    }

    if (is_string($value)) {
      $name = empty($element['#title']) ? $element['#parents'][0] : $element['#title'];
      $json = json_decode($value, TRUE);
      if ($error = json_last_error()) {
        $form_state->setError($element, t('%name is not valid JSON: @error.', [
          '%name' => $name,
          '@error' => json_last_error_msg(),
        ]));
        return;
      }
    }
  }

}
