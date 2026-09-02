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
		__( 'Bannerns rubrik', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_banner_title',
		'hnrk-cookie-consent-general',
		'hnrk_cookie_section_general'
	);

	add_settings_field(
		'banner_text',
		__( 'Bannerns introduktionstext', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_banner_text',
		'hnrk-cookie-consent-general',
		'hnrk_cookie_section_general'
	);

	add_settings_field(
		'cookie_page_url',
		__( 'URL till cookiepolicysida', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_url',
		'hnrk-cookie-consent-general',
		'hnrk_cookie_section_general'
	);

	add_settings_field(
		'cookie_days',
		__( 'Cookiens giltighetstid (dagar)', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_days',
		'hnrk-cookie-consent-general',
		'hnrk_cookie_section_general'
	);

	add_settings_field(
		'banner_position',
		__( 'Bannerns position', 'hnrk-cookie-consent' ),
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
		__( 'Nödvändiga', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_necessary_description',
		'hnrk-cookie-consent-categories',
		'hnrk_cookie_section_categories'
	);

	add_settings_field(
		'enable_analytics',
		__( 'Analys / Statistik', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_analytics',
		'hnrk-cookie-consent-categories',
		'hnrk_cookie_section_categories'
	);

	add_settings_field(
		'enable_functional',
		__( 'Funktionella', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_functional',
		'hnrk-cookie-consent-categories',
		'hnrk_cookie_section_categories'
	);

	add_settings_field(
		'enable_marketing',
		__( 'Marknadsföring', 'hnrk-cookie-consent' ),
		'hnrk_cookie_field_marketing',
		'hnrk-cookie-consent-categories',
		'hnrk_cookie_section_categories'
	);

	add_settings_field(
		'enable_thirdparty',
		__( 'Tredjepartstjänster', 'hnrk-cookie-consent' ),
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
		__( 'Återöppna banner', 'hnrk-cookie-consent' ),
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
 * Returns the default settings array.
 *
 * @return array
 */
function hnrk_cookie_default_settings() {
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
function hnrk_cookie_get_settings() {
	return wp_parse_args(
		(array) get_option( 'hnrk_cookie_settings', array() ),
		hnrk_cookie_default_settings()
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

function hnrk_cookie_field_banner_title() {
	$settings = hnrk_cookie_get_settings();
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<p class="hnrk-cookie-admin__hint">
				<?php esc_html_e( 'Rubriken som visas överst i cookiebannern.', 'hnrk-cookie-consent' ); ?>
			</p>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="banner_title_editor">
				<?php esc_html_e( 'Redigera', 'hnrk-cookie-consent' ); ?>
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

function hnrk_cookie_field_banner_text() {
	$settings = hnrk_cookie_get_settings();
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<p class="hnrk-cookie-admin__hint">
				<?php esc_html_e( 'Introduktionstexten i bannern. Använd %s som platshållare där länken till cookiepolicysidan infogas.', 'hnrk-cookie-consent' ); ?>
			</p>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="banner_text_editor">
				<?php esc_html_e( 'Redigera', 'hnrk-cookie-consent' ); ?>
			</button>
		</div>
		<div class="hnrk-cookie-admin__category-body">
			<div class="hnrk-cookie-admin__category-preview">
				<?php
				echo wp_kses(
					sprintf( $settings['banner_text'], '#' ),
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

function hnrk_cookie_field_url() {
	$settings = hnrk_cookie_get_settings();
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<p class="hnrk-cookie-admin__hint">
				<?php esc_html_e( 'Länk som infogas i bannertexten via %s. Lämna tomt för standardsökvägen /cookies/.', 'hnrk-cookie-consent' ); ?>
			</p>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="cookie_page_url_editor">
				<?php esc_html_e( 'Redigera', 'hnrk-cookie-consent' ); ?>
			</button>
		</div>
		<div class="hnrk-cookie-admin__category-body">
			<div class="hnrk-cookie-admin__category-preview">
				<?php echo esc_html( $settings['cookie_page_url'] ); ?>
			</div>
			<input
				type="url"
				name="hnrk_cookie_settings[cookie_page_url]"
				id="cookie_page_url_editor"
				value="<?php echo esc_attr( $settings['cookie_page_url'] ); ?>"
				class="hnrk-cookie-admin__category-editor widefat"
				placeholder="<?php echo esc_attr( home_url( '/cookies/' ) ); ?>"
				hidden
			>
		</div>
	</div>
	<?php
}

function hnrk_cookie_field_banner_position() {
	$settings = hnrk_cookie_get_settings();
	$pos      = $settings['banner_position'];
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<p class="hnrk-cookie-admin__hint">
				<?php esc_html_e( 'Modal visas centrerat på sidan med mörk overlay. Remsa visas längs sidans nederkant utan overlay.', 'hnrk-cookie-consent' ); ?>
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
				<?php esc_html_e( 'Modal (centrerad)', 'hnrk-cookie-consent' ); ?>
			</label>
			<label class="hnrk-cookie-admin__radio">
				<input
					type="radio"
					name="hnrk_cookie_settings[banner_position]"
					value="bottom"
					<?php checked( $pos, 'bottom' ); ?>
				>
				<?php esc_html_e( 'Remsa (nederkant)', 'hnrk-cookie-consent' ); ?>
			</label>
		</div>
	</div>
	<?php
}

function hnrk_cookie_field_days() {
	$settings = hnrk_cookie_get_settings();
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<p class="hnrk-cookie-admin__hint">
				<?php esc_html_e( 'Hur länge besökarens samtyckesval sparas. Standard: 365 dagar (1 år).', 'hnrk-cookie-consent' ); ?>
			</p>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="cookie_days_editor">
				<?php esc_html_e( 'Redigera', 'hnrk-cookie-consent' ); ?>
			</button>
		</div>
		<div class="hnrk-cookie-admin__category-body">
			<div class="hnrk-cookie-admin__category-preview">
				<?php echo esc_html( $settings['cookie_days'] ); ?> <?php esc_html_e( 'dagar', 'hnrk-cookie-consent' ); ?>
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

function hnrk_cookie_section_categories_description() {
	echo '<p>' . esc_html__( 'Nödvändiga visas alltid. Aktivera de kategorier webbplatsen faktiskt använder — övriga visas inte i bannern.', 'hnrk-cookie-consent' ) . '</p>';
}

function hnrk_cookie_field_necessary_description() {
	$settings = hnrk_cookie_get_settings();
	?>
	<div class="hnrk-cookie-admin__category">
		<div class="hnrk-cookie-admin__category-header">
			<span class="hnrk-cookie-admin__checkbox">
				<?php esc_html_e( 'Alltid aktiv', 'hnrk-cookie-consent' ); ?>
			</span>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="necessary_editor">
				<?php esc_html_e( 'Redigera info', 'hnrk-cookie-consent' ); ?>
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
				<?php esc_html_e( 'Visa kategori', 'hnrk-cookie-consent' ); ?>
			</label>

			<button
				type="button"
				class="hnrk-cookie-admin__edit-link"
				aria-expanded="false"
				aria-controls="analytics_editor"
			>
				<?php esc_html_e( 'Redigera info', 'hnrk-cookie-consent' ); ?>
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
				<?php esc_html_e( 'Visa kategori', 'hnrk-cookie-consent' ); ?>
			</label>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="functional_editor">
				<?php esc_html_e( 'Redigera info', 'hnrk-cookie-consent' ); ?>
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
				<?php esc_html_e( 'Visa kategori', 'hnrk-cookie-consent' ); ?>
			</label>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="marketing_editor">
				<?php esc_html_e( 'Redigera info', 'hnrk-cookie-consent' ); ?>
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
				<?php esc_html_e( 'Visa kategori', 'hnrk-cookie-consent' ); ?>
			</label>
			<button type="button" class="hnrk-cookie-admin__edit-link" aria-expanded="false" aria-controls="thirdparty_editor">
				<?php esc_html_e( 'Redigera info', 'hnrk-cookie-consent' ); ?>
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

function hnrk_cookie_field_trigger() {
	$settings = hnrk_cookie_get_settings();
	$trigger  = $settings['manage_cookies_trigger'];
	$position = $settings['manage_cookies_position'];
	?>
	<div class="hnrk-cookie-admin__trigger-options">
		<label class="hnrk-cookie-admin__radio">
			<input
				type="radio"
				name="hnrk_cookie_settings[manage_cookies_trigger]"
				value="none"
				<?php checked( $trigger, 'none' ); ?>
			>
			<?php esc_html_e( 'Ingen', 'hnrk-cookie-consent' ); ?>
		</label>
		<label class="hnrk-cookie-admin__radio">
			<input
				type="radio"
				name="hnrk_cookie_settings[manage_cookies_trigger]"
				value="floating"
				<?php checked( $trigger, 'floating' ); ?>
			>
			<?php esc_html_e( 'Flytande knapp', 'hnrk-cookie-consent' ); ?>
		</label>
	</div>

	<div class="hnrk-cookie-admin__shortcode-box" id="trigger_label_wrapper" <?php echo 'floating' !== $trigger ? 'hidden' : ''; ?>>
		<p class="hnrk-cookie-admin__shortcode-title">
			<?php esc_html_e( 'Flytande knapp', 'hnrk-cookie-consent' ); ?>
		</p>
		<p class="hnrk-cookie-admin__shortcode-hint">
			<?php esc_html_e( 'En liten knapp visas i sidans nedre hörn så att cookieinställningarna kan öppna igen. Texten på knappen är valfri — standard är "Hantera cookies".', 'hnrk-cookie-consent' ); ?>
		</p>
		<label for="manage_cookies_label">
			<?php esc_html_e( 'Knapptext', 'hnrk-cookie-consent' ); ?>
		</label>
		<input
			type="text"
			name="hnrk_cookie_settings[manage_cookies_label]"
			id="manage_cookies_label"
			value="<?php echo esc_attr( $settings['manage_cookies_label'] ); ?>"
			class="regular-text"
			placeholder="<?php esc_attr_e( 'Hantera cookies', 'hnrk-cookie-consent' ); ?>"
		>
		<div class="hnrk-cookie-admin__trigger-options" style="margin-top:0.75rem;margin-bottom:0;">
			<label class="hnrk-cookie-admin__radio">
				<input
					type="radio"
					name="hnrk_cookie_settings[manage_cookies_position]"
					value="right"
					<?php checked( $position, 'right' ); ?>
				>
				<?php esc_html_e( 'Höger', 'hnrk-cookie-consent' ); ?>
			</label>
			<label class="hnrk-cookie-admin__radio">
				<input
					type="radio"
					name="hnrk_cookie_settings[manage_cookies_position]"
					value="left"
					<?php checked( $position, 'left' ); ?>
				>
				<?php esc_html_e( 'Vänster', 'hnrk-cookie-consent' ); ?>
			</label>
		</div>
	</div>

	<div class="hnrk-cookie-admin__shortcode-box">
		<p class="hnrk-cookie-admin__shortcode-title">
			<?php esc_html_e( 'Shortcode', 'hnrk-cookie-consent' ); ?>
		</p>
		<p class="hnrk-cookie-admin__shortcode-hint">
			<?php esc_html_e( 'Lägg en länk var som helst på webbplatsen — t.ex. på integritetssidan:', 'hnrk-cookie-consent' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'Standard:', 'hnrk-cookie-consent' ); ?>
			<code class="hnrk-cookie-admin__code">[hnrk_manage_cookies]</code>
		</p>
		<p>
			<?php esc_html_e( 'Anpassad text:', 'hnrk-cookie-consent' ); ?>
			<code class="hnrk-cookie-admin__code">[hnrk_manage_cookies text="Hantera cookies"]</code>
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
function hnrk_cookie_add_menu() {
	add_menu_page(
		__( 'Cookiesamtycke', 'hnrk-cookie-consent' ),
		__( 'Cookiesamtycke', 'hnrk-cookie-consent' ),
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
				<h2><?php esc_html_e( 'Bannern', 'hnrk-cookie-consent' ); ?></h2>
				<?php do_settings_sections( 'hnrk-cookie-consent-general' ); ?>
			</div>

			<div class="hnrk-cookie-admin__card">
				<h2><?php esc_html_e( 'Kategorier', 'hnrk-cookie-consent' ); ?></h2>
				<?php do_settings_sections( 'hnrk-cookie-consent-categories' ); ?>
			</div>

			<div class="hnrk-cookie-admin__card">
				<h2><?php esc_html_e( 'Återöppna banner', 'hnrk-cookie-consent' ); ?></h2>
				<p class="hnrk-cookie-admin__card-desc">
					<?php esc_html_e( 'Välj hur besökare kan öppna bannern igen efter att ha gjort sitt val.', 'hnrk-cookie-consent' ); ?>
				</p>
				<?php do_settings_sections( 'hnrk-cookie-consent-reopen' ); ?>
			</div>

			<?php submit_button( __( 'Spara inställningar', 'hnrk-cookie-consent' ) ); ?>
		</form>
	</div>
	<?php
}
