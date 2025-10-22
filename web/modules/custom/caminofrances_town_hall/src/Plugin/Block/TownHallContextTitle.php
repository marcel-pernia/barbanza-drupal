<?php

namespace Drupal\caminofrances_town_hall\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\NodeInterface;

/**
 * Provides a town hall context title block.
 *
 * @Block(
 *   id = "caminofrances_town_hall_context_title",
 *   admin_label = @Translation("Town hall context title"),
 *   category = @Translation("Camino Francés"),
 *   context_definitions = {
 *     "node" = @ContextDefinition(
 *       "entity:node",
 *       label = @Translation("Current Node")
 *     )
 *   }
 * )
 */
class TownHallContextTitle extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build = [];
    $node = $this->getContextValue('node');
    $title = NULL;
    if ($node instanceof NodeInterface) {
      if ($node->bundle() == 'practical_information' && $node->hasField('field_town_hall') && !$node->get('field_town_hall')->isEmpty() && $node->get('field_town_hall')->entity instanceof NodeInterface) {
        $title = $node->get('field_town_hall')->entity->toLink()->toRenderable();
      }
      else {
        $title = $node->toLink()->toRenderable();
        $title['#attributes']['class'][] = 'current-page';
      }
    }
    $build['content'] = $title;
    $build['#attributes']['class'][] = 'caminofrances-town-hall-context-title';
    return $build;
  }

}
