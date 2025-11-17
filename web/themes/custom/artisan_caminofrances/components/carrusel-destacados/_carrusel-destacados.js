(function ($, Drupal) {
  Drupal.behaviors.carruselDestacados = {
    attach: function (context, settings) {
      once('slickInit', '.carrusel-destacados', context).forEach(function(element) {
        $(element).slick({
          slidesToShow: 3,
          slidesToScroll: 1,
          dots: false,
          arrows: true,
          prevArrow: '<button class="slick-prev" aria-label="Anterior" type="button"><span class="sr-only">Anterior</span></button>',
          nextArrow: '<button class="slick-next" aria-label="Siguiente" type="button"><span class="sr-only">Siguiente</span></button>',
          autoplay: false,
          centerMode: false,
          infinite: true,
          responsive: [
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
          ]
        });
      });
    }
  };
})(jQuery, Drupal);

