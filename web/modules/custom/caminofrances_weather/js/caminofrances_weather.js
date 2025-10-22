
(function (Drupal, drupalSettings, once, window) {
  Drupal.behaviors.caminofrances_town_hall_weather = {
    attach(context) {
      once('caminofrances-town-hall-weather', '.town-hall-weather-block', context).forEach(currentWeather);
    }
  };

  /**
   * Current time.
   */
  function currentWeather(element) {
    let latitude = element.getAttribute('data-lat');
    let longitude = element.getAttribute('data-long');

    let minMaxTempWrapper = document.createElement('div');
    let minMaxTempInnerWrapper = document.createElement('div');
    let minTempWrapper = document.createElement('div');
    let maxTempWrapper = document.createElement('div');
    let image = document.createElement('img');


    let humidityWrapper = document.createElement('div');
    humidityWrapper.classList.add('humidity-wrapper');

    let humidityLabel = document.createElement('div');
    humidityLabel.classList.add('label', 'humidity-label');
    humidityLabel.textContent = Drupal.t('Humidity');
    let humidityValue = document.createElement('div');
    humidityValue.classList.add('humidity-value');

    minMaxTempWrapper.classList.add('min-max-temperature-wrapper');
    minMaxTempInnerWrapper.classList.add('min-max-temperature-inner-wrapper');
    minTempWrapper.classList.add('min-temperature');
    maxTempWrapper.classList.add('max-temperature');
    minMaxTempInnerWrapper.appendChild(image);
    minMaxTempInnerWrapper.appendChild(minTempWrapper);
    minMaxTempInnerWrapper.appendChild(maxTempWrapper);
    minMaxTempWrapper.appendChild(minMaxTempInnerWrapper);

    humidityWrapper.appendChild(humidityValue);
    humidityWrapper.appendChild(humidityLabel);

    element.appendChild(humidityWrapper);
    element.appendChild(minMaxTempWrapper);

    weatherRequest(element, latitude, longitude);
    setInterval(() => (weatherRequest(element, latitude, longitude)), 5 * 60 * 1000);
  }

  function weatherRequest(weatherElement, latitude, longitude) {
    const endpoint = Drupal.url(`weather-api?lat=${latitude}&long=${longitude}`);
    console.log(endpoint);

    drupalSettings.ajaxTrustedUrl[endpoint] = true;
    Drupal.ajax({
      url: endpoint,
      httpMethod: 'GET',
      progress: false,
      success: function (data) {
        if (data.error) {
          console.error('Weather widget unable to refresh: ' + data.error);
          weatherElement.parentNode.classList.add('block--unavailable');
          return;
        }
        // weatherElement.classList.remove('unavailable');
        weatherElement.parentNode.classList.remove('block--unavailable');
        let image = weatherElement.querySelector('img');
        let minTempWrapper = weatherElement.querySelector('.min-temperature');
        let maxTempWrapper = weatherElement.querySelector('.max-temperature');
        let humidityValue = weatherElement.querySelector('.humidity-value');
        let image_href = "https://openweathermap.org/img/wn/" + data.list[0].weather[0].icon + "@2x.png";
        console.log(image_href);
        image.src = image_href;
        image.alt = data.list[0].weather[0].description;
        image.width = 70;
        image.height = 70;
        minTempWrapper.textContent = Math.round(data.list[0].temp.min - 273.15) + '°C / ';
        maxTempWrapper.textContent = Math.round(data.list[0].temp.max - 273.15) + '°C';
        humidityValue.textContent = data.list[0].humidity + '%';
      }
    }).execute();
  }

}(Drupal, drupalSettings, once, window));
