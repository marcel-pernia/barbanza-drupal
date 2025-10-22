(function (Drupal, drupalSettings, once, window) {
  Drupal.behaviors.kiosk_nav_query = {
    attach(context) {
      // Look up for kiosk mode based into query param.
      const kiosk_nav = drupalSettings.kiosk_nav || null;
      Drupal.kioskNavMode = kiosk_nav !== null ? queryParamsGetMode(kiosk_nav) : null;
      if (Drupal.kioskNavMode !== null) {
        once('kiosk-nav-query', 'a[href]', context).forEach(processLink);
      }
    }
  };

  /**
   * Query params get mode.
   */
  function queryParamsGetMode(paramsToCheck) {
    return paramsToCheck.reduce(function (mode, currentParam) {
      return mode === null && new RegExp('[\?&]' + currentParam + '=*([^&#]*)').exec(window.location.href) !== null ? currentParam : mode;
    }, null);
  };

  /**
   * Process links.
   *
   * Ensure all internal links has query kiosk mode param or "disable".
   */
  function processLink(linkElement) {
    const host = window.location.host;
    const href = linkElement.href;
    const internal = href.indexOf(host) > -1;
    if (internal) {
      let kiosk_href_url = new URL(href);
      if (href.indexOf(Drupal.kioskNavMode) === -1) {
        kiosk_href_url.searchParams.append(Drupal.kioskNavMode, '');
        linkElement.href = kiosk_href_url.toString();
      }
      linkElement.classList.add('kiosk-nav-link');
      linkElement.classList.add('kiosk-nav-link--internal');
    }
    else {
      linkElement.classList.add('kiosk-nav-link');
      linkElement.classList.add('kiosk-nav-link--external');
    }
  }

}(Drupal, window.drupalSettings, once, window));
