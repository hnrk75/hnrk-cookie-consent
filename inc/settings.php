<?php
/**
 * Admin settings page for WPST Cookie Consent.
 *
 * Option key : wpst_cookie_settings
 * Settings group: wpst_cookie_settings_group
 *
 * @package WPST Cookie Consent
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
function wpst_cookie_register_settings() {
	register_setting(
		'wpst_cookie_settings_group',
		'wpst_cookie_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'wpst_cookie_sanitize_settings',
			'default'           => wpst_cookie_default_settings(),
		)
	);

	// --- Section: General ----------------------------------------
	add_settings_section(
		'wpst_cookie_section_general',
		'',
		'__return_false',
		'wpst-cookie-consent-general'
	);

	add_settings_field(
		'banner_title',
		__( 'Bannerns rubrik', 'wpst-cookie-consent' ),
		'wpst_cookie_field_banner_title',
		'wpst-cookie-consent-general',
		'wpst_cookie_section_general'
	);

	add_settings_field(
		'banner_text',
		__( 'Bannerns introduktionstext', 'wpst-cookie-consent' ),
		'wpst_cookie_field_banner_text',
		'wpst-cookie-consent-general',
		'wpst_cookie_section_general'
	);

	add_settings_field(
		'cookie_page_url',
		__( 'URL till cookiepolicysida', 'wpst-cookie-consent' ),
		'wpst_cookie_field_url',
		'wpst-cookie-consent-general',
		'wpst_cookie_section_general'
	);

	add_settings_field(
		'cookie_days',
		__( 'Cookiens giltighetstid (dagar)', 'wpst-cookie-consent' ),
		'wpst_cookie_field_days',
		'wpst-cookie-consent-general',
		'wpst_cookie_section_general'
	);

	add_settings_field(
		'banner_position',
		__( 'Bannerns position', 'wpst-cookie-consent' ),
		'wpst_cookie_field_banner_position',
		'wpst-cookie-consent-general',
		'wpst_cookie_section_general'
	);

	// --- Section: Categories -------------------------------------
	add_settings_section(
		'wpst_cookie_section_categories',
		'',
		'wpst_cookie_section_categories_description',
		'wpst-cookie-consent-categories'
	);

	add_settings_field(
		'necessary_description',
		__( 'Nödvändiga', 'wpst-cookie-consent' ),
		'wpst_cookie_field_necessary_description',
		'wpst-cookie-consent-categories',
		'wpst_cookie_section_categories'
	);

	add_settings_field(
		'enable_analytics',
		__( 'Analys / Statistik', 'wpst-cookie-consent' ),
		'wpst_cookie_field_analytics',
		'wpst-cookie-consent-categories',
		'wpst_cookie_section_categories'
	);

	add_settings_field(
		'enable_functional',
		__( 'Funktionella', 'wpst-cookie-consent' ),
		'wpst_cookie_field_functional',
		'wpst-cookie-consent-categories',
		'wpst_cookie_section_categories'
	);

	add_settings_field(
		'enable_marketing',
		__( 'Marknadsföring', 'wpst-cookie-consent' ),
		'wpst_cookie_field_marketing',
		'wpst-cookie-consent-categories',
		'wpst_cookie_section_categories'
	);

	add_settings_field(
		'enable_thirdparty',
		__( 'Tredjepartstjänster', 'wpst-cookie-consent' ),
		'wpst_cookie_field_thirdparty',
		'wpst-cookie-consent-categories',
		'wpst_cookie_section_categories'
	);

	// --- Section: Reopen -------------------------------------------
	add_settings_section(
		'wpst_cookie_section_reopen',
		'',
		'__return_false',
		'wpst-cookie-consent-reopen'
	);

	add_settings_field(
		'manage_cookies_trigger',
		__( 'Återöppna banner', 'wpst-cookie-consent' ),
		'wpst_cookie_field_trigger',
		'wpst-cookie-consent-reopen',
		'wpst_cookie_section_reopen'
	);
}
add_action( 'admin_init', 'wpst_cookie_register_settings' );

// ------------------------------------------------------------
// Default settings
// ------------------------------------------------------------

/**
 * Returns the default settings array.
 *
 * @return array
 */
