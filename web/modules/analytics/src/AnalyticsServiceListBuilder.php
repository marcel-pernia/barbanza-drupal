<?php

namespace Drupal\analytics;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a listing of analytics instances.
 *
 * @todo Convert this to use DraggableListBuilder
 */
class AnalyticsServiceListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'analytics_service_list';
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = [
      'data' => $this->t('Label'),
    ];
    $header['service'] = [
      'data' => $this->t('Service'),
    ];
    $header['status'] = [
      'data' => $this->t('Status'),
    ];
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\analytics\Entity\AnalyticsServiceInterface $entity */
    $row['label'] = $entity->label();
    $row['service'] = $entity->getService()->getLabel();
    // @todo Do we need to actually show if overwritten? Do any other list builders do this?
    /** @var \Drupal\analytics\Entity\AnalyticsServiceInterface $overridden_entity */
    $overridden_entity = $this->storage->load($entity->id());
    $row['status'] = $overridden_entity->status() ? $this->t('Enabled') : $this->t('Disabled');
    if ($overridden_entity->status() != $entity->status()) {
      $row['status'] .= ' (overwritten)';
    }

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultOperations(EntityInterface $entity) {
    /** @var \Drupal\analytics\Entity\AnalyticsServiceInterface $entity */
    $operations = parent::getDefaultOperations($entity);
    if (!$entity->get('status') && $entity->hasLinkTemplate('enable')) {
      $operations['enable'] = [
        'title' => t('Enable'),
        'weight' => 40,
        'url' => $entity->toUrl('enable'),
      ];
    }
    elseif ($entity->hasLinkTemplate('disable')) {
      $operations['disable'] = [
        'title' => t('Disable'),
        'weight' => 50,
        'url' => $entity->toUrl('disable'),
      ];
    }
    return $operations;
  }

}
