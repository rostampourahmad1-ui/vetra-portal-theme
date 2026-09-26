(function () {
  'use strict';
  var html = document.documentElement;
  var mode = html.dataset.themeMode || 'system';
  var toggle = document.querySelector('.js-vetra-mode-toggle');
  var mobileToggle = document.querySelector('.js-vetra-mobile-toggle');
  var navigation = document.querySelector('#site-navigation');
  var nav = navigation ? navigation.querySelector('.vetra-site-nav') : null;
  var searchToggle = document.querySelector('.js-vetra-search-toggle');
  var searchOverlay = document.querySelector('#vetra-search-overlay');
  var searchClose = document.querySelector('.js-vetra-search-close');
  var lastTrigger = null;
  function readStorage(key) { try { return localStorage.getItem(key); } catch (e) { return null; } }
  function writeStorage(key, value) { try { localStorage.setItem(key, value); } catch (e) {} }
  function systemTheme() { return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'; }
  function setTheme(value, persist) { html.dataset.theme = value === 'system' ? systemTheme() : value; if (persist) writeStorage('vetra-theme', value); }
  var stored = readStorage('vetra-theme');
  if (stored === 'light' || stored === 'dark') mode = stored;
  setTheme(mode, false);
  if (toggle) toggle.addEventListener('click', function () { setTheme(html.dataset.theme === 'dark' ? 'light' : 'dark', true); });
  if (window.matchMedia) window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function () { if (!readStorage('vetra-theme') && mode === 'system') setTheme('system', false); });
  function closeMenu() { if (!nav || !nav.classList.contains('is-open')) return; nav.classList.remove('is-open'); mobileToggle.setAttribute('aria-expanded', 'false'); if (lastTrigger) lastTrigger.focus(); else mobileToggle.focus(); }
  function openMenu() { if (!nav) return; lastTrigger = document.activeElement; nav.classList.add('is-open'); mobileToggle.setAttribute('aria-expanded', 'true'); var first = nav.querySelector('a,button'); if (first) first.focus(); }
  if (mobileToggle && nav) mobileToggle.addEventListener('click', function () { nav.classList.contains('is-open') ? closeMenu() : openMenu(); });
  function closeSearch() { if (!searchOverlay || searchOverlay.hidden) return; searchOverlay.hidden = true; searchToggle.setAttribute('aria-expanded', 'false'); if (lastTrigger) lastTrigger.focus(); else searchToggle.focus(); }
  function openSearch() { if (!searchOverlay) return; lastTrigger = document.activeElement; searchOverlay.hidden = false; searchToggle.setAttribute('aria-expanded', 'true'); var input = searchOverlay.querySelector('.search-field'); if (input) input.focus(); }
  if (searchToggle) searchToggle.addEventListener('click', openSearch);
  if (searchClose) searchClose.addEventListener('click', closeSearch);
  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') { closeMenu(); closeSearch(); }
    if (event.key === 'Tab' && nav && nav.classList.contains('is-open')) { var focusable = nav.querySelectorAll('a,button,input,select,textarea,[tabindex]:not([tabindex="-1"])'); if (focusable.length && event.shiftKey && document.activeElement === focusable[0]) { event.preventDefault(); focusable[focusable.length - 1].focus(); } else if (focusable.length && !event.shiftKey && document.activeElement === focusable[focusable.length - 1]) { event.preventDefault(); focusable[0].focus(); } }
  });
  if (nav) nav.addEventListener('click', function (event) { if (event.target.closest('a')) closeMenu(); });
  var topbar = document.querySelector('.vetra-topbar');
  var backTop = document.querySelector('.js-vetra-back-to-top');
  window.addEventListener('scroll', function () { if (topbar) topbar.classList.toggle('is-scrolled', window.scrollY > 10); if (backTop) backTop.hidden = window.scrollY < 500; }, { passive: true });
  if (backTop) backTop.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' }); });
  var reveal = document.querySelectorAll('.vetra-reveal');
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) reveal.forEach(function (item) { item.classList.add('is-visible'); });
  else if ('IntersectionObserver' in window) { var observer = new IntersectionObserver(function (entries) { entries.forEach(function (entry) { if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); } }); }, { threshold: .12 }); reveal.forEach(function (item) { observer.observe(item); }); }
  else reveal.forEach(function (item) { item.classList.add('is-visible'); });
  var currentPath = window.location.pathname;
  document.querySelectorAll('.vetra-bottom-bar a').forEach(function (link) { try { if (new URL(link.href, window.location.origin).pathname === currentPath) link.classList.add('is-active'); } catch (e) {} });
  if (window.vetraPortal && vetraPortal.pwaEnabled && 'serviceWorker' in navigator) window.addEventListener('load', function () { navigator.serviceWorker.register(vetraPortal.swUrl).catch(function () {}); });
  else if (window.vetraPortal && !vetraPortal.pwaEnabled && 'serviceWorker' in navigator) navigator.serviceWorker.getRegistrations().then(function (registrations) { registrations.forEach(function (registration) { var worker = registration.active || registration.waiting || registration.installing; if (worker && new URL(worker.scriptURL).pathname.endsWith('/vetra-sw')) registration.unregister(); }); }).catch(function () {});
  var cookieBanner = document.querySelector('.vetra-cookie-banner');
  if (cookieBanner) { if (readStorage('vetra-cookie-accepted') === '1') cookieBanner.hidden = true; var dismiss = cookieBanner.querySelector('.js-vetra-cookie-dismiss'); if (dismiss) dismiss.addEventListener('click', function () { writeStorage('vetra-cookie-accepted', '1'); cookieBanner.hidden = true; }); }
  var installBanner = document.querySelector('.vetra-install-banner');
  if (installBanner && window.vetraPortal && vetraPortal.pwaEnabled && vetraPortal.installPrompt) { var installEvent = null; window.addEventListener('beforeinstallprompt', function (event) { event.preventDefault(); installEvent = event; if (readStorage('vetra-install-dismissed') !== '1') window.setTimeout(function () { installBanner.hidden = false; }, Math.max(0, Number(vetraPortal.installDelay) || 0) * 1000); }); var installButton = installBanner.querySelector('.js-vetra-install'); if (installButton) installButton.addEventListener('click', function () { if (!installEvent) return; installEvent.prompt(); installEvent.userChoice.finally(function () { installEvent = null; installBanner.hidden = true; }); }); var installDismiss = installBanner.querySelector('.js-vetra-install-dismiss'); if (installDismiss) installDismiss.addEventListener('click', function () { writeStorage('vetra-install-dismissed', '1'); installBanner.hidden = true; }); }
}());