function wpst_cookie_default_settings() {
	return array(
		'cookie_page_url'        => home_url( '/cookies/' ),
		'cookie_days'            => 365,
		'banner_title'           => 'Vi värnar om din integritet',
		'banner_text'            => 'Vi använder cookies för att förbättra din upplevelse, visa anpassat innehåll och analysera vår trafik. Genom att klicka på "Acceptera alla" samtycker du till vår användning av cookies. <a href="%s">Läs om cookies</a>',
		'enable_analytics'       => true,
		'enable_functional'      => false,
		'enable_marketing'       => false,
		'enable_thirdparty'      => false,
		'necessary_description'  => 'Krävs för att webbplatsen ska fungera korrekt. Dessa cookies lagrar inga personuppgifter.',
		'analytics_description'  => 'Hjälper oss förstå hur besökare interagerar med webbplatsen — antal besökare, avvisningsfrekvens, trafikkälla m.m.',
		'functional_description' => 'Möjliggör förbättrad funktionalitet som sparade inställningar, språkval eller inbäddat innehåll.',
		'marketing_description'  => 'Används för personanpassad annonsering och spårning via tredjepartstjänster som Google Ads och Meta.',
		'thirdparty_description'  => 'Cookies från inbäddade tjänster som YouTube, Vimeo eller Google Maps.',
		'banner_position'         => 'modal',
		'manage_cookies_trigger'  => 'none',
		'manage_cookies_label'    => 'Hantera cookies',
		'manage_cookies_position' => 'right',
	);
}

/**
 * Returns the saved settings merged with defaults.
 *
 * @return array
 */
function wpst_cookie_get_settings() {
	return wp_parse_args(
		(array) get_option( 'wpst_cookie_settings', array() ),
		wpst_cookie_default_settings()
	);
}

// ------------------------------------------------------------
// Sanitisation
// ------------------------------------------------------------

/**
 * Sanitise settings before saving.
 *
 * @param array $input Raw POST values.
 * @return array Sanitised values.
 */
