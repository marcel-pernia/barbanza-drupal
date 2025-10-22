(function (Drupal, once, window) {
  Drupal.behaviors.practical_information_content_top_kioskh = {
    attach(context) {
      //once('practical-information-content-top-kioskh', '.practical-information-content-top-kioskh', context).forEach(initCarousel);
    }
  };

  /**
   * Init carousel.
   */
  /*
  function initCarousel(initElement) {
    if (typeof window.Swiper !== 'function') {
      return;
    }
    var itemsSlideElement = initElement.querySelector('.practical-information-content-top-kioskh .swiper');
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
  }*/
}(Drupal, once, window));
