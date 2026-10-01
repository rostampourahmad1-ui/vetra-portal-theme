(function () {
  'use strict';
  document.addEventListener('click', function (event) {
    var link = event.target.closest('[data-vetra-gallery-link]');
    if (!link) return;
    if (event.defaultPrevented || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    var gallery = link.closest('[data-vetra-gallery]');
    if (!gallery) return;
    gallery.querySelectorAll('[data-vetra-gallery-link]').forEach(function (item) {
      item.removeAttribute('aria-current');
    });
    link.setAttribute('aria-current', 'true');
  });
}());
