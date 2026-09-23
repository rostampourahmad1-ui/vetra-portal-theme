(function () {
  'use strict';

  var body = document.body;
  var sidebar = document.getElementById('vetra-sidebar');
  var menuOpen = document.querySelector('.js-vetra-menu-toggle');
  var menuClose = document.querySelector('.js-vetra-menu-close');

  function setMenu(open) {
    if (!sidebar || !menuOpen) return;
    body.classList.toggle('vetra-menu-is-open', open);
    menuOpen.setAttribute('aria-expanded', open ? 'true' : 'false');
  }

  if (menuOpen) menuOpen.addEventListener('click', function () { setMenu(true); });
  if (menuClose) menuClose.addEventListener('click', function () { setMenu(false); });
  document.addEventListener('click', function (event) {
    if (body.classList.contains('vetra-menu-is-open') && sidebar && !sidebar.contains(event.target) && !menuOpen.contains(event.target)) setMenu(false);
  });

  var input = document.querySelector('.js-vetra-form-search');
  var cards = Array.prototype.slice.call(document.querySelectorAll('.vetra-form-card'));
  var filters = Array.prototype.slice.call(document.querySelectorAll('.js-vetra-filter'));
  var empty = document.querySelector('.js-vetra-no-results');
  var activeFilter = 'all';

  function normalize(value) {
    return String(value || '').toLocaleLowerCase('fa-IR').replace(/[يى]/g, 'ی').replace(/ك/g, 'ک').replace(/\s+/g, ' ').trim();
  }

  function renderForms() {
    var query = normalize(input ? input.value : '');
    var visible = 0;
    cards.forEach(function (card) {
      var categories = (card.dataset.categories || '').split(' ');
      var haystack = normalize(card.dataset.search || card.textContent);
      var matchesFilter = activeFilter === 'all' || categories.indexOf(activeFilter) !== -1;
      var matchesSearch = !query || haystack.indexOf(query) !== -1;
      var show = matchesFilter && matchesSearch;
      card.hidden = !show;
      if (show) visible += 1;
    });
    if (empty) empty.hidden = visible !== 0;
  }

  filters.forEach(function (filter) {
    filter.addEventListener('click', function () {
      activeFilter = filter.dataset.filter || 'all';
      filters.forEach(function (item) { item.classList.toggle('is-active', item === filter); item.setAttribute('aria-pressed', item === filter ? 'true' : 'false'); });
      renderForms();
    });
  });
  if (input) input.addEventListener('input', renderForms);
  renderForms();
}());
