<?php

namespace Drupal\analytics\Plugin\AnalyticsService;

use Drupal\analytics\Plugin\ServicePluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;

/**
 * Google Optimize service plugin.
 *
 * @AnalyticsService(
 *   id = "google_optimize",
 *   label = @Translation("Google Optimize"),
 *   multiple = true,
 * )
 */
class GoogleOptimize extends ServicePluginBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'container_id' => '',
      'async' => FALSE,
      'anti_flicker' => FALSE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['container_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Container ID'),
      '#default_value' => $this->configuration['container_id'],
      '#required' => TRUE,
      '#size' => 15,
      '#placeholder' => 'GTM-XXXX',
    ];
    $form['async'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Async'),
      '#default_value' => $this->configuration['async'],
    ];
    $form['anti_flicker'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Add the <a href="@url">Optimize anti-flicker snippet</a>', ['@url' => 'https://support.google.com/optimize/answer/7100284']),
      '#description' => $this->t('If you notice page flicker, adding this snippet can help.'),
      '#default_value' => $this->configuration['anti_flicker'],
      '#return_value' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getOutput() {
    $output = [];

    if ($this->configuration['anti_flicker']) {
      // This only needs to be output once so it has the same ID every time.
      $output['#attached']['html_head'][] = [
        [
          '#type' => 'html_tag',
          '#tag' => 'style',
          '#value' => Markup::create('.async-hide { opacity: 0 !important}'),
        ],
        'google_analytics_optimize_anti_flicker_css',
      ];

      $optimize_js = <<<END
(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
(a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
})(window,document.documentElement,'async-hide','dataLayer',4000,
{'{$this->configuration['container_id']}':true});
END;
      $output['#attached']['html_head'][] = [
        [
          '#type' => 'html_tag',
          '#tag' => 'script',
          '#value' => Markup::create($optimize_js),
        ],
        'analytics_' . $this->getServiceId() . '_optimize_anti_flicker_js',
      ];
    }

    $output['#attached']['html_head'][] = [
      [
        '#type' => 'html_tag',
        '#tag' => 'script',
        '#attributes' => [
          'src' => 'https://www.googleoptimize.com/optimize.js?id=' . $this->configuration['container_id'],
          'async' => $this->configuration['async'],
        ],
      ],
      'analytics_' . $this->getServiceId() . '_optimize',
    ];

    return $output;
  }

}
