<?php

namespace Drupal\kiosk_nav\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\kiosk_nav\KioskNavHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Kiosk Navigation' condition.
 *
 * @Condition(
 *   id = "kiosk_nav_condition",
 *   label = @Translation("Kiosk Navigation"),
 * )
 */
final class KioskNavCondition extends ConditionPluginBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs a new KioskNavCondition instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    private readonly KioskNavHelper $kioskNavHelper,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('kiosk_nav.helper'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return ['modes' => []] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state): array {
    $form['modes'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Modes'),
      '#options' => KioskNavHelper::getModesOptions(),
      '#default_value' => $this->configuration['modes'],
    ];
    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state): void {
    $this->configuration['modes'] = array_filter($form_state->getValue('modes'));
    parent::submitConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function summary(): string {
    return (string) $this->t(
      'Modes: @modes', ['@modes' => implode(' ,', $this->configuration['modes'])],
    );
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate() {
    if (empty($this->configuration['modes']) && !$this->isNegated()) {
      return TRUE;
    }
    return (bool) array_intersect($this->configuration['modes'], [$this->kioskNavHelper->getCurrentMode()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $contexts = parent::getCacheContexts();
    $kiosk_nav_modes = KioskNavHelper::getModes();
    foreach ($kiosk_nav_modes as $kiosk_nav_mode) {
      $contexts[] = 'url.query_args:' . $kiosk_nav_mode;
    }
    return $contexts;
  }

}
