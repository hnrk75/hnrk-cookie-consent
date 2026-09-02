(() => {
	const COOKIE_NAME = 'hnrk-consent';
	const COOKIE_DAYS = window.hnrkCookieSettings?.cookieDays ?? 365;

	// --- Cookie utilities ---
	const setCookie = (name, value, days) => {
		const expires = new Date(Date.now() + days * 864e5).toUTCString();
		document.cookie = `${name}=${encodeURIComponent(JSON.stringify(value))}; expires=${expires}; path=/; SameSite=Lax`;
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

	// --- Consent management ---

	const deleteGACookies = () => {
		const hostname   = location.hostname;
		const rootDomain = hostname.replace(/^www\./, '');
		document.cookie.split(';').forEach((cookie) => {
			const name = cookie.trim().split('=')[0];
			if (name.startsWith('_ga')) {
				const past = 'expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
				document.cookie = `${name}=; ${past}`;
				document.cookie = `${name}=; ${past}; domain=${hostname}`;
				document.cookie = `${name}=; ${past}; domain=.${rootDomain}`;
			}
		});
	};

	const applyConsent = (consent) => {
		if (typeof window.gtag === 'function') {
			window.gtag('consent', 'update', {
				analytics_storage:  consent.analytics ? 'granted' : 'denied',
				ad_storage:         consent.marketing ? 'granted' : 'denied',
				ad_user_data:       consent.marketing ? 'granted' : 'denied',
				ad_personalization: consent.marketing ? 'granted' : 'denied',
			});
		}
		if (!consent.analytics) deleteGACookies();
	};

	const saveConsent = (consent) => {
		const data = {
			necessary:  true,
			analytics:  false,
			functional: false,
			marketing:  false,
			thirdparty: false,
			...consent,
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
			const existing = getCookie(COOKIE_NAME);
			existing ? hideBanner() : saveConsent({ necessary: true });
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

	// --- Initialise on load ---
	const existing = getCookie(COOKIE_NAME);
	if (existing) {
		applyConsent(existing);
		syncToggles(existing);
	} else {
		showBanner();
	}

	// --- Button handlers ---

	banner.querySelector('[data-consent-accept]')?.addEventListener('click', () => {
		const s = window.hnrkCookieSettings ?? {};
		saveConsent({
			necessary:  true,
			analytics:  !!s.enableAnalytics,
			functional: !!s.enableFunctional,
			marketing:  !!s.enableMarketing,
			thirdparty: !!s.enableThirdparty,
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
			syncToggles(getCookie(COOKIE_NAME) ?? { necessary: true });
			banner.classList.remove('cookie-banner--bottom');
			showBanner();
		});
	});
})();