function wpst_cookie_sanitize_settings( $input ) {
	$clean = wpst_cookie_default_settings();

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

	if ( ! empty( $input['cookie_page_url'] ) ) {
		$clean['cookie_page_url'] = esc_url_raw( $input['cookie_page_url'] );
	}

	if ( isset( $input['cookie_days'] ) ) {
		$days = absint( $input['cookie_days'] );
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

	$allowed_triggers = array( 'none', 'floating' );
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

	return $clean;
}

// ------------------------------------------------------------
// Field callbacks
// ------------------------------------------------------------

function wpst_cookie_field_banner_title() {
	$settings = wpst_cookie_get_settings();
	?>
	<div class="wpst-cookie-admin__category">
		<div class="wpst-cookie-admin__category-header">
			<p class="wpst-cookie-admin__hint">
				<?php esc_html_e( 'Rubriken som visas överst i cookiebannern.', 'wpst-cookie-consent' ); ?>
			</p>
			<button type="button" class="wpst-cookie-admin__edit-link" aria-expanded="false" aria-controls="banner_title_editor">
				<?php esc_html_e( 'Redigera', 'wpst-cookie-consent' ); ?>
			</button>
		</div>
		<div class="wpst-cookie-admin__category-body">
			<div class="wpst-cookie-admin__category-preview">
				<?php echo esc_html( $settings['banner_title'] ); ?>
			</div>
			<input
				type="text"
				name="wpst_cookie_settings[banner_title]"
				id="banner_title_editor"
				value="<?php echo esc_attr( $settings['banner_title'] ); ?>"
				class="wpst-cookie-admin__category-editor widefat"
				hidden
			>
		</div>
	</div>
	<?php
}

function wpst_cookie_field_banner_text() {
	$settings = wpst_cookie_get_settings();
	?>
	<div class="wpst-cookie-admin__category">
		<div class="wpst-cookie-admin__category-header">
			<p class="wpst-cookie-admin__hint">
				<?php esc_html_e( 'Introduktionstexten i bannern. Använd %s som platshållare där länken till cookiepolicysidan infogas.', 'wpst-cookie-consent' ); ?>
			</p>
			<button type="button" class="wpst-cookie-admin__edit-link" aria-expanded="false" aria-controls="banner_text_editor">
				<?php esc_html_e( 'Redigera', 'wpst-cookie-consent' ); ?>
			</button>
		</div>
		<div class="wpst-cookie-admin__category-body">
			<div class="wpst-cookie-admin__category-preview">
				<?php echo esc_html( $settings['banner_text'] ); ?>
			</div>
			<textarea
				name="wpst_cookie_settings[banner_text]"
				id="banner_text_editor"
				class="wpst-cookie-admin__category-editor"
				rows="4"
				hidden
			><?php echo esc_textarea( $settings['banner_text'] ); ?></textarea>
		</div>
	</div>
	<?php
}

function wpst_cookie_field_url() {
	$settings = wpst_cookie_get_settings();
	?>
	<div class="wpst-cookie-admin__category">
		<div class="wpst-cookie-admin__category-header">
			<p class="wpst-cookie-admin__hint">
				<?php esc_html_e( 'Länk som infogas i bannertexten via %s. Lämna tomt för standardsökvägen /cookies/.', 'wpst-cookie-consent' ); ?>
			</p>
			<button type="button" class="wpst-cookie-admin__edit-link" aria-expanded="false" aria-controls="cookie_page_url_editor">
				<?php esc_html_e( 'Redigera', 'wpst-cookie-consent' ); ?>
			</button>
		</div>
		<div class="wpst-cookie-admin__category-body">
			<div class="wpst-cookie-admin__category-preview">
				<?php echo esc_html( $settings['cookie_page_url'] ); ?>
			</div>
			<input
				type="url"
				name="wpst_cookie_settings[cookie_page_url]"
				id="cookie_page_url_editor"
				value="<?php echo esc_attr( $settings['cookie_page_url'] ); ?>"
				class="wpst-cookie-admin__category-editor widefat"
				placeholder="<?php echo esc_attr( home_url( '/cookies/' ) ); ?>"
				hidden
			>
		</div>
	</div>
	<?php
}

function wpst_cookie_field_banner_position() {
	$settings = wpst_cookie_get_settings();
	$pos      = $settings['banner_position'];
	?>
	<div class="wpst-cookie-admin__category">
		<div class="wpst-cookie-admin__category-header">
			<p class="wpst-cookie-admin__hint">
				<?php esc_html_e( 'Modal visas centrerat på sidan med mörk overlay. Remsa visas längs sidans nederkant utan overlay.', 'wpst-cookie-consent' ); ?>
			</p>
		</div>
		<div class="wpst-cookie-admin__trigger-options" style="margin-bottom:0">
			<label class="wpst-cookie-admin__radio">
				<input
					type="radio"
					name="wpst_cookie_settings[banner_position]"
					value="modal"
					<?php checked( $pos, 'modal' ); ?>
				>
				<?php esc_html_e( 'Modal (centrerad)', 'wpst-cookie-consent' ); ?>
			</label>
			<label class="wpst-cookie-admin__radio">
				<input
					type="radio"
					name="wpst_cookie_settings[banner_position]"
					value="bottom"
					<?php checked( $pos, 'bottom' ); ?>
				>
				<?php esc_html_e( 'Remsa (nederkant)', 'wpst-cookie-consent' ); ?>
			</label>
		</div>
	</div>
	<?php
}

function wpst_cookie_field_days() {
	$settings = wpst_cookie_get_settings();
	?>
	<div class="wpst-cookie-admin__category">
		<div class="wpst-cookie-admin__category-header">
			<p class="wpst-cookie-admin__hint">
				<?php esc_html_e( 'Hur länge besökarens samtyckesval sparas. Standard: 365 dagar (1 år).', 'wpst-cookie-consent' ); ?>
			</p>
			<button type="button" class="wpst-cookie-admin__edit-link" aria-expanded="false" aria-controls="cookie_days_editor">
				<?php esc_html_e( 'Redigera', 'wpst-cookie-consent' ); ?>
			</button>
		</div>
		<div class="wpst-cookie-admin__category-body">
			<div class="wpst-cookie-admin__category-preview">
				<?php echo esc_html( $settings['cookie_days'] ); ?> <?php esc_html_e( 'dagar', 'wpst-cookie-consent' ); ?>
			</div>
			<input
				type="number"
				name="wpst_cookie_settings[cookie_days]"
				id="cookie_days_editor"
				value="<?php echo esc_attr( $settings['cookie_days'] ); ?>"
				min="1"
				max="3650"
				class="wpst-cookie-admin__category-editor small-text"
				hidden
			>
		</div>
	</div>
	<?php
}

function wpst_cookie_section_categories_description() {
	echo '<p>' . esc_html__( 'Nödvändiga visas alltid. Aktivera de kategorier webbplatsen faktiskt använder — övriga visas inte i bannern.', 'wpst-cookie-consent' ) . '</p>';
}

function wpst_cookie_field_necessary_description() {
	$settings = wpst_cookie_get_settings();
	?>
	<div class="wpst-cookie-admin__category">
		<div class="wpst-cookie-admin__category-header">
			<span class="wpst-cookie-admin__checkbox">
				<?php esc_html_e( 'Alltid aktiv', 'wpst-cookie-consent' ); ?>
			</span>
			<button type="button" class="wpst-cookie-admin__edit-link" aria-expanded="false" aria-controls="necessary_editor">
				<?php esc_html_e( 'Redigera info', 'wpst-cookie-consent' ); ?>
			</button>
		</div>
		<div class="wpst-cookie-admin__category-body">
			<div class="wpst-cookie-admin__category-preview">
				<?php echo esc_html( $settings['necessary_description'] ); ?>
			</div>
			<textarea
				name="wpst_cookie_settings[necessary_description]"
				id="necessary_editor"
				class="wpst-cookie-admin__category-editor"
				rows="3"
				hidden
			><?php echo esc_textarea( $settings['necessary_description'] ); ?></textarea>
		</div>
	</div>
	<?php
}

function wpst_cookie_field_analytics() {
	$settings = wpst_cookie_get_settings();
	?>
	<div class="wpst-cookie-admin__category">
		<div class="wpst-cookie-admin__category-header">
			<label class="wpst-cookie-admin__checkbox">
				<input
					type="checkbox"
					name="wpst_cookie_settings[enable_analytics]"
					id="enable_analytics"
					value="1"
					<?php checked( $settings['enable_analytics'] ); ?>
				>
				<?php esc_html_e( 'Visa kategori', 'wpst-cookie-consent' ); ?>
			</label>

			<button
				type="button"
				class="wpst-cookie-admin__edit-link"
				aria-expanded="false"
				aria-controls="analytics_editor"
			>
				<?php esc_html_e( 'Redigera info', 'wpst-cookie-consent' ); ?>
			</button>
		</div>

		<div class="wpst-cookie-admin__category-body">
			<div class="wpst-cookie-admin__category-preview">
				<?php echo esc_html( $settings['analytics_description'] ); ?>
			</div>

			<textarea
				name="wpst_cookie_settings[analytics_description]"
				id="analytics_editor"
				class="wpst-cookie-admin__category-editor"
				rows="3"
				hidden
			><?php echo esc_textarea( $settings['analytics_description'] ); ?></textarea>
		</div>
	</div>
	<?php
}

function wpst_cookie_field_functional() {
	$settings = wpst_cookie_get_settings();
	?>
	<div class="wpst-cookie-admin__category">
		<div class="wpst-cookie-admin__category-header">
			<label class="wpst-cookie-admin__checkbox">
				<input
					type="checkbox"
					name="wpst_cookie_settings[enable_functional]"
					id="enable_functional"
					value="1"
					<?php checked( $settings['enable_functional'] ); ?>
				>
				<?php esc_html_e( 'Visa kategori', 'wpst-cookie-consent' ); ?>
			</label>
			<button type="button" class="wpst-cookie-admin__edit-link" aria-expanded="false" aria-controls="functional_editor">
				<?php esc_html_e( 'Redigera info', 'wpst-cookie-consent' ); ?>
			</button>
		</div>
		<div class="wpst-cookie-admin__category-body">
			<div class="wpst-cookie-admin__category-preview">
				<?php echo esc_html( $settings['functional_description'] ); ?>
			</div>
			<textarea
				name="wpst_cookie_settings[functional_description]"
				id="functional_editor"
				class="wpst-cookie-admin__category-editor"
				rows="3"
				hidden
			><?php echo esc_textarea( $settings['functional_description'] ); ?></textarea>
		</div>
	</div>
	<?php
}

function wpst_cookie_field_marketing() {
	$settings = wpst_cookie_get_settings();
	?>
	<div class="wpst-cookie-admin__category">
		<div class="wpst-cookie-admin__category-header">
			<label class="wpst-cookie-admin__checkbox">
				<input
					type="checkbox"
					name="wpst_cookie_settings[enable_marketing]"
					id="enable_marketing"
					value="1"
					<?php checked( $settings['enable_marketing'] ); ?>
				>
				<?php esc_html_e( 'Visa kategori', 'wpst-cookie-consent' ); ?>
			</label>
			<button type="button" class="wpst-cookie-admin__edit-link" aria-expanded="false" aria-controls="marketing_editor">
				<?php esc_html_e( 'Redigera info', 'wpst-cookie-consent' ); ?>
			</button>
		</div>
		<div class="wpst-cookie-admin__category-body">
			<div class="wpst-cookie-admin__category-preview">
				<?php echo esc_html( $settings['marketing_description'] ); ?>
			</div>
			<textarea
				name="wpst_cookie_settings[marketing_description]"
				id="marketing_editor"
				class="wpst-cookie-admin__category-editor"
				rows="3"
				hidden
			><?php echo esc_textarea( $settings['marketing_description'] ); ?></textarea>
		</div>
	</div>
	<?php
}

function wpst_cookie_field_thirdparty() {
	$settings = wpst_cookie_get_settings();
	?>
	<div class="wpst-cookie-admin__category">
		<div class="wpst-cookie-admin__category-header">
			<label class="wpst-cookie-admin__checkbox">
				<input
					type="checkbox"
					name="wpst_cookie_settings[enable_thirdparty]"
					id="enable_thirdparty"
					value="1"
					<?php checked( $settings['enable_thirdparty'] ); ?>
				>
				<?php esc_html_e( 'Visa kategori', 'wpst-cookie-consent' ); ?>
			</label>
			<button type="button" class="wpst-cookie-admin__edit-link" aria-expanded="false" aria-controls="thirdparty_editor">
				<?php esc_html_e( 'Redigera info', 'wpst-cookie-consent' ); ?>
			</button>
		</div>
		<div class="wpst-cookie-admin__category-body">
			<div class="wpst-cookie-admin__category-preview">
				<?php echo esc_html( $settings['thirdparty_description'] ); ?>
			</div>
			<textarea
				name="wpst_cookie_settings[thirdparty_description]"
				id="thirdparty_editor"
				class="wpst-cookie-admin__category-editor"
				rows="3"
				hidden
			><?php echo esc_textarea( $settings['thirdparty_description'] ); ?></textarea>
		</div>
	</div>
	<?php
}

function wpst_cookie_field_trigger() {
	$settings = wpst_cookie_get_settings();
	$trigger  = $settings['manage_cookies_trigger'];
	$position = $settings['manage_cookies_position'];
	?>
	<div class="wpst-cookie-admin__trigger-options">
		<label class="wpst-cookie-admin__radio">
			<input
				type="radio"
				name="wpst_cookie_settings[manage_cookies_trigger]"
				value="none"
				<?php checked( $trigger, 'none' ); ?>
			>
			<?php esc_html_e( 'Ingen', 'wpst-cookie-consent' ); ?>
		</label>
		<label class="wpst-cookie-admin__radio">
			<input
				type="radio"
				name="wpst_cookie_settings[manage_cookies_trigger]"
				value="floating"
				<?php checked( $trigger, 'floating' ); ?>
			>
			<?php esc_html_e( 'Flytande knapp', 'wpst-cookie-consent' ); ?>
		</label>
	</div>

	<div class="wpst-cookie-admin__shortcode-box" id="trigger_label_wrapper" <?php echo 'floating' !== $trigger ? 'hidden' : ''; ?>>
		<p class="wpst-cookie-admin__shortcode-title">
			<?php esc_html_e( 'Flytande knapp', 'wpst-cookie-consent' ); ?>
		</p>
		<p class="wpst-cookie-admin__shortcode-hint">
			<?php esc_html_e( 'En liten knapp visas i sidans nedre hörn så att cookieinställningarna kan öppna igen. Texten på knappen är valfri — standard är "Hantera cookies".', 'wpst-cookie-consent' ); ?>
		</p>
		<label for="manage_cookies_label">
			<?php esc_html_e( 'Knapptext', 'wpst-cookie-consent' ); ?>
		</label>
		<input
			type="text"
			name="wpst_cookie_settings[manage_cookies_label]"
			id="manage_cookies_label"
			value="<?php echo esc_attr( $settings['manage_cookies_label'] ); ?>"
			class="regular-text"
			placeholder="<?php esc_attr_e( 'Hantera cookies', 'wpst-cookie-consent' ); ?>"
		>
		<div class="wpst-cookie-admin__trigger-options" style="margin-top:0.75rem;margin-bottom:0;">
			<label class="wpst-cookie-admin__radio">
				<input
					type="radio"
					name="wpst_cookie_settings[manage_cookies_position]"
					value="right"
					<?php checked( $position, 'right' ); ?>
				>
				<?php esc_html_e( 'Höger', 'wpst-cookie-consent' ); ?>
			</label>
			<label class="wpst-cookie-admin__radio">
				<input
					type="radio"
					name="wpst_cookie_settings[manage_cookies_position]"
					value="left"
					<?php checked( $position, 'left' ); ?>
				>
				<?php esc_html_e( 'Vänster', 'wpst-cookie-consent' ); ?>
			</label>
		</div>
	</div>

	<div class="wpst-cookie-admin__shortcode-box">
		<p class="wpst-cookie-admin__shortcode-title">
			<?php esc_html_e( 'Shortcode', 'wpst-cookie-consent' ); ?>
		</p>
		<p class="wpst-cookie-admin__shortcode-hint">
			<?php esc_html_e( 'Lägg en länk var som helst på webbplatsen — t.ex. på integritetssidan:', 'wpst-cookie-consent' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'Standard:', 'wpst-cookie-consent' ); ?>
			<code class="wpst-cookie-admin__code">[wpst_manage_cookies]</code>
		</p>
		<p>
			<?php esc_html_e( 'Anpassad text:', 'wpst-cookie-consent' ); ?>
			<code class="wpst-cookie-admin__code">[wpst_manage_cookies text="Hantera cookies"]</code>
		</p>
	</div>
	<?php
}

// ------------------------------------------------------------
// Admin menu
// ------------------------------------------------------------

/**
 * Register the options page under Settings.
 */
function wpst_cookie_add_menu() {
	add_menu_page(
		__( 'Cookiesamtycke', 'wpst-cookie-consent' ),
		__( 'Cookiesamtycke', 'wpst-cookie-consent' ),
		'manage_options',
		'wpst-cookie-consent',
		'wpst_cookie_settings_page',
		'dashicons-privacy',
		80
	);
}
add_action( 'admin_menu', 'wpst_cookie_add_menu' );

// ------------------------------------------------------------
// Settings page output
// ------------------------------------------------------------

/**
 * Render the settings page.
 */
function wpst_cookie_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap wpst-cookie-admin">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<?php settings_errors( 'wpst_cookie_settings' ); ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'wpst_cookie_settings_group' ); ?>

			<div class="wpst-cookie-admin__card">
				<h2><?php esc_html_e( 'Bannern', 'wpst-cookie-consent' ); ?></h2>
				<?php do_settings_sections( 'wpst-cookie-consent-general' ); ?>
			</div>

			<div class="wpst-cookie-admin__card">
				<h2><?php esc_html_e( 'Kategorier', 'wpst-cookie-consent' ); ?></h2>
				<?php do_settings_sections( 'wpst-cookie-consent-categories' ); ?>
			</div>

			<div class="wpst-cookie-admin__card">
				<h2><?php esc_html_e( 'Återöppna banner', 'wpst-cookie-consent' ); ?></h2>
				<p class="wpst-cookie-admin__card-desc">
					<?php esc_html_e( 'Välj hur besökare kan öppna bannern igen efter att ha gjort sitt val.', 'wpst-cookie-consent' ); ?>
				</p>
				<?php do_settings_sections( 'wpst-cookie-consent-reopen' ); ?>
			</div>

			<?php submit_button( __( 'Spara inställningar', 'wpst-cookie-consent' ) ); ?>
		</form>
	</div>
	<?php
}
