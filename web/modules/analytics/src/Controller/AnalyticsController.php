<?php

namespace Drupal\analytics\Controller;

use Drupal\analytics\Entity\AnalyticsServiceInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns responses for Views UI routes.
 */
class AnalyticsController extends ControllerBase {

  /**
   * Calls a method on a view and reloads the listing page.
   *
   * @param \Drupal\analytics\Entity\AnalyticsServiceInterface $analytics_service
   *   The analytics service being acted on.
   * @param string $op
   *   The operation to perform, e.g., 'enable' or 'disable'.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse|\Symfony\Component\HttpFoundation\RedirectResponse
   *   Either returns a rebuilt listing page as an AJAX response, or redirects
   *   back to the listing page.
   */
  public function ajaxOperation(AnalyticsServiceInterface $analytics_service, $op, Request $request) {
    // Perform the operation.
    $analytics_service->$op()->save();

    // Display a message and log the action.
    if ($op === 'enable') {
      $this->messenger()->addMessage($this->t('Enabled analytics service @label.', ['@label' => $analytics_service->label()]));
      $this->getLogger('analytics')->info('Enabled analytics service @label.', ['@label' => $analytics_service->label()]);
    }
    elseif ($op === 'disable') {
      $this->messenger()->addMessage($this->t('Disabled analytics service @label.', ['@label' => $analytics_service->label()]));
      $this->getLogger('analytics')->info('Disabled analytics service @label.', ['@label' => $analytics_service->label()]);
    }

    // If the request is via AJAX, return the rendered list as JSON.
    if ($request->request->get('js')) {
      $list = $this->entityTypeManager()->getListBuilder('analytics_service')->render();
      $response = new AjaxResponse();
      $response->addCommand(new ReplaceCommand('#analytics-entity-list', $list));
      return $response;
    }

    // Otherwise, redirect back to the page.
    return $this->redirect('entity.analytics_service.collection');
  }

}
