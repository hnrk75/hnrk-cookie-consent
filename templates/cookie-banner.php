<?php
/**
 * Cookie consent banner template.
 *
 * Rendered in wp_footer via wpst_cookie_banner().
 *
 * @package WPST Cookie Consent
 */

$settings        = wpst_cookie_get_settings();
$cookie_page_url = apply_filters( 'wpst_cookie_page_url', $settings['cookie_page_url'] );
$banner_class    = 'bottom' === $settings['banner_position'] ? 'cookie-banner cookie-banner--bottom' : 'cookie-banner';
?>

<div class="cookie-overlay" id="cookie-overlay" hidden aria-hidden="true"></div>

<div
	class="<?php echo esc_attr( $banner_class ); ?>"
	id="cookie-banner"
	hidden
	role="dialog"
	aria-modal="true"
	aria-labelledby="cookie-banner-title"
	aria-describedby="cookie-banner-desc"
>
	<div class="cookie-banner__main">
		<h2 class="cookie-banner__title" id="cookie-banner-title">
			<?php echo esc_html( $settings['banner_title'] ); ?>
		</h2>

		<p class="cookie-banner__text" id="cookie-banner-desc">
			<?php
			printf(
				wp_kses(
					$settings['banner_text'],
					array( 'a' => array( 'href' => array() ) )
				),
				esc_url( $cookie_page_url )
			);
			?>
		</p>

		<div class="cookie-banner__categories">

			<div class="cookie-category">
				<label class="cookie-category__label">
					<span class="cookie-category__title">
						<?php esc_html_e( 'Nödvändiga', 'wpst-cookie-consent' ); ?>
					</span>
					<input
						type="checkbox"
						data-consent-toggle="necessary"
						checked
						disabled
						aria-label="<?php esc_attr_e( 'Nödvändiga cookies, alltid aktiva', 'wpst-cookie-consent' ); ?>"
						aria-describedby="desc-necessary"
					>
				</label>
				<p id="desc-necessary"><?php echo esc_html( $settings['necessary_description'] ); ?></p>
			</div>

			<?php if ( $settings['enable_analytics'] ) : ?>
			<div class="cookie-category">
				<label class="cookie-category__label">
					<span class="cookie-category__title">
						<?php esc_html_e( 'Analys', 'wpst-cookie-consent' ); ?>
					</span>
					<input
						type="checkbox"
						data-consent-toggle="analytics"
						aria-describedby="desc-analytics"
					>
				</label>
				<p id="desc-analytics"><?php echo esc_html( $settings['analytics_description'] ); ?></p>
			</div>
			<?php endif; ?>

			<?php if ( $settings['enable_functional'] ) : ?>
			<div class="cookie-category">
				<label class="cookie-category__label">
					<span class="cookie-category__title">
						<?php esc_html_e( 'Funktionella', 'wpst-cookie-consent' ); ?>
					</span>
					<input
						type="checkbox"
						data-consent-toggle="functional"
						aria-describedby="desc-functional"
					>
				</label>
				<p id="desc-functional"><?php echo esc_html( $settings['functional_description'] ); ?></p>
			</div>
			<?php endif; ?>

			<?php if ( $settings['enable_marketing'] ) : ?>
			<div class="cookie-category">
				<label class="cookie-category__label">
					<span class="cookie-category__title">
						<?php esc_html_e( 'Marknadsföring', 'wpst-cookie-consent' ); ?>
					</span>
					<input
						type="checkbox"
						data-consent-toggle="marketing"
						aria-describedby="desc-marketing"
					>
				</label>
				<p id="desc-marketing"><?php echo esc_html( $settings['marketing_description'] ); ?></p>
			</div>
			<?php endif; ?>

			<?php if ( $settings['enable_thirdparty'] ) : ?>
			<div class="cookie-category">
				<label class="cookie-category__label">
					<span class="cookie-category__title">
						<?php esc_html_e( 'Tredjepartstjänster', 'wpst-cookie-consent' ); ?>
					</span>
					<input
						type="checkbox"
						data-consent-toggle="thirdparty"
						aria-describedby="desc-thirdparty"
					>
				</label>
				<p id="desc-thirdparty"><?php echo esc_html( $settings['thirdparty_description'] ); ?></p>
			</div>
			<?php endif; ?>

		</div>

		<div class="cookie-banner__actions">
			<button type="button" class="cookie-btn" data-consent-reject>
				<?php esc_html_e( 'Avvisa alla', 'wpst-cookie-consent' ); ?>
			</button>
			<button type="button" class="cookie-btn" data-consent-save>
				<?php esc_html_e( 'Spara inställningar', 'wpst-cookie-consent' ); ?>
			</button>
			<button type="button" class="cookie-btn" data-consent-accept>
				<?php esc_html_e( 'Acceptera alla', 'wpst-cookie-consent' ); ?>
			</button>
		</div>
	</div>
</div>
