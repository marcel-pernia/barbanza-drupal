<?php

namespace Drupal\analytics_google\Plugin\AnalyticsService;

use Drupal\analytics\Plugin\ServicePluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Analytics service type.
 *
 * @AnalyticsService(
 *   id = "google_ga",
 *   label = @Translation("Google Analytics (ga.js)"),
 *   multiple = true,
 * )
 */
class GoogleAnalyticsGa extends ServicePluginBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'id' => NULL,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['id'] = [
      '#type' => 'number',
      '#title' => t('Tracking ID'),
      '#default_value' => $this->configuration['id'],
      '#min' => 0,
      '#required' => TRUE,
      '#size' => 15,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getOutput() {
    $output = [];
    // This is just placeholder code.
    $output['analytics_' . $this->getServiceId()] = [
      '#type' => 'html_tag',
      '#tag' => 'googleanalytics',
      '#attributes' => [
        'tracking_id' => $this->configuration['id'],
      ],
    ];
    return $output;
  }

}
