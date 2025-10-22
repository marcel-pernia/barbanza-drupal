<?php

namespace Drupal\caminofrances_weather\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a town hall weather block.
 *
 * @Block(
 *   id = "caminofrances_town_hall_weather",
 *   admin_label = @Translation("Town hall Weather"),
 *   category = @Translation("Camino Francés Weather"),
 * )
 */
final class Weather extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * The RouteMatch service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected RouteMatchInterface $routeMatch;

  /**
   * The controller constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteMatchInterface $routeMatch
   *   The current route match service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $routeMatch) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $routeMatch;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build = [];
    $geo = $this->getContentContextLatLon();
    if (!empty($geo['lat']) && !empty($geo['lon'])) {
      $build['content'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['town-hall-weather-block'],
          'data-lat' => $geo['lat'],
          'data-long' => $geo['lon'],
        ],
      ];
    }
    else {
      $build['#attributes']['class'][] = 'block--unavailable';
    }
    $build['#attached']['library'][] = 'caminofrances_weather/weather';
    return $build;
  }

  /**
   * Get content lat lon coordinates (town hall over practical info).
   *
   * @return array
   *   lat|lon
   */
  protected function getContentContextLatLon() {
    $node = $this->routeMatch->getParameter('node');
    $geo = [];
    if ($node instanceof NodeInterface) {
      if ($node->bundle() == 'practical_information' && $node->hasField('field_town_hall') && !$node->get('field_town_hall')->isEmpty() && $node->get('field_town_hall')->entity instanceof NodeInterface) {
        $node = $node->get('field_town_hall')->entity;
      }
      if ($node->hasField('field_geolocation') && !$node->get('field_geolocation')->isEmpty()) {
        $geo['lat'] = $node->get('field_geolocation')->first()->lat ?? '';
        $geo['lon'] = $node->get('field_geolocation')->first()->lng ?? '';
      }
    }
    return $geo;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $node = $this->routeMatch->getParameter('node');
    return $node instanceof NodeInterface ? Cache::mergeContexts(parent::getCacheContexts(), $node->getCacheContexts()) : parent::getCacheContexts();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $node = $this->routeMatch->getParameter('node');
    return $node instanceof NodeInterface ? Cache::mergeTags(parent::getCacheTags(), $node->getCacheTags()) : parent::getCacheTags();
  }

}
