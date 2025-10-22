
(function (Drupal, once, window) {
  Drupal.behaviors.caminofrances_town_hall_time = {
    attach(context) {
      once('caminofrances-town-hall-time', '.caminofrances-town-hall-context-time', context).forEach(currentTime);
    }
  };

  /**
   * Current time.
   */
  function currentTime(timeElement) {
    let realTimeElement = timeElement.querySelector('.real-time');
    updateTime(realTimeElement);

    function updateTime(wrapper) {
      let today = new Date();
      let hours = today.getHours();
      let minutes = today.getMinutes();
      let seconds = today.getSeconds();
      wrapper.innerHTML = ('0'  + hours).slice(-2) + ":" + ('0'  + minutes).slice(-2);
    }
    setInterval(function() {
      updateTime(realTimeElement);
    }, 1000);
  }

}(Drupal, once, window));
