(function () {
  'use strict';

  var html = document.documentElement;
  var mode = html.dataset.themeMode || 'system';
  var toggle = document.querySelector('.js-vetra-mode-toggle');
  var mobileToggle = document.querySelector('.js-vetra-mobile-toggle');
  var nav = document.querySelector('.vetra-site-nav');

  function readStorage(key) { try { return localStorage.getItem(key); } catch (e) { return null; } }
  function writeStorage(key, value) { try { localStorage.setItem(key, value); } catch (e) {} }

  function systemTheme() {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  function setTheme(value, persist) {
    var theme = value === 'system' ? systemTheme() : value;
    html.dataset.theme = theme;
    if (persist) writeStorage('vetra-theme', value);
  }

  var stored = readStorage('vetra-theme');
  if (stored === 'light' || stored === 'dark') mode = stored;
  setTheme(mode, false);
  if (toggle) toggle.addEventListener('click', function () { setTheme(html.dataset.theme === 'dark' ? 'light' : 'dark', true); });
  if (window.matchMedia) window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function () { if (!readStorage('vetra-theme') && mode === 'system') setTheme('system', false); });

  if (mobileToggle && nav) mobileToggle.addEventListener('click', function () {
    var open = nav.classList.toggle('is-open');
    mobileToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  var topbar = document.querySelector('.vetra-topbar');
  window.addEventListener('scroll', function () { if (topbar) topbar.classList.toggle('is-scrolled', window.scrollY > 10); }, { passive: true });

  var reveal = document.querySelectorAll('.vetra-reveal');
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) { entries.forEach(function (entry) { if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); } }); }, { threshold: .12 });
    reveal.forEach(function (item) { observer.observe(item); });
  } else reveal.forEach(function (item) { item.classList.add('is-visible'); });

  // Bottom bar active link.
  var currentPath = window.location.pathname;
  document.querySelectorAll('.vetra-bottom-bar a').forEach(function (link) {
    try {
      if (new URL(link.href, window.location.origin).pathname === currentPath) {
        link.classList.add('is-active');
      }
    } catch (e) {}
  });

  // PWA service worker registration.
  if (window.vetraPortal && vetraPortal.pwaEnabled && 'serviceWorker' in navigator) {
    window.addEventListener('load', function () {
      navigator.serviceWorker.register(vetraPortal.swUrl).catch(function () {});
    });
  } else if (window.vetraPortal && !vetraPortal.pwaEnabled && 'serviceWorker' in navigator) {
    navigator.serviceWorker.getRegistrations().then(function (registrations) {
      registrations.forEach(function (registration) {
        var worker = registration.active || registration.waiting || registration.installing;
        if (worker && new URL(worker.scriptURL).pathname.endsWith('/vetra-sw')) registration.unregister();
      });
    }).catch(function () {});
  }

  var cookieBanner = document.querySelector('.vetra-cookie-banner');
  if (cookieBanner) {
    if (readStorage('vetra-cookie-accepted') === '1') cookieBanner.hidden = true;
    var cookieDismiss = cookieBanner.querySelector('.js-vetra-cookie-dismiss');
    if (cookieDismiss) cookieDismiss.addEventListener('click', function () { writeStorage('vetra-cookie-accepted', '1'); cookieBanner.hidden = true; });
  }

  var installBanner = document.querySelector('.vetra-install-banner');
  if (installBanner && window.vetraPortal && vetraPortal.pwaEnabled && vetraPortal.installPrompt) {
    var installEvent = null;
    window.addEventListener('beforeinstallprompt', function (event) {
      event.preventDefault(); installEvent = event;
      if (readStorage('vetra-install-dismissed') !== '1') window.setTimeout(function () { installBanner.hidden = false; }, Math.max(0, Number(vetraPortal.installDelay) || 0) * 1000);
    });
    var installButton = installBanner.querySelector('.js-vetra-install');
    if (installButton) installButton.addEventListener('click', function () {
      if (!installEvent) return;
      installEvent.prompt(); installEvent.userChoice.finally(function () { installEvent = null; installBanner.hidden = true; });
    });
    var installDismiss = installBanner.querySelector('.js-vetra-install-dismiss');
    if (installDismiss) installDismiss.addEventListener('click', function () { writeStorage('vetra-install-dismissed', '1'); installBanner.hidden = true; });
    window.addEventListener('appinstalled', function () { installBanner.hidden = true; });
  }
}());
