(() => {
	const COOKIE_NAME = 'hnrk-consent';
	const settings    = window.hnrkCookieSettings ?? {};
	const COOKIE_DAYS = settings.cookieDays ?? 365;

	const VERSION = settings.consentVersion ?? '';

	// Removed when their category is declined.
	const COOKIE_PREFIXES = {
		analytics: ['_ga', '_gid', '_gat'],
		marketing: ['_fbp', '_fbc', '_gcl_', '_uetsid', '_uetvid', '_ttp', '_pin_unauth'],
	};

	// --- Cookie utilities ---
	const setCookie = (name, value, days) => {
		const expires = new Date(Date.now() + days * 864e5).toUTCString();
		const secure  = location.protocol === 'https:' ? '; Secure' : '';
		document.cookie = `${name}=${encodeURIComponent(JSON.stringify(value))}; expires=${expires}; path=/; SameSite=Lax${secure}`;
	};

	const getCookie = (name) => {
		const match = document.cookie.match(
			new RegExp('(?:^|; )' + name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '=([^;]*)')
		);
		if (!match) return null;
		try {
			return JSON.parse(decodeURIComponent(match[1]));
		} catch {
			return null;
		}
	};

	const deleteCookies = (prefixes) => {
		const hostname   = location.hostname;
		const rootDomain = hostname.replace(/^www\./, '');
		const past       = 'expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';

		document.cookie.split(';').forEach((cookie) => {
			const name = cookie.trim().split('=')[0];
			if (prefixes.some((prefix) => name.startsWith(prefix))) {
				document.cookie = `${name}=; ${past}`;
				document.cookie = `${name}=; ${past}; domain=${hostname}`;
				document.cookie = `${name}=; ${past}; domain=.${rootDomain}`;
			}
		});
	};

	// --- Blocked content: <script type="text/plain" data-consent="marketing"> ---
	const activateBlocked = (consent) => {
		document.querySelectorAll('script[type="text/plain"][data-consent]').forEach((blocked) => {
			if (!consent[blocked.dataset.consent]) return;

			const script = document.createElement('script');
			Array.from(blocked.attributes).forEach((attr) => {
				if (attr.name !== 'type' && attr.name !== 'data-consent') {
					script.setAttribute(attr.name, attr.value);
				}
			});
			script.text = blocked.text;
			blocked.replaceWith(script);
		});
	};

	// --- Consent management ---
	let current = null;

	const applyConsent = (consent) => {
		current = consent;

		if (typeof window.gtag === 'function') {
			window.gtag('consent', 'update', {
				analytics_storage:       consent.analytics ? 'granted' : 'denied',
				ad_storage:              consent.marketing ? 'granted' : 'denied',
				ad_user_data:            consent.marketing ? 'granted' : 'denied',
				ad_personalization:      consent.marketing ? 'granted' : 'denied',
				functionality_storage:   consent.functional ? 'granted' : 'denied',
				personalization_storage: consent.functional ? 'granted' : 'denied',
			});
		}

		Object.entries(COOKIE_PREFIXES).forEach(([category, prefixes]) => {
			if (!consent[category]) deleteCookies(prefixes);
		});

		activateBlocked(consent);

		document.dispatchEvent(new CustomEvent('hnrk:consent', { detail: { ...consent } }));
	};

	const saveConsent = (consent) => {
		const data = {
			necessary:  true,
			analytics:  false,
			functional: false,
			marketing:  false,
			thirdparty: false,
			...consent,
			v: VERSION,
		};
		setCookie(COOKIE_NAME, data, COOKIE_DAYS);
		applyConsent(data);
		hideBanner();
	};

	const collectToggles = () => {
		const consent = { necessary: true };
		banner.querySelectorAll('[data-consent-toggle]').forEach((toggle) => {
			const cat = toggle.dataset.consentToggle;
			if (cat !== 'necessary') consent[cat] = toggle.checked;
		});
		return consent;
	};

	// --- Banner & overlay ---
	const banner  = document.getElementById('cookie-banner');
	const overlay = document.getElementById('cookie-overlay');
	if (!banner) return;

	const isBottomBar = () => banner.classList.contains('cookie-banner--bottom');

	let previousFocus = null;

	const getFocusable = () =>
		Array.from(
			banner.querySelectorAll(
				'button:not([disabled]), input:not([disabled]), a[href], [tabindex]:not([tabindex="-1"])'
			)
		).filter((el) => !el.closest('[hidden]'));

	const showBanner = () => {
		previousFocus = document.activeElement;
		banner.removeAttribute('hidden');
		if (!isBottomBar()) {
			overlay?.removeAttribute('hidden');
			document.body.classList.add('cookie-banner-open');
		}
		requestAnimationFrame(() => getFocusable()[0]?.focus());
	};

	const hideBanner = () => {
		banner.setAttribute('hidden', '');
		overlay?.setAttribute('hidden', '');
		document.body.classList.remove('cookie-banner-open');
		previousFocus?.focus();
	};

	// --- Focus trap ---
	banner.addEventListener('keydown', (e) => {
		if (e.key === 'Escape') {
			current ? hideBanner() : saveConsent({ necessary: true });
			return;
		}

		if (e.key !== 'Tab') return;

		const focusable = getFocusable();
		const first = focusable[0];
		const last  = focusable[focusable.length - 1];

		if (e.shiftKey) {
			if (document.activeElement === first) { e.preventDefault(); last?.focus(); }
		} else {
			if (document.activeElement === last)  { e.preventDefault(); first?.focus(); }
		}
	});

	const syncToggles = (consent) => {
		banner.querySelectorAll('[data-consent-toggle]').forEach((toggle) => {
			const cat = toggle.dataset.consentToggle;
			if (cat !== 'necessary') toggle.checked = consent[cat] ?? false;
		});
	};

	const reopen = () => {
		syncToggles(current ?? { necessary: true });
		banner.classList.remove('cookie-banner--bottom');
		banner.setAttribute('role', 'dialog');
		banner.setAttribute('aria-modal', 'true');
		showBanner();
	};

	// --- Public API for themes and other scripts ---
	window.hnrkConsent = {
		get: () => (current ? { ...current } : null),
		has: (category) => !!current?.[category],
		open: reopen,
	};

	// --- Initialise on load ---
	const existing = getCookie(COOKIE_NAME);
	if (existing) {
		applyConsent(existing);
		syncToggles(existing);
	}

	// No choice yet, or the site's categories have changed since.
	if (!existing || existing.v !== VERSION) {
		showBanner();
	}

	// --- Button handlers ---

	banner.querySelector('[data-consent-accept]')?.addEventListener('click', () => {
		saveConsent({
			necessary:  true,
			analytics:  !!settings.enableAnalytics,
			functional: !!settings.enableFunctional,
			marketing:  !!settings.enableMarketing,
			thirdparty: !!settings.enableThirdparty,
		});
	});

	banner.querySelector('[data-consent-reject]')?.addEventListener('click', () => {
		saveConsent({ necessary: true });
	});

	banner.querySelector('[data-consent-save]')?.addEventListener('click', () => {
		saveConsent(collectToggles());
	});

	// --- Re-open: always as modal ---
	document.querySelectorAll('a[href="#manage-cookies"]').forEach((link) => {
		link.setAttribute('data-consent-reopen', '');
	});

	document.querySelectorAll('[data-consent-reopen]').forEach((btn) => {
		btn.addEventListener('click', (e) => {
			e.preventDefault();
			reopen();
		});
	});
})();
