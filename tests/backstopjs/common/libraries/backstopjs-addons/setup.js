module.exports = async (page, scenario, config) => {
  // Stop animations.
  if (scenario.stopAnimationsSelectors) {
    await page.waitForSelector('head');
    await page.evaluate(async(scenario) => {
      const head = document.head || document.getElementsByTagName('head')[0];
      const style = document.createElement('style');
      const scenarioSelector = scenario.stopAnimationsSelectors ? scenario.stopAnimationsSelectors : '';
      const css = scenarioSelector + '{animation: none !important; transition-duration: 0ms !important; transition: none !important; visibility: visible !important;}';

      head.appendChild(style);
      style.setAttribute('type', 'text/css');
      style.appendChild(document.createTextNode(css));
    }, scenario);
  }

  if (scenario.disableFiltersSelector) {
    await page.waitForSelector('head');
    await page.evaluate(async(scenario) => {
      const head = document.head || document.getElementsByTagName('head')[0];
      const style = document.createElement('style');
      const scenarioSelector = scenario.disableFiltersSelector ? scenario.disableFiltersSelector : '';
      const css = scenarioSelector + '{filter: none !important;}';

      head.appendChild(style);
      style.setAttribute('type', 'text/css');
      style.appendChild(document.createTextNode(css));
    }, scenario);
  }

  await page.evaluate(async(config) => {
    // Avoid lazy css load.
    document.querySelectorAll('link[rel="stylesheet"][data-onload-media][onload]').forEach((stylelink) => {
      stylelink.onload = null;
      stylelink.media = stylelink.dataset.onloadMedia;
    });

    // Force blazy to load all images when available.
    if (typeof Drupal == 'object' && typeof Drupal.blazy == 'object') {
      Drupal.blazy.init.load(document.getElementsByClassName('b-lazy'), true);
    }
    // Force eager image loading.
    document.querySelectorAll('img').forEach((image) => {
      image.loading = 'eager';
    });
    // Avoid images decoding async.
    document.querySelectorAll('img[decoding=async]').forEach((image) => {
      image.decoding = 'sync';
    });

    // Avoid iframe lazy load.
    document.querySelectorAll('iframe[loading=lazy]').forEach((iframe) => {
      iframe.loading = 'eager';
    });

    // Slick refresh to avoid minimal changes.
    const slickCarousels = document.querySelectorAll('.slick-slider');
    slickCarousels.forEach((carousel) => {
      jQuery(carousel).slick('setPosition');

      // Avoid autoplay in carousels.
      if (jQuery(carousel).slick('slickGetOption', 'autoplay')) {
        jQuery(carousel).slick('slickSetOption', {
          'autoplay': false,
          'speed': 0,
          'infinite': false
        }, true);
        jQuery(carousel).slick('slickGoTo', 0, false);

        // Sometimes slick doesn't get the changes, for these cases the
        // carrousel is rebuild.
        if (jQuery(carousel).slick('slickCurrentSlide') > 0) {
          let options = jQuery(carousel).slick('getSlick').options;
          jQuery(carousel).slick('unslick').slick(options);
        }
      }
    });
    if (slickCarousels.length > 0) {
      const head = document.head || document.getElementsByTagName('head')[0];
      const style = document.createElement('style');
      const css = '.slick-list * {text-rendering: geometricPrecision;}';

      head.appendChild(style);
      style.setAttribute('type', 'text/css');
      style.appendChild(document.createTextNode(css));
    }

    // Avoid responsive font sizes.

    const heading_selectors = config.backstopjsAddons.responsiveFontSizeSelector ?? "h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6, .lead, .display-1, .display-2, .display-3, .display-4, .display-5, .display-6, .blockquote, .btn, .fs-1, .fs-2, .fs-3, .fs-4, .fs-6, .fs-6";
    document.querySelectorAll(heading_selectors).forEach((heading) => {
      let style = window.getComputedStyle(heading, null).getPropertyValue('font-size');
      let fontSize = parseFloat(style).toFixed(2);
      heading.style.fontSize = fontSize + "px";
    });
  }, config);

  await page.waitForFunction(() => {
    return document.fonts.ready.then(() => {
      return true;
    });
  });

  // Wait for assets to load.
  await page.waitForNetworkIdle();
}
