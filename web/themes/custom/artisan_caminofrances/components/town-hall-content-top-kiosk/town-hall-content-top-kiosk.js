(function (Drupal, once, window) {
  Drupal.behaviors.practical_information_content_top_kioskv = {
    attach(context) {
      once('practical-information-content-top-kioskv', '.practical-information-content-top-kioskv', context).forEach(initBackgroundCarousel);
    }
  };

  /**
   * Init background carousel.
   */
  function initBackgroundCarousel(initElement) {
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

    let video = initElement.querySelector('video');
    let playPauseVideo = initElement.querySelector('.swiper-button-play');
    if (playPauseVideo != null) {

      playPauseVideo.addEventListener('click', function () {
        if (video.paused) {
          video.play();
          this.classList.add('video-playing');
          this.classList.remove('video-paused');
        }
        else {
          video.pause();
          this.classList.add('video-paused');
          this.classList.remove('video-playing');
        }
      });
    }
  }
}(Drupal, once, window));
