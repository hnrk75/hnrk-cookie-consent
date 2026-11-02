<?php
/**
 * Admin settings page for HNRK Cookie Consent.
 *
 * Option key : hnrk_cookie_settings
 * Settings group: hnrk_cookie_settings_group
 *
 * @package HNRK Cookie Consent
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ------------------------------------------------------------
// Register settings
// ------------------------------------------------------------

/**
 * Register the option and its sanitisation callback.
 */
function hnrk_cookie_register_settings() {
	register_setting(
		'hnrk_cookie_settings_group',
		'hnrk_cookie_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'hnrk_cookie_sanitize_settings',
			'default'           => hnrk_cookie_default_settings(),
		)
	);

	// --- Section: General ----------------------------------------
	add_settings_section(
		'hnrk_cookie_section_general',
		'',
		'__return_false',
		'hnrk-cookie-consent-general'
	);

	add_settings_field(
		'banner_title',
		__( 'Banner title', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_banner_title',
		'hnrk-cookie-consent-general',
		'hnrk_cookie_section_general'
	);

	add_settings_field(
		'banner_text',
		__( 'Banner intro text', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_banner_text',
		'hnrk-cookie-consent-general',
		'hnrk_cookie_section_general'
	);

	add_settings_field(
		'cookie_page_url',
		__( 'Cookie policy page', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_url',
		'hnrk-cookie-consent-general',
		'hnrk_cookie_section_general'
	);

	add_settings_field(
		'cookie_days',
		__( 'Cookie lifetime (days)', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_days',
		'hnrk-cookie-consent-general',
		'hnrk_cookie_section_general'
	);

	add_settings_field(
		'banner_position',
		__( 'Banner position', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_banner_position',
		'hnrk-cookie-consent-general',
		'hnrk_cookie_section_general'
	);

	// --- Section: Categories -------------------------------------
	add_settings_section(
		'hnrk_cookie_section_categories',
		'',
		'hnrk_cookie_section_categories_description',
		'hnrk-cookie-consent-categories'
	);

	add_settings_field(
		'necessary_description',
		__( 'Necessary', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_necessary_description',
		'hnrk-cookie-consent-categories',
		'hnrk_cookie_section_categories'
	);

	add_settings_field(
		'enable_analytics',
		__( 'Analytics / Statistics', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_analytics',
		'hnrk-cookie-consent-categories',
		'hnrk_cookie_section_categories'
	);

	add_settings_field(
		'enable_functional',
		__( 'Functional', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_functional',
		'hnrk-cookie-consent-categories',
		'hnrk_cookie_section_categories'
	);

	add_settings_field(
		'enable_marketing',
		__( 'Marketing', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_marketing',
		'hnrk-cookie-consent-categories',
		'hnrk_cookie_section_categories'
	);

	add_settings_field(
		'enable_thirdparty',
		__( 'Third-party services', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_thirdparty',
		'hnrk-cookie-consent-categories',
		'hnrk_cookie_section_categories'
	);

	// --- Section: Reopen -------------------------------------------
	add_settings_section(
		'hnrk_cookie_section_reopen',
		'',
		'__return_false',
		'hnrk-cookie-consent-reopen'
	);

	add_settings_field(
		'manage_cookies_trigger',
		__( 'Reopen banner', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_trigger',
		'hnrk-cookie-consent-reopen',
		'hnrk_cookie_section_reopen'
	);
}
add_action( 'admin_init', 'hnrk_cookie_register_settings' );

// ------------------------------------------------------------
// Default settings
// ------------------------------------------------------------

/**
 * Default texts as [ English source, translation ]. Unchanged defaults are stored empty,
 * so they follow the site's language.
 *
 * @return array<string, array{0: string, 1: string}>
 */
function hnrk_cookie_default_texts() {
	return array(
		'banner_title'           => array(
			'We value your privacy',
			__( 'We value your privacy', 'hnrk-cookie-consent' ),
		),
		'banner_text'            => array(
			'We use cookies to improve your experience, show personalised content and analyse our traffic. By clicking "Accept all" you consent to our use of cookies. <a href="%s">Read about cookies</a>',
			/* translators: %s: URL of the cookie policy page. */
			__( 'We use cookies to improve your experience, show personalised content and analyse our traffic. By clicking "Accept all" you consent to our use of cookies. <a href="%s">Read about cookies</a>', 'hnrk-cookie-consent' ),
		),
		'necessary_description'  => array(
			'Required for the website to function properly. These cookies do not store any personal data.',
			__( 'Required for the website to function properly. These cookies do not store any personal data.', 'hnrk-cookie-consent' ),
		),
		'analytics_description'  => array(
			'Help us understand how visitors interact with the website — number of visitors, bounce rate, traffic source, etc.',
			__( 'Help us understand how visitors interact with the website — number of visitors, bounce rate, traffic source, etc.', 'hnrk-cookie-consent' ),
		),
		'functional_description' => array(
			'Enable enhanced functionality such as saved preferences, language selection or embedded content.',
			__( 'Enable enhanced functionality such as saved preferences, language selection or embedded content.', 'hnrk-cookie-consent' ),
		),
		'marketing_description'  => array(
			'Used for personalised advertising and tracking via third-party services such as Google Ads and Meta.',
			__( 'Used for personalised advertising and tracking via third-party services such as Google Ads and Meta.', 'hnrk-cookie-consent' ),
		),
		'thirdparty_description' => array(
			'Cookies from embedded services such as YouTube, Vimeo or Google Maps.',
			__( 'Cookies from embedded services such as YouTube, Vimeo or Google Maps.', 'hnrk-cookie-consent' ),
		),
		'manage_cookies_label'   => array(
			'Manage cookies',
			__( 'Manage cookies', 'hnrk-cookie-consent' ),
		),
	);
}

/**
 * Returns the default settings array, with texts in the current language.
 *
 * @return array
 */
function hnrk_cookie_default_settings() {
	return array_merge(
		array(
			'cookie_page_id'          => 0,
			'cookie_page_url'         => '',
			'cookie_days'             => 365,
			'enable_analytics'        => false,
			'enable_functional'       => false,
			'enable_marketing'        => false,
			'enable_thirdparty'       => false,
			'banner_position'         => 'modal',
			// GDPR: withdrawing consent must be as easy as giving it.
			'manage_cookies_trigger'  => 'floating',
			'manage_cookies_position' => 'right',
		),
		array_map(
			function ( $text ) {
				return $text[1];
			},
			hnrk_cookie_default_texts()
		)
	);
}

/**
 * Saved settings merged with defaults; empty texts get the translated default.
 *
 * @return array
 */
function hnrk_cookie_get_settings() {
	$defaults = hnrk_cookie_default_settings();
	$settings = wp_parse_args( (array) get_option( 'hnrk_cookie_settings', array() ), $defaults );

	foreach ( array_keys( hnrk_cookie_default_texts() ) as $key ) {
		if ( '' === trim( (string) $settings[ $key ] ) ) {
			$settings[ $key ] = $defaults[ $key ];
		}
	}

	return $settings;
}

/**
 * The cookie policy URL from the settings: the selected page, else the custom URL.
 *
 * @param array $settings Plugin settings.
 * @return string Empty when there is no page.
 */
function hnrk_cookie_own_page_url( $settings ) {
	$page_id = (int) $settings['cookie_page_id'];

	if ( $page_id && 'publish' === get_post_status( $page_id ) ) {
		return (string) get_permalink( $page_id );
	}

	$url = (string) $settings['cookie_page_url'];

	// The old default /cookies/ is ignored unless a page actually lives there.
	if ( $url && untrailingslashit( $url ) === untrailingslashit( home_url( '/cookies' ) ) && ! url_to_postid( $url ) ) {
		return '';
	}

	return $url;
}

/**
 * The cookie policy URL the banner uses. A theme can set it with `hnrk_cookie_page_url`.
 *
 * @return string Empty when there is no page.
 */
function hnrk_cookie_page_url() {
	return (string) apply_filters( 'hnrk_cookie_page_url', hnrk_cookie_own_page_url( hnrk_cookie_get_settings() ) );
}

/**
 * Empties texts that equal their default, so they are not locked to one language.
 *
 * @param array $settings Settings to store.
 * @return array
 */
function hnrk_cookie_unlock_default_texts( $settings ) {
	foreach ( hnrk_cookie_default_texts() as $key => $text ) {
		if ( isset( $settings[ $key ] ) && in_array( trim( (string) $settings[ $key ] ), $text, true ) ) {
			$settings[ $key ] = '';
		}
	}

	return $settings;
}

/**
 * Sites saved before 1.0.6 have their default texts locked to one language.
 */
function hnrk_cookie_migrate_default_texts() {
	$stored = get_option( 'hnrk_cookie_settings' );

	if ( ! is_array( $stored ) ) {
		return;
	}

	$unlocked = hnrk_cookie_unlock_default_texts( $stored );

	if ( $unlocked !== $stored ) {
		update_option( 'hnrk_cookie_settings', $unlocked );
	}
}
add_action( 'admin_init', 'hnrk_cookie_migrate_default_texts' );

/**
 * Once: the old choice "none" becomes the footer link if the theme supports it,
 * otherwise the floating button.
 */
function hnrk_cookie_migrate_manage_trigger() {
	if ( get_option( 'hnrk_cookie_trigger_none_removed' ) ) {
		return;
	}

	$stored = get_option( 'hnrk_cookie_settings' );

	if ( is_array( $stored ) && ! in_array( $stored['manage_cookies_trigger'] ?? '', array( 'floating', 'footer' ), true ) ) {
		$stored['manage_cookies_trigger'] = hnrk_cookie_footer_supported() ? 'footer' : 'floating';
		update_option( 'hnrk_cookie_settings', $stored );
	}

	update_option( 'hnrk_cookie_trigger_none_removed', time(), false );
}
add_action( 'admin_init', 'hnrk_cookie_migrate_manage_trigger' );

// ------------------------------------------------------------
// Sanitisation
// ------------------------------------------------------------

/**
 * Sanitise settings before saving.
 *
 * @param array $input Raw POST values.
 * @return array Sanitised values.
 */
function hnrk_cookie_sanitize_settings( $input ) {
	$clean = hnrk_cookie_default_settings();

	if ( isset( $input['banner_title'] ) ) {
		$clean['banner_title'] = sanitize_text_field( $input['banner_title'] );
	}

	if ( isset( $input['banner_text'] ) ) {
		$clean['banner_text'] = wp_kses(
			$input['banner_text'],
			array(
				'a' => array(
					'href' => array(),
				),
			)
		);
	}

	if ( isset( $input['cookie_page_id'] ) ) {
		$clean['cookie_page_id'] = absint( $input['cookie_page_id'] );
	}

	if ( isset( $input['cookie_page_url'] ) ) {
		$clean['cookie_page_url'] = esc_url_raw( trim( (string) $input['cookie_page_url'] ) );
	}

	if ( isset( $input['cookie_days'] ) ) {
		$days                 = absint( $input['cookie_days'] );
		$clean['cookie_days'] = ( $days > 0 && $days <= 3650 ) ? $days : 365;
	}

	$clean['enable_analytics']  = ! empty( $input['enable_analytics'] );
	$clean['enable_functional'] = ! empty( $input['enable_functional'] );
	$clean['enable_marketing']  = ! empty( $input['enable_marketing'] );
	$clean['enable_thirdparty'] = ! empty( $input['enable_thirdparty'] );

	$description_keys = array(
		'necessary_description',
		'analytics_description',
		'functional_description',
		'marketing_description',
		'thirdparty_description',
	);

	foreach ( $description_keys as $key ) {
		if ( isset( $input[ $key ] ) ) {
			$clean[ $key ] = sanitize_textarea_field( $input[ $key ] );
		}
	}

	$allowed_triggers = array( 'floating', 'footer' );
	if ( isset( $input['manage_cookies_trigger'] ) && in_array( $input['manage_cookies_trigger'], $allowed_triggers, true ) ) {
		$clean['manage_cookies_trigger'] = $input['manage_cookies_trigger'];
	}

	if ( isset( $input['manage_cookies_label'] ) ) {
		$clean['manage_cookies_label'] = sanitize_text_field( $input['manage_cookies_label'] );
	}

	$allowed_banner_positions = array( 'modal', 'bottom' );
	if ( isset( $input['banner_position'] ) && in_array( $input['banner_position'], $allowed_banner_positions, true ) ) {
		$clean['banner_position'] = $input['banner_position'];
	}

	$allowed_positions = array( 'right', 'left' );
	if ( isset( $input['manage_cookies_position'] ) && in_array( $input['manage_cookies_position'], $allowed_positions, true ) ) {
		$clean['manage_cookies_position'] = $input['manage_cookies_position'];
	}

	return hnrk_cookie_unlock_default_texts( $clean );
}

// ------------------------------------------------------------
// Field callbacks
// ------------------------------------------------------------

/** Renders the banner title field. */
function hnrk_cookie_field_banner_title() {
	$settings = hnrk_cookie_get_settings();
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<p class="hnrk-cookie-admin__hint">
				<?php esc_html_e( 'The heading displayed at the top of the cookie banner.', 'hnrk-cookie-consent' ); ?>
			</p>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="banner_title_editor">
				<?php esc_html_e( 'Edit', 'hnrk-cookie-consent' ); ?>
			</button>
		</div>
		<div class="hnrk-cookie-admin__category-body">
			<div class="hnrk-cookie-admin__category-preview">
				<?php echo esc_html( $settings['banner_title'] ); ?>
			</div>
			<input
				type="text"
				name="hnrk_cookie_settings[banner_title]"
				id="banner_title_editor"
				value="<?php echo esc_attr( $settings['banner_title'] ); ?>"
				class="hnrk-cookie-admin__category-editor widefat"
				hidden
			>
		</div>
	</div>
	<?php
}

/** Renders the banner intro text field. */
function hnrk_cookie_field_banner_text() {
	$settings = hnrk_cookie_get_settings();
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<p class="hnrk-cookie-admin__hint">
				<?php
				/* translators: %s is replaced with the cookie policy page URL. */
				esc_html_e( 'The intro text in the banner. Use %s as a placeholder where the link to the cookie policy page is inserted.', 'hnrk-cookie-consent' );
				?>
			</p>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="banner_text_editor">
				<?php esc_html_e( 'Edit', 'hnrk-cookie-consent' ); ?>
			</button>
		</div>
		<div class="hnrk-cookie-admin__category-body">
			<div class="hnrk-cookie-admin__category-preview">
				<?php
				echo wp_kses(
					str_replace( '%s', '#', $settings['banner_text'] ),
					array( 'a' => array( 'href' => array() ) )
				);
				?>
			</div>
			<textarea
				name="hnrk_cookie_settings[banner_text]"
				id="banner_text_editor"
				class="hnrk-cookie-admin__category-editor"
				rows="4"
				hidden
			><?php echo esc_textarea( $settings['banner_text'] ); ?></textarea>
		</div>
	</div>
	<?php
}

/** Renders the cookie page URL field. */
function hnrk_cookie_field_url() {
	$settings  = hnrk_cookie_get_settings();
	$own_url   = hnrk_cookie_own_page_url( $settings );
	$url       = hnrk_cookie_page_url();
	$by_filter = $url !== $own_url;
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<p class="hnrk-cookie-admin__hint">
				<?php
				/* translators: %s is the placeholder in the banner text field. */
				esc_html_e( 'The page the banner links to via %s.', 'hnrk-cookie-consent' );
				?>
			</p>
			<?php if ( ! $by_filter ) : ?>
				<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="cookie_page_url_editor">
					<?php esc_html_e( 'Edit', 'hnrk-cookie-consent' ); ?>
				</button>
			<?php elseif ( $url && get_edit_post_link( url_to_postid( $url ) ) ) : ?>
				<a class="hnrk-cookie-admin__edit-link" href="<?php echo esc_url( get_edit_post_link( url_to_postid( $url ) ) ); ?>">
					<?php esc_html_e( 'Edit', 'hnrk-cookie-consent' ); ?>
				</a>
			<?php endif; ?>
		</div>
		<div class="hnrk-cookie-admin__category-body">
			<div class="hnrk-cookie-admin__category-preview">
				<?php if ( $by_filter && $url ) : ?>
					<?php esc_html_e( 'Set by the theme:', 'hnrk-cookie-consent' ); ?>
					<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $url ); ?></a>
				<?php elseif ( $url ) : ?>
					<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $url ); ?></a>
				<?php else : ?>
					<strong><?php esc_html_e( 'No page selected — the banner shows no link to a cookie policy.', 'hnrk-cookie-consent' ); ?></strong>
				<?php endif; ?>
			</div>
			<?php if ( ! $by_filter ) : ?>
				<div id="cookie_page_url_editor" class="hnrk-cookie-admin__category-editor" hidden>
					<p>
						<label for="cookie_page_id"><?php esc_html_e( 'Page', 'hnrk-cookie-consent' ); ?></label><br>
						<?php
						wp_dropdown_pages(
							array(
								'name'              => 'hnrk_cookie_settings[cookie_page_id]',
								'id'                => 'cookie_page_id',
								'selected'          => (int) $settings['cookie_page_id'],
								'show_option_none'  => esc_html__( '— Select a page —', 'hnrk-cookie-consent' ),
								'option_none_value' => '0',
							)
						);
						?>
					</p>
					<p>
						<label for="cookie_page_custom_url"><?php esc_html_e( 'Or a custom URL', 'hnrk-cookie-consent' ); ?></label><br>
						<input
							type="url"
							name="hnrk_cookie_settings[cookie_page_url]"
							id="cookie_page_custom_url"
							value="<?php echo esc_attr( $settings['cookie_page_url'] ); ?>"
							class="widefat"
						>
					</p>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/** Renders the banner position radio field. */
function hnrk_cookie_field_banner_position() {
	$settings = hnrk_cookie_get_settings();
	$pos      = $settings['banner_position'];
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<p class="hnrk-cookie-admin__hint">
				<?php esc_html_e( 'Modal is displayed centred on the page with a dark overlay. Strip is displayed along the bottom of the page without an overlay.', 'hnrk-cookie-consent' ); ?>
			</p>
		</div>
		<div class="hnrk-cookie-admin__trigger-options" style="margin-bottom:0">
			<label class="hnrk-cookie-admin__radio">
				<input
					type="radio"
					name="hnrk_cookie_settings[banner_position]"
					value="modal"
					<?php checked( $pos, 'modal' ); ?>
				>
				<?php esc_html_e( 'Modal (centred)', 'hnrk-cookie-consent' ); ?>
			</label>
			<label class="hnrk-cookie-admin__radio">
				<input
					type="radio"
					name="hnrk_cookie_settings[banner_position]"
					value="bottom"
					<?php checked( $pos, 'bottom' ); ?>
				>
				<?php esc_html_e( 'Strip (bottom)', 'hnrk-cookie-consent' ); ?>
			</label>
		</div>
	</div>
	<?php
}

/** Renders the cookie lifetime field. */
function hnrk_cookie_field_days() {
	$settings = hnrk_cookie_get_settings();
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<p class="hnrk-cookie-admin__hint">
				<?php esc_html_e( 'How long the visitor\'s consent choice is stored. Default: 365 days (1 year).', 'hnrk-cookie-consent' ); ?>
			</p>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="cookie_days_editor">
				<?php esc_html_e( 'Edit', 'hnrk-cookie-consent' ); ?>
			</button>
		</div>
		<div class="hnrk-cookie-admin__category-body">
			<div class="hnrk-cookie-admin__category-preview">
				<?php echo esc_html( $settings['cookie_days'] ); ?> <?php esc_html_e( 'days', 'hnrk-cookie-consent' ); ?>
			</div>
			<input
				type="number"
				name="hnrk_cookie_settings[cookie_days]"
				id="cookie_days_editor"
				value="<?php echo esc_attr( $settings['cookie_days'] ); ?>"
				min="1"
				max="3650"
				class="hnrk-cookie-admin__category-editor small-text"
				hidden
			>
		</div>
	</div>
	<?php
}

/** Outputs the categories section description. */
function hnrk_cookie_section_categories_description() {
	echo '<p>' . esc_html__( 'Necessary cookies are always shown. Enable the categories your site actually uses — others will not appear in the banner.', 'hnrk-cookie-consent' ) . '</p>';
}

/** Renders the necessary cookies description field. */
function hnrk_cookie_field_necessary_description() {
	$settings = hnrk_cookie_get_settings();
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<span class="hnrk-cookie-admin__checkbox">
				<?php esc_html_e( 'Always active', 'hnrk-cookie-consent' ); ?>
			</span>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="necessary_editor">
				<?php esc_html_e( 'Edit info', 'hnrk-cookie-consent' ); ?>
			</button>
		</div>
		<div class="hnrk-cookie-admin__category-body">
			<div class="hnrk-cookie-admin__category-preview">
				<?php echo esc_html( $settings['necessary_description'] ); ?>
			</div>
			<textarea
				name="hnrk_cookie_settings[necessary_description]"
				id="necessary_editor"
				class="hnrk-cookie-admin__category-editor"
				rows="3"
				hidden
			><?php echo esc_textarea( $settings['necessary_description'] ); ?></textarea>
		</div>
	</div>
	<?php
}

/** Renders the analytics category field. */
function hnrk_cookie_field_analytics() {
	$settings = hnrk_cookie_get_settings();
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<label class="hnrk-cookie-admin__checkbox">
				<input
					type="checkbox"
					name="hnrk_cookie_settings[enable_analytics]"
					id="enable_analytics"
					value="1"
					<?php checked( $settings['enable_analytics'] ); ?>
				>
				<?php esc_html_e( 'Show category', 'hnrk-cookie-consent' ); ?>
			</label>

			<button
				type="button"
				class="hnrk-cookie-admin__edit-link"
				aria-expanded="false"
				aria-controls="analytics_editor"
			>
				<?php esc_html_e( 'Edit info', 'hnrk-cookie-consent' ); ?>
			</button>
		</div>

		<div class="hnrk-cookie-admin__category-body">
			<div class="hnrk-cookie-admin__category-preview">
				<?php echo esc_html( $settings['analytics_description'] ); ?>
			</div>

			<textarea
				name="hnrk_cookie_settings[analytics_description]"
				id="analytics_editor"
				class="hnrk-cookie-admin__category-editor"
				rows="3"
				hidden
			><?php echo esc_textarea( $settings['analytics_description'] ); ?></textarea>
		</div>
	</div>
	<?php
}

/** Renders the functional cookies category field. */
function hnrk_cookie_field_functional() {
	$settings = hnrk_cookie_get_settings();
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<label class="hnrk-cookie-admin__checkbox">
				<input
					type="checkbox"
					name="hnrk_cookie_settings[enable_functional]"
					id="enable_functional"
					value="1"
					<?php checked( $settings['enable_functional'] ); ?>
				>
				<?php esc_html_e( 'Show category', 'hnrk-cookie-consent' ); ?>
			</label>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="functional_editor">
				<?php esc_html_e( 'Edit info', 'hnrk-cookie-consent' ); ?>
			</button>
		</div>
		<div class="hnrk-cookie-admin__category-body">
			<div class="hnrk-cookie-admin__category-preview">
				<?php echo esc_html( $settings['functional_description'] ); ?>
			</div>
			<textarea
				name="hnrk_cookie_settings[functional_description]"
				id="functional_editor"
				class="hnrk-cookie-admin__category-editor"
				rows="3"
				hidden
			><?php echo esc_textarea( $settings['functional_description'] ); ?></textarea>
		</div>
	</div>
	<?php
}

/** Renders the marketing cookies category field. */
function hnrk_cookie_field_marketing() {
	$settings = hnrk_cookie_get_settings();
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<label class="hnrk-cookie-admin__checkbox">
				<input
					type="checkbox"
					name="hnrk_cookie_settings[enable_marketing]"
					id="enable_marketing"
					value="1"
					<?php checked( $settings['enable_marketing'] ); ?>
				>
				<?php esc_html_e( 'Show category', 'hnrk-cookie-consent' ); ?>
			</label>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="marketing_editor">
				<?php esc_html_e( 'Edit info', 'hnrk-cookie-consent' ); ?>
			</button>
		</div>
		<div class="hnrk-cookie-admin__category-body">
			<div class="hnrk-cookie-admin__category-preview">
				<?php echo esc_html( $settings['marketing_description'] ); ?>
			</div>
			<textarea
				name="hnrk_cookie_settings[marketing_description]"
				id="marketing_editor"
				class="hnrk-cookie-admin__category-editor"
				rows="3"
				hidden
			><?php echo esc_textarea( $settings['marketing_description'] ); ?></textarea>
		</div>
	</div>
	<?php
}

/** Renders the third-party cookies category field. */
function hnrk_cookie_field_thirdparty() {
	$settings = hnrk_cookie_get_settings();
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<label class="hnrk-cookie-admin__checkbox">
				<input
					type="checkbox"
					name="hnrk_cookie_settings[enable_thirdparty]"
					id="enable_thirdparty"
					value="1"
					<?php checked( $settings['enable_thirdparty'] ); ?>
				>
				<?php esc_html_e( 'Show category', 'hnrk-cookie-consent' ); ?>
			</label>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="thirdparty_editor">
				<?php esc_html_e( 'Edit info', 'hnrk-cookie-consent' ); ?>
			</button>
		</div>
		<div class="hnrk-cookie-admin__category-body">
			<div class="hnrk-cookie-admin__category-preview">
				<?php echo esc_html( $settings['thirdparty_description'] ); ?>
			</div>
			<textarea
				name="hnrk_cookie_settings[thirdparty_description]"
				id="thirdparty_editor"
				class="hnrk-cookie-admin__category-editor"
				rows="3"
				hidden
			><?php echo esc_textarea( $settings['thirdparty_description'] ); ?></textarea>
		</div>
	</div>
	<?php
}

/** Renders the reopen trigger field. */
function hnrk_cookie_field_trigger() {
	$settings  = hnrk_cookie_get_settings();
	$trigger   = hnrk_cookie_manage_trigger();
	$position  = $settings['manage_cookies_position'];
	$supported = hnrk_cookie_footer_supported();
	?>
	<div class="hnrk-cookie-admin__trigger-options">
		<label class="hnrk-cookie-admin__radio">
			<input
				type="radio"
				name="hnrk_cookie_settings[manage_cookies_trigger]"
				value="floating"
				<?php checked( $trigger, 'floating' ); ?>
			>
			<?php esc_html_e( 'Floating button', 'hnrk-cookie-consent' ); ?>
		</label>
		<label class="hnrk-cookie-admin__radio">
			<input
				type="radio"
				name="hnrk_cookie_settings[manage_cookies_trigger]"
				value="footer"
				<?php checked( $trigger, 'footer' ); ?>
				<?php disabled( ! $supported ); ?>
			>
			<?php esc_html_e( 'In the footer', 'hnrk-cookie-consent' ); ?>
		</label>
	</div>
	<?php if ( ! $supported ) : ?>
		<p class="description">
			<?php esc_html_e( 'The active theme does not print a footer link, so the floating button is used.', 'hnrk-cookie-consent' ); ?>
		</p>
	<?php endif; ?>

	<div class="hnrk-cookie-admin__shortcode-box">
		<label for="manage_cookies_label">
			<?php esc_html_e( 'Label', 'hnrk-cookie-consent' ); ?>
		</label>
		<input
			type="text"
			name="hnrk_cookie_settings[manage_cookies_label]"
			id="manage_cookies_label"
			value="<?php echo esc_attr( $settings['manage_cookies_label'] ); ?>"
			class="regular-text"
			placeholder="<?php esc_attr_e( 'Manage cookies', 'hnrk-cookie-consent' ); ?>"
		>
	</div>

	<div class="hnrk-cookie-admin__shortcode-box" id="trigger_label_wrapper" <?php echo 'floating' !== $trigger ? 'hidden' : ''; ?>>
		<p class="hnrk-cookie-admin__shortcode-title">
			<?php esc_html_e( 'Floating button', 'hnrk-cookie-consent' ); ?>
		</p>
		<div class="hnrk-cookie-admin__trigger-options" style="margin-bottom:0;">
			<label class="hnrk-cookie-admin__radio">
				<input
					type="radio"
					name="hnrk_cookie_settings[manage_cookies_position]"
					value="right"
					<?php checked( $position, 'right' ); ?>
				>
				<?php esc_html_e( 'Right', 'hnrk-cookie-consent' ); ?>
			</label>
			<label class="hnrk-cookie-admin__radio">
				<input
					type="radio"
					name="hnrk_cookie_settings[manage_cookies_position]"
					value="left"
					<?php checked( $position, 'left' ); ?>
				>
				<?php esc_html_e( 'Left', 'hnrk-cookie-consent' ); ?>
			</label>
		</div>
	</div>

	<div class="hnrk-cookie-admin__shortcode-box">
		<p class="hnrk-cookie-admin__shortcode-title">
			<?php esc_html_e( 'Shortcode', 'hnrk-cookie-consent' ); ?>
		</p>
		<p class="hnrk-cookie-admin__shortcode-hint">
			<?php esc_html_e( 'Place a link anywhere on the site — e.g. on the privacy policy page:', 'hnrk-cookie-consent' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'Default:', 'hnrk-cookie-consent' ); ?>
			<code class="hnrk-cookie-admin__code">[hnrk_manage_cookies]</code>
		</p>
		<p>
			<?php esc_html_e( 'Custom text:', 'hnrk-cookie-consent' ); ?>
			<code class="hnrk-cookie-admin__code">[hnrk_manage_cookies text="Cookie settings"]</code>
		</p>
	</div>
	<?php
}

// ------------------------------------------------------------
// Admin menu
// ------------------------------------------------------------

/**
 * Register the admin menu page.
 */
function hnrk_cookie_add_menu() {
	add_menu_page(
		__( 'Cookie Consent', 'hnrk-cookie-consent' ),
		__( 'Cookie Consent', 'hnrk-cookie-consent' ),
		'manage_options',
		'hnrk-cookie-consent',
		'hnrk_cookie_settings_page',
		'dashicons-privacy',
		80
	);
}
add_action( 'admin_menu', 'hnrk_cookie_add_menu' );

// ------------------------------------------------------------
// Settings page output
// ------------------------------------------------------------

/**
 * Render the settings page.
 */
function hnrk_cookie_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap hnrk-cookie-admin">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<?php settings_errors( 'hnrk_cookie_settings' ); ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'hnrk_cookie_settings_group' ); ?>

			<div class="hnrk-cookie-admin__card">
				<h2><?php esc_html_e( 'Banner', 'hnrk-cookie-consent' ); ?></h2>
				<?php do_settings_sections( 'hnrk-cookie-consent-general' ); ?>
			</div>

			<div class="hnrk-cookie-admin__card">
				<h2><?php esc_html_e( 'Categories', 'hnrk-cookie-consent' ); ?></h2>
				<?php do_settings_sections( 'hnrk-cookie-consent-categories' ); ?>
			</div>

			<div class="hnrk-cookie-admin__card">
				<h2><?php esc_html_e( 'Reopen banner', 'hnrk-cookie-consent' ); ?></h2>
				<p class="hnrk-cookie-admin__card-desc">
					<?php esc_html_e( 'Choose how visitors can reopen the banner after making their choice.', 'hnrk-cookie-consent' ); ?>
				</p>
				<?php do_settings_sections( 'hnrk-cookie-consent-reopen' ); ?>
			</div>

			<?php submit_button( __( 'Save settings', 'hnrk-cookie-consent' ) ); ?>
		</form>
	</div>
	<?php
}
