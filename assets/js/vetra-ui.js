/**
 * VETRA Design System — UI behaviours
 * Version: 1.0.0
 *
 * Dependency-free, progressively-enhanced behaviours shared by all four VETRA
 * products. Everything is opt-in via `data-vetra-*` attributes and degrades
 * gracefully when JavaScript is unavailable.
 *
 * Public API: window.VetraUI
 *   .setTheme(mode)  .getTheme()  .toast(message, options)  .openModal(el)
 *   .closeModal(el)  .announce(message)
 */
(function () {
	'use strict';

	var config = window.vetraDS || {};
	var labels = config.labels || {};
	var root = document.documentElement;
	var STORAGE_KEY = 'vetra-theme-mode';

	function onReady(fn) {
		if (document.readyState !== 'loading') {
			fn();
		} else {
			document.addEventListener('DOMContentLoaded', fn);
		}
	}

	/* ---------------------------------------------------------------- *
	 * Colour scheme
	 * ---------------------------------------------------------------- */
	function storedMode() {
		try {
			var value = window.localStorage.getItem(STORAGE_KEY);
			return value === 'light' || value === 'dark' || value === 'auto' ? value : null;
		} catch (error) {
			return null;
		}
	}

	function applyTheme(mode) {
		if (mode === 'light' || mode === 'dark') {
			root.setAttribute('data-vetra-theme', mode);
			root.setAttribute('data-theme', mode);
		} else {
			root.removeAttribute('data-vetra-theme');
		}
		root.setAttribute('data-vetra-theme-mode', mode);
	}

	function setTheme(mode) {
		if (['light', 'dark', 'auto'].indexOf(mode) === -1) {
			return;
		}
		applyTheme(mode);
		try {
			window.localStorage.setItem(STORAGE_KEY, mode);
		} catch (error) {
			/* storage blocked — session-only override */
		}
		document.dispatchEvent(new CustomEvent('vetra:themechange', { detail: { mode: mode } }));
	}

	function getTheme() {
		var current = root.getAttribute('data-vetra-theme-mode');
		return current || 'auto';
	}

	function initTheme() {
		var initial = storedMode();
		if (!initial) {
			var configured = config.themeMode;
			if (configured === 'light' || configured === 'dark') {
				initial = configured;
			} else if (root.getAttribute('data-theme') === 'dark') {
				initial = 'dark';
			} else {
				initial = 'auto';
			}
		}
		applyTheme(initial);

		// Mirror the theme's own `data-theme` toggle so both systems stay in sync.
		if (window.MutationObserver) {
			new window.MutationObserver(function (records) {
				records.forEach(function (record) {
					if (record.attributeName !== 'data-theme') {
						return;
					}
					var value = root.getAttribute('data-theme');
					if ((value === 'light' || value === 'dark') && value !== root.getAttribute('data-vetra-theme')) {
						applyTheme(value);
					}
				});
			}).observe(root, { attributes: true, attributeFilter: ['data-theme'] });
		}

		document.addEventListener('click', function (event) {
			var toggle = event.target.closest('[data-vetra-theme-toggle]');
			if (!toggle) {
				return;
			}
			event.preventDefault();
			var requested = toggle.getAttribute('data-vetra-theme-toggle');
			var next = requested === 'toggle'
				? (getTheme() === 'dark' ? 'light' : 'dark')
				: requested;
			setTheme(next);
			toggle.setAttribute('aria-pressed', next === 'dark' ? 'true' : 'false');
		});
	}

	/* ---------------------------------------------------------------- *
	 * Live region (screen-reader announcements for toasts / status)
	 * ---------------------------------------------------------------- */
	var liveRegion = null;

	function region() {
		if (!liveRegion) {
			liveRegion = document.createElement('div');
			liveRegion.className = 'vetra-sr-only';
			liveRegion.setAttribute('role', 'status');
			liveRegion.setAttribute('aria-live', 'polite');
			liveRegion.setAttribute('aria-atomic', 'true');
			document.body.appendChild(liveRegion);
		}
		return liveRegion;
	}

	function announce(message) {
		region().textContent = message;
	}

	/* ---------------------------------------------------------------- *
	 * Toasts
	 * ---------------------------------------------------------------- */
	function toastRegion() {
		var node = document.querySelector('.vetra-toast-region');
		if (!node) {
			node = document.createElement('div');
			node.className = 'vetra-toast-region';
			node.setAttribute('role', 'region');
			node.setAttribute('aria-label', labels.notifications || 'اعلان‌ها');
			document.body.appendChild(node);
		}
		return node;
	}

	function toast(message, options) {
		options = options || {};
		var type = options.type || 'info';
		var timeout = typeof options.timeout === 'number' ? options.timeout : 5000;

		var el = document.createElement('div');
		el.className = 'vetra-toast vetra-toast--' + type;

		// Role: critical messages are assertive, everything else polite.
		el.setAttribute('role', type === 'critical' ? 'alert' : 'status');

		var text = document.createElement('span');
		text.className = 'vetra-toast__message';
		text.textContent = message;
		el.appendChild(text);

		var close = document.createElement('button');
		close.type = 'button';
		close.className = 'vetra-toast__close';
		close.setAttribute('aria-label', labels.dismiss || 'بستن');
		close.innerHTML = '&times;';
		close.addEventListener('click', function () {
			removeToast(el);
		});
		el.appendChild(close);

		toastRegion().appendChild(el);
		announce(message);

		if (timeout > 0) {
			window.setTimeout(function () {
				removeToast(el);
			}, timeout);
		}
		return el;
	}

	function removeToast(el) {
		if (!el || !el.parentNode) {
			return;
		}
		el.classList.add('is-leaving');
		window.setTimeout(function () {
			if (el.parentNode) {
				el.parentNode.removeChild(el);
			}
		}, 200);
	}

	function initToastTriggers() {
		document.addEventListener('click', function (event) {
			var trigger = event.target.closest('[data-vetra-toast]');
			if (!trigger) {
				return;
			}
			event.preventDefault();
			toast(trigger.getAttribute('data-vetra-toast'), {
				type: trigger.getAttribute('data-vetra-toast-type') || 'info'
			});
		});
	}

	/* ---------------------------------------------------------------- *
	 * Modal — focus trap + restore
	 * ---------------------------------------------------------------- */
	var lastFocused = null;

	function focusable(container) {
		return Array.prototype.slice.call(
			container.querySelectorAll(
				'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
			)
		).filter(function (node) {
			return node.offsetParent !== null;
		});
	}

	function openModal(modal) {
		if (typeof modal === 'string') {
			modal = document.getElementById(modal);
		}
		if (!modal) {
			return;
		}
		lastFocused = document.activeElement;
		modal.hidden = false;
		modal.setAttribute('aria-hidden', 'false');
		document.body.classList.add('vetra-modal-open');

		var items = focusable(modal);
		(items[0] || modal).focus();

		modal.dispatchEvent(new CustomEvent('vetra:modalopen', { bubbles: true }));
	}

	function closeModal(modal) {
		if (typeof modal === 'string') {
			modal = document.getElementById(modal);
		}
		if (!modal) {
			return;
		}
		modal.hidden = true;
		modal.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('vetra-modal-open');
		if (lastFocused && typeof lastFocused.focus === 'function') {
			lastFocused.focus();
		}
		modal.dispatchEvent(new CustomEvent('vetra:modalclose', { bubbles: true }));
	}

	function initModals() {
		document.addEventListener('click', function (event) {
			var opener = event.target.closest('[data-vetra-modal-open]');
			if (opener) {
				event.preventDefault();
				openModal(opener.getAttribute('data-vetra-modal-open'));
				return;
			}
			var closer = event.target.closest('[data-vetra-modal-close]');
			if (closer) {
				event.preventDefault();
				closeModal(closer.closest('.vetra-modal'));
				return;
			}
			var overlay = event.target.closest('.vetra-modal__overlay');
			if (overlay) {
				closeModal(overlay.closest('.vetra-modal'));
			}
		});

		document.addEventListener('keydown', function (event) {
			var modal = document.querySelector('.vetra-modal:not([hidden])');
			if (!modal) {
				return;
			}
			if (event.key === 'Escape') {
				closeModal(modal);
				return;
			}
			if (event.key !== 'Tab') {
				return;
			}
			var items = focusable(modal);
			if (!items.length) {
				return;
			}
			var first = items[0];
			var last = items[items.length - 1];
			if (event.shiftKey && document.activeElement === first) {
				event.preventDefault();
				last.focus();
			} else if (!event.shiftKey && document.activeElement === last) {
				event.preventDefault();
				first.focus();
			}
		});
	}

	/* ---------------------------------------------------------------- *
	 * Tabs
	 * ---------------------------------------------------------------- */
	function activateTab(tab) {
		var list = tab.closest('.vetra-tabs__list');
		if (!list) {
			return;
		}
		var tabs = Array.prototype.slice.call(list.querySelectorAll('[role="tab"]'));
		tabs.forEach(function (item) {
			var selected = item === tab;
			item.setAttribute('aria-selected', selected ? 'true' : 'false');
			item.tabIndex = selected ? 0 : -1;
			var panel = document.getElementById(item.getAttribute('aria-controls'));
			if (panel) {
				panel.hidden = !selected;
			}
		});
	}

	function initTabs() {
		document.addEventListener('click', function (event) {
			var tab = event.target.closest('[role="tab"]');
			if (tab) {
				activateTab(tab);
			}
		});

		document.addEventListener('keydown', function (event) {
			var tab = event.target.closest('[role="tab"]');
			if (!tab) {
				return;
			}
			var tabs = Array.prototype.slice.call(tab.closest('.vetra-tabs__list').querySelectorAll('[role="tab"]'));
			var index = tabs.indexOf(tab);
			var next = null;
			if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
				var dir = event.key === 'ArrowLeft' ? 1 : -1; // RTL: left advances
				next = tabs[(index + dir + tabs.length) % tabs.length];
			} else if (event.key === 'Home') {
				next = tabs[0];
			} else if (event.key === 'End') {
				next = tabs[tabs.length - 1];
			}
			if (next) {
				event.preventDefault();
				activateTab(next);
				next.focus();
			}
		});
	}

	/* ---------------------------------------------------------------- *
	 * Tooltip (touch / keyboard fallback via click)
	 * ---------------------------------------------------------------- */
	function initTooltips() {
		document.addEventListener('click', function (event) {
			var tip = event.target.closest('[data-vetra-tooltip]');
			document.querySelectorAll('.vetra-tooltip.is-open').forEach(function (open) {
				if (open !== tip) {
					open.classList.remove('is-open');
				}
			});
			if (tip) {
				tip.classList.toggle('is-open');
			}
		});
		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape') {
				document.querySelectorAll('.vetra-tooltip.is-open').forEach(function (open) {
					open.classList.remove('is-open');
				});
			}
		});
	}

	/* ---------------------------------------------------------------- *
	 * Loading buttons
	 * ---------------------------------------------------------------- */
	function initLoading() {
		document.addEventListener('click', function (event) {
			var btn = event.target.closest('[data-vetra-loading]');
			if (!btn || btn.getAttribute('aria-busy') === 'true') {
				return;
			}
			btn.setAttribute('aria-busy', 'true');
			btn.classList.add('is-loading');
		});
	}

	/* ---------------------------------------------------------------- *
	 * Table sort (client-side, accessible)
	 * ---------------------------------------------------------------- */
	function initTableSort() {
		document.addEventListener('click', function (event) {
			var th = event.target.closest('[data-vetra-sort]');
			if (!th) {
				return;
			}
			var table = th.closest('table');
			var body = table && table.tBodies[0];
			if (!body) {
				return;
			}
			var index = Array.prototype.indexOf.call(th.parentNode.children, th);
			var type = th.getAttribute('data-vetra-sort') || 'text';
			var ascending = th.getAttribute('aria-sort') !== 'ascending';

			Array.prototype.forEach.call(table.querySelectorAll('[data-vetra-sort]'), function (cell) {
				cell.setAttribute('aria-sort', 'none');
			});
			th.setAttribute('aria-sort', ascending ? 'ascending' : 'descending');

			var rows = Array.prototype.slice.call(body.rows);
			rows.sort(function (a, b) {
				var x = (a.cells[index] && a.cells[index].textContent || '').trim();
				var y = (b.cells[index] && b.cells[index].textContent || '').trim();
				if (type === 'number') {
					var nx = parseFloat(x.replace(/[^\d.-]/g, ''));
					var ny = parseFloat(y.replace(/[^\d.-]/g, ''));
					nx = isNaN(nx) ? 0 : nx;
					ny = isNaN(ny) ? 0 : ny;
					return ascending ? nx - ny : ny - nx;
				}
				return ascending ? x.localeCompare(y, 'fa') : y.localeCompare(x, 'fa');
			});
			rows.forEach(function (row) {
				body.appendChild(row);
			});
		});
	}

	/* ---------------------------------------------------------------- *
	 * Destructive confirmation without colour-only signalling
	 * ---------------------------------------------------------------- */
	function initConfirm() {
		document.addEventListener('click', function (event) {
			var trigger = event.target.closest('[data-vetra-confirm]');
			if (!trigger) {
				return;
			}
			if (!window.confirm(trigger.getAttribute('data-vetra-confirm'))) {
				event.preventDefault();
				event.stopImmediatePropagation();
			}
		});
	}

	/* ---------------------------------------------------------------- *
	 * Boot
	 * ---------------------------------------------------------------- */
	onReady(function () {
		initTheme();
		initToastTriggers();
		initModals();
		initTabs();
		initTooltips();
		initLoading();
		initTableSort();
		initConfirm();
	});

	window.VetraUI = {
		setTheme: setTheme,
		getTheme: getTheme,
		toast: toast,
		openModal: openModal,
		closeModal: closeModal,
		announce: announce
	};
}());
