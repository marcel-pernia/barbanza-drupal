<?php

namespace Drupal\analytics_piwik\Plugin\AnalyticsService;

use Drupal\analytics\Plugin\ServicePluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Analytics service type.
 *
 * @AnalyticsService(
 *   id = "piwik",
 *   label = @Translation("Piwik"),
 * )
 */
class Piwik extends ServicePluginBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'url' => '',
      'id' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm($form, FormStateInterface $form_state) {
    $form['url'] = [
      '#type' => 'textfield',
      '#title' => t('URL'),
      '#description' => t('The URL to your Piwik base directory.'),
      '#default_value' => $this->configuration['url'],
      // @todo Add validation
      //'#element_validate' => [$this->validateUrl],
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'number',
      '#title' => t('Site ID'),
      '#default_value' => $this->configuration['id'],
      '#min' => 0,
      '#required' => TRUE,
      '#size' => 15,
    ];
    return $form;
  }

  function validateUrl($element, &$form_state) {
    $value = $element['#value'];
    if ($value != '') {
      // Make sure the URL is normalized.
      $value = rtrim($value, '/') . '/';
      form_set_value($element, $value, $form_state);

      if (!valid_url($value, TRUE)) {
        form_error($element, t('%name is not a valid URL.', ['%name' => $element['#title']]));
      }
      else {
        $request = drupal_http_request($value . '/piwik.js');
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getOutput() {
    $output = [];
    // This is just placeholder code.
    $output['analytics_' . $this->getServiceId()] = [
      '#type' => 'html_tag',
      '#tag' => 'piwik',
      '#attributes' => [
        'src' => $this->configuration['url'],
        'site_id' => $this->configuration['id'],
      ],
    ];
    return $output;
  }

}
