/******/ (() => { // webpackBootstrap
/*!*******************************!*\
  !*** ./src/js/main.script.js ***!
  \*******************************/
(function (Drupal, once) {
  'use strict';

  Drupal.behaviors.caminofrances = {
    attach: function attach(context, settings) {
      once('caminofrances_hierarchical_select', '.form-type-cshs', context).forEach(function (element, index) {
        var selectWrapper;
        setTimeout(function () {
          selectWrapper = element.querySelector('.select-wrapper');
          selectWrapper.classList.add('form-floating');
        }, "1");
      });
    }
  };
})(Drupal, once);
/******/ })()
;
//# sourceMappingURL=main.script.js.map