<?php

namespace Drupal\analytics_amp\Plugin\AnalyticsService;

use Drupal\analytics\Plugin\ServicePluginBase;
use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;

/**
 * Analytics service type.
 *
 * @AnalyticsService(
 *   id = "amp_tracking_pixel",
 *   label = @Translation("AMP Tracking Pixel"),
 *   multiple = true,
 * )
 */
class AmpTrackingPixel extends ServicePluginBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'url' => NULL,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['url'] = [
      '#title' => t('URL'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['url'],
      '#description' => $this->t('See the <a href="@url">substitutions guide</a> to see what variables can be included in the URL.', ['@url' => 'https://github.com/ampproject/amphtml/blob/master/spec/amp-var-substitutions.md']),
      '#required' => TRUE,
      '#placeholder' => 'https://foo.com/pixel?RANDOM',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getOutput() {
    if (\Drupal::service('router.amp_context')->isAmpRoute()) {
      $output['analytics_' . $this->getServiceId()] = [
        '#type' => 'html_tag',
        '#tag' => 'amp-pixel',
        '#attributes' => [
          'id' => Html::getUniqueId('analytics_' . $this->getServiceId()),
          'src' => $this->configuration['url'],
        ],
      ];
      return $output;
    }
  }

}
