(function (Drupal, once) {
  'use strict';
  Drupal.behaviors.caminofrances = {
    attach: function (context, settings) {
      once('caminofrances_hierarchical_select', '.form-type-cshs', context).forEach(function (element, index) {
        let selectWrapper;
        setTimeout(() => {
          selectWrapper = element.querySelector('.select-wrapper');
          selectWrapper.classList.add('form-floating');
        }, "1");
      });
    }
  };
})(Drupal, once);
