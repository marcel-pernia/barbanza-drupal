(function (Drupal, once, window) {
  Drupal.behaviors.caminofrances_town_hall_menu = {
    attach(context) {
      once('caminofrances-town-hall-menu', 'div.block-system-menu-blocktown-hall-menu ul li a[href^="#"]', context).forEach(ensureAnchorTarget);
    }
  };

  /**
   * List link ensure anchor target or remove.
   */
  function ensureAnchorTarget(listLink) {
    const listLinkTarget = listLink.getAttribute('href');
    if (listLinkTarget !== "#" && window.document.querySelector(listLinkTarget) === null) {
      listLink.parentNode.remove();
      listLink.remove();
    }
  }

}(Drupal, once, window));
