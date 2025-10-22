<?php

namespace Drupal\analytics\Plugin\AnalyticsService;

use Drupal\analytics\Plugin\ServiceDataTrait;
use Drupal\analytics\Plugin\ServicePluginBase;
use Drupal\analytics\Render\AnalyticsJsMarkup;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;

/**
 * Google Tag Manager analytics service plugin.
 *
 * @AnalyticsService(
 *   id = "google_tag_manager",
 *   label = @Translation("Google Tag Manager"),
 *   multiple = true,
 * )
 */
class GoogleTagManager extends ServicePluginBase {

  use ServiceDataTrait;

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'container_id' => '',
      'data_layer' => [
        'name' => 'dataLayer',
        'value' => '',
      ],
      'optimize_anti_flicker' => FALSE,
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
    $form['data_layer'] = [
      '#type' => 'details',
      '#title' => $this->t('Data Layer'),
      '#open' => TRUE,
    ];
    $form['data_layer']['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Data Layer variable'),
      '#default_value' => $this->configuration['data_layer']['name'],
      '#size' => 15,
      '#required' => TRUE,
    ];
    $form['data_layer']['value'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Data Layer value JSON'),
      '#default_value' => $this->configuration['data_layer']['value'],
      '#element_validate' => [
        [get_class($this), 'validateJson'],
      ],
    ];
    $form['optimize_anti_flicker'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Add the <a href="@url">Optimize anti-flicker snippet</a>', ['@url' => 'https://support.google.com/optimize/answer/7100284']),
      '#description' => $this->t('If you notice page flicker when using Google Optimize, adding this snippet can help.'),
      '#default_value' => $this->configuration['optimize_anti_flicker'],
      '#weight' => 50,
      '#return_value' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getOutput() {
    $output = [];

    $data_layer_name = $this->configuration['data_layer']['name'];
    if ($data = $this->getData()) {
      $data_layer_json = json_encode($data, JSON_PRETTY_PRINT);
      $data_layer_js = <<<END
var {$data_layer_name} = window.dataLayer = window.dataLayer || [];
{$data_layer_name}.push({$data_layer_json});
END;
      $output['#attached']['html_head'][] = [
        [
          '#type' => 'html_tag',
          '#tag' => 'script',
          '#value' => Markup::create($data_layer_js),
          // This always needs to be super early.
          '#weight' => -900,
        ],
        'analytics_' . $this->getServiceId() . '_data_layer',
      ];
    }

    if ($this->configuration['optimize_anti_flicker']) {
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

    $tag_manager_js = <<<END
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','{$data_layer_name}','{$this->configuration['container_id']}');
END;
    $output['#attached']['html_head'][] = [
      [
        '#type' => 'html_tag',
        '#tag' => 'script',
        '#value' => AnalyticsJsMarkup::create($tag_manager_js),
      ],
      'analytics_' . $this->getServiceId() . '_tag_manager',
    ];

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultData() {
    if (!empty($this->configuration['data_layer']['value'])) {
      return json_decode($this->configuration['data_layer']['value'], TRUE) ?? [];
    }
    else {
      return [];
    }
  }

}
