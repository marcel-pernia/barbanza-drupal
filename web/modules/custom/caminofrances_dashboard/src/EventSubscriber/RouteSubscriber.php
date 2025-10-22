<?php

namespace Drupal\caminofrances_dashboard\EventSubscriber;

use Drupal\Core\Routing\RouteBuildEvent;
use Drupal\Core\Routing\RoutingEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Provides route subscriber.
 */
class RouteSubscriber implements EventSubscriberInterface {

  /**
   * Alter routes.
   *
   * @param \Drupal\Core\Routing\RouteBuildEvent $event
   *   The event containing the route being built.
   */
  public function alterRoutes(RouteBuildEvent $event) {
    $user_page = $event->getRouteCollection()->get('user.page');
    if ($user_page) {
      $user_page->setDefault('_controller', '\Drupal\caminofrances_dashboard\Controller\DashboardController::mainPageRedirect');
    }
    $user_canonical = $event->getRouteCollection()->get('entity.user.canonical');
    if ($user_canonical) {
      $user_canonical->setDefault('_controller', '\Drupal\caminofrances_dashboard\Controller\DashboardController::mainPageRedirect');
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      RoutingEvents::ALTER => [['alterRoutes']],
    ];
  }

}
