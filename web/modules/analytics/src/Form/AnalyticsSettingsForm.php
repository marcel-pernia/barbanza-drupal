<?php

namespace Drupal\analytics\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Settings form for the Analytics module.
 */
class AnalyticsSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'analytics_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['analytics.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('analytics.settings');

    $form['privacy'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Privacy'),
      '#tree' => TRUE,
    ];
    $form['privacy']['dnt'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Respect Do Not Track (DNT) cookies.'),
      '#default_value' => $config->get('privacy.dnt'),
    ];

    $form['advanced'] = [
      '#type' => 'details',
      '#title' => $this->t('Advanced'),
      '#open' => FALSE,
    ];
    $form['advanced']['cache_urls'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Cache files locally where possible.'),
      '#default_value' => $config->get('cache_urls'),
    ];
    $form['advanced']['disable_page_build'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Disable default analytics service rendering in hook_page_bottom().'),
      '#default_value' => $config->get('disable_page_build'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('analytics.settings')
      ->set('privacy', $form_state->getValue('privacy'))
      ->set('cache_urls', $form_state->getValue('cache_urls'))
      ->set('disable_page_build', $form_state->getValue('disable_page_build'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
