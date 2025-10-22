/**
 * @file
 * Registers the Drupal.settings.dnt variable with the browser's setting.
 */

(function ($, drupalSettings) {

  'use strict';

  if (typeof drupalSettings.dnt === 'undefined') {
    const dnt = navigator.doNotTrack || navigator.msDoNotTrack;
    $.extend(drupalSettings, {'dnt': (dnt === 'yes' || dnt === '1')});
  }

})(jQuery, window.drupalSettings);
