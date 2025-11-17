(function (Drupal, once) {
    Drupal.behaviors.mostrarSecciones = {
      attach: function (context, settings) {
        const infoConcelloSlider = document.getElementById('info-concello-slider');
        const infoConcello = document.getElementById('info-concello');
        const secciones = document.querySelectorAll('.item-list.my-5');
  
        once('mostrarSecciones', 'a[href^="#"]', context).forEach(function (enlace) {
          enlace.addEventListener('click', function (e) {
            e.preventDefault();
  
            const targetId = this.getAttribute('href').replace('#', '');
  
            // Ocultar todas las secciones con transiciones suaves
            secciones.forEach(function (seccion) {
              seccion.style.transition = 'height 0.5s ease, opacity 0.5s ease';
              seccion.style.overflow = 'hidden';
              seccion.style.opacity = '0';
              seccion.style.height = '0';
            });
  
            // Ocultar #info-concello
            if (infoConcello) {
              infoConcello.style.transition = 'height 0.5s ease, opacity 0.5s ease';
              infoConcello.style.overflow = 'hidden';
              infoConcello.style.opacity = '0';
              infoConcello.style.height = '0';
            }
  
            // Lógica para #main-content
            if (targetId === 'main-content') {
              if (infoConcello) {
                infoConcello.style.display = 'block'; // Por si estaba oculto
                setTimeout(() => {
                  infoConcello.style.height = 'auto'; // Para que calcule la altura real
                  const autoHeight = infoConcello.offsetHeight + 'px';
                  infoConcello.style.height = '0';
                  setTimeout(() => {
                    infoConcello.style.opacity = '1';
                    infoConcello.style.height = autoHeight;
                  }, 10);
                }, 10);
              }
  
              // Restablecer el height del slider a 630px
              if (infoConcelloSlider) {
                infoConcelloSlider.style.transition = 'height 0.5s ease';
                infoConcelloSlider.style.height = '630px';
              }
            } else {
              // Mostrar la sección correspondiente
              secciones.forEach(function (seccion) {
                const h3 = seccion.querySelector(`h3#${targetId}`);
                if (h3) {
                  seccion.style.display = 'block'; // Por si estaba oculto
                  setTimeout(() => {
                    seccion.style.height = 'auto'; // Para que calcule la altura real
                    const autoHeight = seccion.offsetHeight + 'px';
                    seccion.style.height = '0';
                    setTimeout(() => {
                      seccion.style.opacity = '1';
                      seccion.style.height = autoHeight;
                    }, 10);
                  }, 10);
                }
              });
  
              // Cambiar el height del slider a 300px
              if (infoConcelloSlider) {
                infoConcelloSlider.style.transition = 'height 0.5s ease';
                infoConcelloSlider.style.height = '300px';
              }
            }
          });
        });
      }
    };
  })(Drupal, once);
  


  
  (function (Drupal, once) {
    Drupal.behaviors.backLinkBehavior = {
      attach: function (context, settings) {
        const backLinks = once('backLinkBehavior', 'a[href="#atras"]', context);
  
        backLinks.forEach(link => {
          link.addEventListener('click', function (event) {
            event.preventDefault();
            window.history.back();
          });
        });
      },
    };
  })(Drupal, once);


  (function ($, Drupal) {
    Drupal.behaviors.portadaSlider = {
      attach: function (context, settings) {
        once('slickInit', '.block-views-blockportada-slider-block-1 .view-content', context).forEach(function(element) {
          $(element).slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: true,
            autoplay: false,
            autoplaySpeed: 4000,
            centerMode: false,
            focusOnSelect: true,
            infinite: false
          });
        });
      }
    };
  })(jQuery, Drupal);


  (function ($, Drupal) {
    Drupal.behaviors.sliderrecursos = {
      attach: function (context, settings) {
        once('slickInit', '.galeria-portada-barbanza', context).forEach(function(element) {
          $(element).slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: true,
            autoplay: true,
            autoplaySpeed: 4000,
            centerMode: false,
            focusOnSelect: true,
            infinite: false
          });
        });
      }
    };
  })(jQuery, Drupal);

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