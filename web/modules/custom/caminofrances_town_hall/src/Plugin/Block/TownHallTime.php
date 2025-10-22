<?php

namespace Drupal\caminofrances_town_hall\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a townhalltime block.
 *
 * @Block(
 *   id = "caminofrances_town_hall_time",
 *   admin_label = @Translation("Town hall time"),
 *   category = @Translation("Camino Francés"),
 * )
 */
final class TownHallTime extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build = [];
    $build['content'] = [
      'time' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['real-time']],
      ],
    ];
    $build['#attributes']['class'][] = 'caminofrances-town-hall-context-time';
    $build['#attached']['library'][] = 'caminofrances_town_hall/time';
    return $build;
  }

}
