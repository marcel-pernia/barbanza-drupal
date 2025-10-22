(function (Drupal, once, window) {
  Drupal.behaviors.town_hall_content_top_kioskh = {
    attach(context) {
      once('town-hall-content-top-kioskh', '.town-hall-content-top-kioskh', context).forEach(initCarousel);
    }
  };

  /**
   * Init background carousel.
   */
  function initCarousel(initElement) {
    if (typeof window.Swiper !== 'function') {
      return;
    }
    var itemsSlideElement = initElement.querySelector('.town-hall-content-top-kioskh .swiper');
    var slides = itemsSlideElement.querySelectorAll('.swiper-slide').length;
    if (slides > 1) {
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
  }
}(Drupal, once, window));
