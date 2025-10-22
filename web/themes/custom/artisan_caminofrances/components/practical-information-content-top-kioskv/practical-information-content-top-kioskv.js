(function (Drupal, once, window) {
  Drupal.behaviors.practical_information_content_top_kioskv = {
    attach(context) {
      once('practical-information-content-top-kioskv', '.practical-information-content-top-kioskv', context).forEach(initCarousel);
    }
  };

  /**
   * Init background carousel.
   */
  function initCarousel(initElement) {
    if (typeof window.Swiper !== 'function') {
      return;
    }
    var itemsSlideElement = initElement.querySelector('.practical-information-content-top-kioskv .swiper');
    new window.Swiper(itemsSlideElement, {
      slidesPerView: 1,
      loop: true,
      centeredSlides: true,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  }
}(Drupal, once, window));
