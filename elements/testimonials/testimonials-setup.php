<?php
/**
 * CPH Testimonials - Setup
 *
 * Registers the cph_testimonial custom post type and meta boxes.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * ==========================================================================
 * CPH TESTIMONIALS - CUSTOM POST TYPE
 * ==========================================================================
 */

if ( ! function_exists( 'cph_register_testimonial_post_type' ) ) {
	/**
	 * Register the Testimonial custom post type.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_register_testimonial_post_type() {
		$labels = array(
			'name'               => _x( 'Testimonials', 'Post type general name', 'cph-elements' ),
			'singular_name'      => _x( 'Testimonial', 'Post type singular name', 'cph-elements' ),
			'menu_name'          => _x( 'Testimonials', 'Admin Menu text', 'cph-elements' ),
			'add_new'            => __( 'Add New', 'cph-elements' ),
			'add_new_item'       => __( 'Add New Testimonial', 'cph-elements' ),
			'edit_item'          => __( 'Edit Testimonial', 'cph-elements' ),
			'new_item'           => __( 'New Testimonial', 'cph-elements' ),
			'view_item'          => __( 'View Testimonial', 'cph-elements' ),
			'search_items'       => __( 'Search Testimonials', 'cph-elements' ),
			'not_found'          => __( 'No testimonials found', 'cph-elements' ),
			'not_found_in_trash' => __( 'No testimonials found in Trash', 'cph-elements' ),
			'all_items'          => __( 'All Testimonials', 'cph-elements' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'testimonial' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 21,
			'menu_icon'          => 'dashicons-format-quote',
			'supports'           => array( 'title', 'page-attributes' ),
			'show_in_rest'       => true,
		);

		register_post_type( 'cph_testimonial', $args );
	}
	add_action( 'init', 'cph_register_testimonial_post_type' );
}

/*
 * ==========================================================================
 * CPH TESTIMONIALS - META BOXES
 * ==========================================================================
 */

if ( ! function_exists( 'cph_testimonial_add_meta_boxes' ) ) {
	/**
	 * Add meta box for testimonial details.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_testimonial_add_meta_boxes() {
		add_meta_box(
			'cph_testimonial_details',
			__( 'Testimonial Details', 'cph-elements' ),
			'cph_testimonial_meta_box_callback',
			'cph_testimonial',
			'normal',
			'high'
		);
	}
	add_action( 'add_meta_boxes', 'cph_testimonial_add_meta_boxes' );
}

if ( ! function_exists( 'cph_testimonial_meta_box_callback' ) ) {
	/**
	 * Render the testimonial meta box.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The post object.
	 * @return void
	 */
	function cph_testimonial_meta_box_callback( $post ) {
		wp_nonce_field( 'cph_testimonial_meta_box', 'cph_testimonial_meta_box_nonce' );

		$quote = get_post_meta( $post->ID, '_cph_testimonial_quote', true );
		?>
		<p>
			<label for="cph_testimonial_quote">
				<strong><?php esc_html_e( 'Quote', 'cph-elements' ); ?></strong>
			</label>
		</p>
		<?php
		wp_editor(
			$quote,
			'cph_testimonial_quote',
			array(
				'textarea_name' => 'cph_testimonial_quote',
				'textarea_rows' => 8,
				'media_buttons' => false,
				'teeny'         => false,
				'quicktags'     => true,
			)
		);
	}
}

if ( ! function_exists( 'cph_testimonial_save_meta_box' ) ) {
	/**
	 * Save testimonial meta box data.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The post ID.
	 * @return void
	 */
	function cph_testimonial_save_meta_box( $post_id ) {
		// Verify nonce.
		if ( ! isset( $_POST['cph_testimonial_meta_box_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['cph_testimonial_meta_box_nonce'], 'cph_testimonial_meta_box' ) ) {
			return;
		}

		// Check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save quote (allow HTML from WYSIWYG editor).
		if ( isset( $_POST['cph_testimonial_quote'] ) ) {
			update_post_meta(
				$post_id,
				'_cph_testimonial_quote',
				wp_kses_post( $_POST['cph_testimonial_quote'] )
			);
		}
	}
	add_action( 'save_post_cph_testimonial', 'cph_testimonial_save_meta_box' );
}
