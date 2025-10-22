<?php

namespace Drupal\caminofrances_dashboard\Controller;

use Drupal\user\Controller\UserController;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Dashboard controller.
 */
class DashboardController extends UserController {

  const BYPASS_REDIRECTIONS_ACCESS = 'administer users';

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    return $instance;
  }

  /**
   * Main page redirect.
   *
   * @param \Drupal\user\UserInterface|null $user
   *   User from argument.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
   *   Redirect response or array.
   */
  public function mainPageRedirect(?UserInterface $user = NULL) {
    if ($this->currentUser()->hasPermission(self::BYPASS_REDIRECTIONS_ACCESS) && $user instanceof UserInterface) {
      $response = $this->entityTypeManager()->getViewBuilder('user')->view($user, 'full');
    }
    else {
      $response = $this->redirect($this->currentUser()->isAuthenticated() ? 'caminofrances_dashboard.main' : 'user.login');
    }
    return $response;
  }

  /**
   * Main page.
   *
   * @param \Drupal\user\UserInterface|null $user
   *   User.
   *
   * @return array
   *   Page.
   */
  public function mainPage(?UserInterface $user = NULL) {
    $page_user = $user instanceof UserInterface ? $user : $this->getCurrentUser();
    return $this->entityTypeManager()->getViewBuilder('user')->view($page_user, 'full');
  }

  /**
   * Get current user.
   *
   * @return \Drupal\user\UserInterface
   *   User.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *   Exception.
   */
  protected function getCurrentUser() {
    $user = $this->userStorage->load($this->currentUser()->id());
    if (!($user instanceof UserInterface)) {
      throw new AccessDeniedHttpException();
    }
    return $user;
  }

}
