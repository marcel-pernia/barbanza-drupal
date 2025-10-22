<?php

namespace Drupal\analytics\Form;

use Drupal\analytics\AnalyticsServiceManager;
use Drupal\analytics\Entity\AnalyticsService;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\SubformState;
use Drupal\Core\Render\Element;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AnalyticsServiceForm extends EntityForm {

  /**
   * The entity being used by this form.
   *
   * @var \Drupal\analytics\Entity\AnalyticsServiceInterface
   */
  protected $entity;

  /**
   * The analyics service plugin manager.
   *
   * @var \Drupal\analytics\AnalyticsServiceManager
   */
  protected $analyticsServiceManager;

  /**
   * Constructs on AnalyticsServiceForm object.
   *
   * @param \Drupal\analytics\AnalyticsServiceManager $analytics_service_manager
   *   The analytics service.
   */
  public function __construct(AnalyticsServiceManager $analytics_service_manager) {
    $this->analyticsServiceManager = $analytics_service_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.analytics.service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    // Plugin is not set when the entity is initially created.
    $plugin = $this->entity->get('service') ? $this->entity->getService() : NULL;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('The label for this service.'),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#disabled' => !$this->entity->isNew(),
      '#maxlength' => 64,
      '#machine_name' => [
        'exists' => [AnalyticsService::class, 'load'],
      ],
    ];

    $form['service'] = [
      '#type' => 'select',
      '#title' => $this->t('Service'),
      '#options' => $this->analyticsServiceManager->getDefinitionOptions(),
      '#default_value' => $plugin ? $plugin->getPluginId() : NULL,
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::updateServiceSettings',
        'wrapper' => 'analytics-service-configuration-wrapper',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    $form['service_configuration'] = [
      '#type' => 'container',
      '#tree' => TRUE,
      '#prefix' => '<div id="analytics-service-configuration-wrapper">',
      '#suffix' => '</div>',
    ];

    if ($plugin) {
      $form['service_configuration'] = $plugin->buildConfigurationForm($form['service_configuration'], $this->getPluginSubFormState($form, $form_state));
    }

    return $form;
  }

  /**
   * Gets subform state for the plugin configuration subform.
   *
   * @param array $form
   *   Full form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Parent form state.
   *
   * @return \Drupal\Core\Form\SubformStateInterface
   *   Sub-form state for the media source configuration form.
   */
  protected function getPluginSubFormState(array $form, FormStateInterface $form_state) {
    return SubformState::createForSubform($form['service_configuration'], $form, $form_state)
      ->set('operation', $this->operation)
      ->set('type', $this->entity);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    // Let the selected plugin validate its settings.
    if (Element::children($form['service_configuration'])) {
      $this->entity
        ->getService()
        ->validateConfigurationForm($form['service_configuration'], $this
          ->getPluginSubFormState($form, $form_state));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    if (Element::children($form['service_configuration'])) {
      $this->entity
        ->getService()
        ->submitConfigurationForm($form['service_configuration'], $this
          ->getPluginSubFormState($form, $form_state));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = $this->entity->save();

    $t_args = ['%label' => $this->entity->label()];

    if ($status == SAVED_UPDATED) {
      $this->messenger()->addMessage($this->t('The analytics service %label has been updated.', $t_args));
    }
    elseif ($status == SAVED_NEW) {
      $this->messenger()->addMessage($this->t('The analytics service %label has been added.', $t_args));
      $context = array_merge($t_args, ['link' => $this->entity->toLink($this->t('View'), 'edit-form')->toString()]);
      $this->logger('analytics')->notice('Added analytics service %label.', $context);
    }

    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
  }

  /**
   * Ajax callback to update the form fields which depend on analytics service.
   */
  public function updateServiceSettings(array $form, FormStateInterface $form_state) {
    return $form['service_configuration'];
  }

}
