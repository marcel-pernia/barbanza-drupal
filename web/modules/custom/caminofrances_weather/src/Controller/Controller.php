<?php

namespace Drupal\caminofrances_weather\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\openweather\WeatherService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns responses for Caminofrances Weather routes.
 */
final class Controller extends ControllerBase {

  /**
   * The controller constructor.
   */
  public function __construct(
    private readonly WeatherService $openweatherWeatherService,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('openweather.weather_service'),
    );
  }

  /**
   * Builds the response.
   */
  public function fetchOpenWeatherData(Request $request): JsonResponse {
    $lat = $request->query->get('lat') ?? '';
    $long = $request->query->get('long') ?? ''; 
    if (!empty($lat) && !empty($long)) {
      $weather_info = $this->openweatherWeatherService->getWeatherInformation([
        // Use "current_details" for inmediate current weather.
        'display_type' => 'forecast_daily',
        'input_options' => 'geo_coord',
        'input_value' => implode(',', [$lat, $long]),
        // Request weather for just one day, equal as count due service reqs.
        'count' => '1',
        'cnt' => '1',
      ]);
    }
    else {
      $weather_info = FALSE;
    }

    if ($weather_info !== FALSE) {
      $response = new JsonResponse((string) $weather_info, 200, [], TRUE);
    }
    else {
      $response = new JsonResponse(['error' => 'Service unavailable'], 200);
    }
    return $response;
  }

}
