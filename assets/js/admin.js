document.addEventListener('DOMContentLoaded', () => {
	// Show / hide the floating button label field based on the trigger radio.
	const triggerRadios  = document.querySelectorAll('[name="hnrk_cookie_settings[manage_cookies_trigger]"]');
	const labelWrapper   = document.getElementById('trigger_label_wrapper');
	if (triggerRadios.length && labelWrapper) {
		triggerRadios.forEach((radio) => {
			radio.addEventListener('change', () => {
				labelWrapper.hidden = radio.value !== 'floating';
			});
		});
	}

	document.querySelectorAll('.hnrk-cookie-admin__edit-link').forEach((btn) => {
		const editor  = document.getElementById(btn.getAttribute('aria-controls'));
		const body    = btn.closest('.hnrk-cookie-admin__category')
			?.querySelector('.hnrk-cookie-admin__category-body');
		const preview = body?.querySelector('.hnrk-cookie-admin__category-preview');

		if (!editor) return;

		// Show any sibling descriptions (e.g. the %s hint) alongside the editor.
		const hints = body ? Array.from(body.querySelectorAll('.description')) : [];

		btn.addEventListener('click', () => {
			const isOpen = btn.getAttribute('aria-expanded') === 'true';

			if (isOpen) {
				// Sync preview with current editor value before closing.
				if (preview) {
					const val = editor.value ?? '';
					if (editor.tagName === 'TEXTAREA' || editor.type === 'text' || editor.type === 'url') {
						preview.textContent = val;
					} else if (editor.type === 'number') {
						preview.textContent = val + ' dagar';
					}
				}
				editor.setAttribute('hidden', '');
				hints.forEach((h) => h.setAttribute('hidden', ''));
				if (preview) preview.removeAttribute('hidden');
				btn.setAttribute('aria-expanded', 'false');
				btn.textContent = 'Redigera';
			} else {
				if (preview) preview.setAttribute('hidden', '');
				editor.removeAttribute('hidden');
				hints.forEach((h) => h.removeAttribute('hidden'));
				editor.focus();
				btn.setAttribute('aria-expanded', 'true');
				btn.textContent = 'Stäng';
			}
		});
	});
});
