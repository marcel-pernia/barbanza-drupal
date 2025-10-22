<?php

namespace Drupal\analytics_amp\Plugin\AnalyticsService;

use Drupal\analytics\Plugin\ServicePluginBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides AMP analytics.
 *
 * @AnalyticsService(
 *   id = "amp_analytics",
 *   label = @Translation("AMP Analytics"),
 *   multiple = true,
 * )
 */
class AmpAnalytics extends ServicePluginBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'type' => NULL,
      'config_url' => '',
      'config_json' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['type'] = [
      '#title' => t('Type'),
      '#type' => 'select',
      '#default_value' => $this->configuration['type'],
      '#options' => [
        // @todo Add support for all options.
        'adobeanalytics' => t('Adobe Analytics'),
        'googleanalytics' => t('Google Analytics'),
      ],
      '#required' => TRUE,
    ];
    $form['config_url'] = [
      '#title' => t('Remote configuration JSON URL'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['config_url'],
      '#placeholder' => 'https://example.com/analytics.config.json',
      // @todo Add URL validation.
      // '#element_validate' => [[$this, 'elementValidateConfigUrl']]
    ];
    $form['config_json'] = [
      '#title' => t('Inline configuration JSON'),
      '#type' => 'textarea',
      '#default_value' => $this->configuration['config_json'],
      '#description' => t('See the <a href="https://www.ampproject.org/docs/reference/extended/amp-analytics.html">amp-analytics documentation</a> for example configuration values.'),
      '#element_validate' => [
        [get_class($this), 'validateJson'],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getOutput() {
    if (\Drupal::service('router.amp_context')->isAmpRoute()) {
      return $this->getAmpOutput($this->configuration);
    }
  }

  protected function elementValidateConfigUrl(array &$element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if ($value == '') {
      return;
    }
    elseif (\Drupal::service('stream_wrapper_manager')->isValidUri($value)) {
      // Allow file URIs like public:://config.json.
      return;
    }
    elseif (!UrlHelper::isExternal($value)) {
      $form_state->setError($element, $this->t('Not a valid URL.'));
    }
  }

  protected function elementValidateConfigJson(array &$element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if ($value == '') {
      return;
    }
    elseif (is_string($value)) {
      // Otherwise attempt to convert the value to JSON.
      $data = json_decode($value, TRUE);
      if (json_last_error()) {
        $form_state->setError($element, $this->t('%name is not valid JSON.', ['%name' => $element['#title']]));
      }
      elseif ($element['#required'] && empty($data)) {
        $form_state->setError($element, $this->t('%name is required.', ['%name' => $element['#title']]));
      }
      else {
        // @todo This should attempt to validate the top-level keys.
        $form_state->setValueForElement($element, $data);
      }
    }
  }

}
