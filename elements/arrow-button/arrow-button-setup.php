<?php
/**
 * Arrow Button - Setup
 *
 * Enqueues a small admin script that builds the Salient-native device-group
 * UI (Padding / Margin / Transform toggle + grid) for this element's WPBakery
 * panel. Salient only wires those groups for vc_row/vc_section/vc_column, so we
 * reproduce the exact wrap-and-toggle for our own shortcode. All styling and the
 * nectar_numerical / range-slider / angle / constrain widgets are already
 * provided globally by Salient Core.
 *
 * @package CPH_Elements
 * @since   1.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'cph_arrow_button_admin_enqueue' ) ) {
	/**
	 * Enqueue the Arrow Button admin script on post edit screens.
	 *
	 * @since 1.6.0
	 *
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	function cph_arrow_button_admin_enqueue( $hook ) {
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		wp_enqueue_script(
			'cph-arrow-button-admin',
			cph_element_url( 'arrow-button' ) . 'assets/js/arrow-button-admin.js',
			array( 'jquery' ),
			CPH_ELEMENTS_VERSION,
			true
		);
	}
	add_action( 'admin_enqueue_scripts', 'cph_arrow_button_admin_enqueue' );
}
