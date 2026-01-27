<?php
/**
 * File Download Card - Setup
 *
 * Registers the custom WPBakery param type (fdc_file_picker),
 * admin scripts, and AJAX handler for attachment filename lookup.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * ==========================================================================
 * CUSTOM WPBAKERY PARAM TYPE: fdc_file_picker
 * ==========================================================================
 */

if ( ! function_exists( 'cph_fdc_register_file_picker_param' ) ) {
	/**
	 * Register the custom fdc_file_picker param type with WPBakery.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_fdc_register_file_picker_param() {
		if ( function_exists( 'vc_add_shortcode_param' ) ) {
			vc_add_shortcode_param( 'fdc_file_picker', 'cph_fdc_file_picker_param' );
		}
	}
	add_action( 'vc_before_init', 'cph_fdc_register_file_picker_param' );
}

if ( ! function_exists( 'cph_fdc_file_picker_param' ) ) {
	/**
	 * Render the custom file picker param field.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $settings Param settings from WPBakery.
	 * @param string $value    Current param value (attachment ID).
	 * @return string HTML output for the param field.
	 */
	function cph_fdc_file_picker_param( $settings, $value ) {
		$output            = '';
		$select_file_class = '';
		$remove_file_class = ' hidden';
		$attachment_url    = '';
		$file_name         = '';

		if ( ! empty( $value ) ) {
			$attachment_url = wp_get_attachment_url( $value );
			$file_name      = basename( get_attached_file( $value ) );
			if ( $attachment_url ) {
				$select_file_class = ' hidden';
				$remove_file_class = '';
			}
		}

		$output .= '<div class="fdc-file-picker-wrapper">';
		$output .= '<input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value fdc-file-picker-value" value="' . esc_attr( $value ) . '" />';
		$output .= '<div class="fdc-file-preview' . ( $attachment_url ? '' : ' hidden' ) . '">';
		$output .= '<span class="fdc-file-name">' . esc_html( $file_name ) . '</span>';
		$output .= '</div>';
		$output .= '<button type="button" class="button fdc-select-file' . esc_attr( $select_file_class ) . '">' . esc_html__( 'Select File', 'cph-elements' ) . '</button>';
		$output .= '<button type="button" class="button fdc-remove-file' . esc_attr( $remove_file_class ) . '">' . esc_html__( 'Remove', 'cph-elements' ) . '</button>';
		$output .= '</div>';

		// Inline JavaScript for the media uploader.
		$output .= '
		<script type="text/javascript">
		(function($) {
			if (typeof window.fdcFilePickerInit === "undefined") {
				window.fdcFilePickerInit = true;

				$(document).on("click", ".fdc-select-file", function(e) {
					e.preventDefault();
					var $wrapper = $(this).closest(".fdc-file-picker-wrapper");
					var $input = $wrapper.find(".fdc-file-picker-value");
					var $preview = $wrapper.find(".fdc-file-preview");
					var $fileName = $wrapper.find(".fdc-file-name");
					var $selectBtn = $wrapper.find(".fdc-select-file");
					var $removeBtn = $wrapper.find(".fdc-remove-file");

					var frame = wp.media({
						title: "' . esc_js( __( 'Select File', 'cph-elements' ) ) . '",
						button: { text: "' . esc_js( __( 'Use This File', 'cph-elements' ) ) . '" },
						multiple: false
					});

					frame.on("select", function() {
						var attachment = frame.state().get("selection").first().toJSON();
						$input.val(attachment.id).trigger("change");
						$fileName.text(attachment.filename);
						$preview.removeClass("hidden");
						$selectBtn.addClass("hidden");
						$removeBtn.removeClass("hidden");
					});

					frame.open();
				});

				$(document).on("click", ".fdc-remove-file", function(e) {
					e.preventDefault();
					var $wrapper = $(this).closest(".fdc-file-picker-wrapper");
					var $input = $wrapper.find(".fdc-file-picker-value");
					var $preview = $wrapper.find(".fdc-file-preview");
					var $selectBtn = $wrapper.find(".fdc-select-file");
					var $removeBtn = $(this);

					$input.val("").trigger("change");
					$preview.addClass("hidden");
					$selectBtn.removeClass("hidden");
					$removeBtn.addClass("hidden");
				});
			}
		})(jQuery);
		</script>
		<style>
		.fdc-file-picker-wrapper { display: flex; align-items: center; gap: 10px; }
		.fdc-file-preview { background: #f0f0f0; padding: 8px 12px; border-radius: 3px; }
		.fdc-file-name { font-weight: 500; }
		.fdc-file-picker-wrapper .hidden { display: none !important; }
		</style>';

		return $output;
	}
}

/*
 * ==========================================================================
 * ADMIN SCRIPTS
 * ==========================================================================
 */

if ( ! function_exists( 'cph_fdc_admin_enqueue_scripts' ) ) {
	/**
	 * Enqueue admin scripts and localize nonce for the AJAX handler.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook The current admin page hook.
	 * @return void
	 */
	function cph_fdc_admin_enqueue_scripts( $hook ) {

		// Only load on post edit screens.
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		// Localize admin nonce for AJAX handler.
		wp_localize_script( 'cph-wpbakery-admin', 'fdcAdmin', array(
			'nonce' => wp_create_nonce( 'fdc_admin_nonce' ),
		) );
	}
	add_action( 'admin_enqueue_scripts', 'cph_fdc_admin_enqueue_scripts' );
}

/*
 * ==========================================================================
 * AJAX HANDLER: Get Attachment Filename
 * ==========================================================================
 */

if ( ! function_exists( 'cph_fdc_ajax_get_attachment_filename' ) ) {
	/**
	 * AJAX handler to get attachment filename by ID.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_fdc_ajax_get_attachment_filename() {

		// Verify nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'fdc_admin_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce', 'cph-elements' ) ) );
		}

		// Check for attachment ID.
		if ( ! isset( $_POST['attachment_id'] ) || empty( $_POST['attachment_id'] ) ) {
			wp_send_json_error( array( 'message' => __( 'No attachment ID provided', 'cph-elements' ) ) );
		}

		$attachment_id = absint( $_POST['attachment_id'] );
		$file_path     = get_attached_file( $attachment_id );

		if ( ! $file_path ) {
			wp_send_json_error( array( 'message' => __( 'Attachment not found', 'cph-elements' ) ) );
		}

		$filename = basename( $file_path );

		wp_send_json_success( array(
			'filename' => $filename,
		) );
	}
	add_action( 'wp_ajax_fdc_get_attachment_filename', 'cph_fdc_ajax_get_attachment_filename' );
}
