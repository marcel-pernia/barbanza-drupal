/**
 * @file
 * Defines Javascript behaviors for the cookies module.
 */;

(function (Drupal, drupalSettings) {
  'use strict';

  Drupal.cookiesGtagConsentModeExecuted = false;

  /**
   * Define defaults.
   */
  Drupal.behaviors.cookiesGtagConsentMode = {
    consentGiven: function () {
      // @see https://developers.google.com/tag-platform/security/guides/consent
      // @see google_tag/js/gtag.js
      gtag('consent', 'update', {
        'ad_storage': 'granted',
        'analytics_storage': 'granted',
        'ad_user_data': 'granted',
        'ad_personalization': 'granted'
      });
      Drupal.cookiesGtagConsentModeExecuted = true;
    },
    attach: function (context) {
      var self = this;
      document.addEventListener('cookiesjsrUserConsent', function (event) {
        var service = (typeof event.detail.services === 'object') ? event.detail.services : {};
        if (typeof service.gtag !== 'undefined' && service.gtag && typeof drupalSettings.gtag !== 'undefined' && drupalSettings.gtag.consentMode === true && Drupal.cookiesGtagConsentModeExecuted === false) {
          self.consentGiven(context);
        }
      });
    }
  };
})(Drupal, drupalSettings);
