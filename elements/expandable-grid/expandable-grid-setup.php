<?php
/**
 * CPH Expandable Grid - Setup
 *
 * Registers the cph_grid_item custom post type and meta boxes.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * ==========================================================================
 * CPH GRID ITEM - CUSTOM POST TYPE
 * ==========================================================================
 */

if ( ! function_exists( 'cph_register_grid_item_post_type' ) ) {
	/**
	 * Register the Grid Item custom post type.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_register_grid_item_post_type() {
		$labels = array(
			'name'               => _x( 'Grid Items', 'Post type general name', 'cph-elements' ),
			'singular_name'      => _x( 'Grid Item', 'Post type singular name', 'cph-elements' ),
			'menu_name'          => _x( 'Grid Items', 'Admin Menu text', 'cph-elements' ),
			'add_new'            => __( 'Add New', 'cph-elements' ),
			'add_new_item'       => __( 'Add New Grid Item', 'cph-elements' ),
			'edit_item'          => __( 'Edit Grid Item', 'cph-elements' ),
			'new_item'           => __( 'New Grid Item', 'cph-elements' ),
			'view_item'          => __( 'View Grid Item', 'cph-elements' ),
			'search_items'       => __( 'Search Grid Items', 'cph-elements' ),
			'not_found'          => __( 'No grid items found', 'cph-elements' ),
			'not_found_in_trash' => __( 'No grid items found in Trash', 'cph-elements' ),
			'all_items'          => __( 'All Grid Items', 'cph-elements' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'menu_icon'          => 'dashicons-grid-view',
			'supports'           => array( 'title', 'editor', 'page-attributes' ),
			'show_in_rest'       => true,
		);

		register_post_type( 'cph_grid_item', $args );
	}
	add_action( 'init', 'cph_register_grid_item_post_type' );
}

/*
 * ==========================================================================
 * CPH GRID ITEM - META BOXES
 * ==========================================================================
 */

if ( ! function_exists( 'cph_grid_item_add_meta_boxes' ) ) {
	/**
	 * Add meta box for grid item details.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function cph_grid_item_add_meta_boxes() {
		add_meta_box(
			'cph_grid_item_details',
			__( 'Grid Item Details', 'cph-elements' ),
			'cph_grid_item_meta_box_callback',
			'cph_grid_item',
			'normal',
			'high'
		);
	}
	add_action( 'add_meta_boxes', 'cph_grid_item_add_meta_boxes' );
}

if ( ! function_exists( 'cph_grid_item_meta_box_callback' ) ) {
	/**
	 * Render the grid item meta box.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The post object.
	 * @return void
	 */
	function cph_grid_item_meta_box_callback( $post ) {
		wp_nonce_field( 'cph_grid_item_meta_box', 'cph_grid_item_meta_box_nonce' );

		$btn_text      = get_post_meta( $post->ID, '_cph_grid_btn_text', true );
		$btn_url       = get_post_meta( $post->ID, '_cph_grid_btn_url', true );
		$border_top    = get_post_meta( $post->ID, '_cph_grid_border_top', true );
		$border_right  = get_post_meta( $post->ID, '_cph_grid_border_right', true );
		$border_bottom = get_post_meta( $post->ID, '_cph_grid_border_bottom', true );
		$border_left   = get_post_meta( $post->ID, '_cph_grid_border_left', true );

		$border_top_mobile    = get_post_meta( $post->ID, '_cph_grid_border_top_mobile', true );
		$border_right_mobile  = get_post_meta( $post->ID, '_cph_grid_border_right_mobile', true );
		$border_bottom_mobile = get_post_meta( $post->ID, '_cph_grid_border_bottom_mobile', true );
		$border_left_mobile   = get_post_meta( $post->ID, '_cph_grid_border_left_mobile', true );
		?>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="cph_grid_btn_text"><?php esc_html_e( 'Button Text', 'cph-elements' ); ?></label>
				</th>
				<td>
					<input type="text"
						id="cph_grid_btn_text"
						name="cph_grid_btn_text"
						value="<?php echo esc_attr( $btn_text ); ?>"
						class="regular-text"
						placeholder="<?php esc_attr_e( 'e.g., Website', 'cph-elements' ); ?>" />
					<p class="description"><?php esc_html_e( 'Leave empty to hide the button in the expanded panel.', 'cph-elements' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="cph_grid_btn_url"><?php esc_html_e( 'Button URL', 'cph-elements' ); ?></label>
				</th>
				<td>
					<input type="url"
						id="cph_grid_btn_url"
						name="cph_grid_btn_url"
						value="<?php echo esc_url( $btn_url ); ?>"
						class="regular-text"
						placeholder="https://" />
				</td>
			</tr>
		</table>

		<h3 style="margin-top: 1.5em;"><?php esc_html_e( 'Borders — Desktop', 'cph-elements' ); ?></h3>
		<p class="description"><?php esc_html_e( 'CSS border shorthand for each side. Leave empty for no border. Example: 1px solid #333333', 'cph-elements' ); ?></p>

		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="cph_grid_border_top"><?php esc_html_e( 'Border Top', 'cph-elements' ); ?></label>
				</th>
				<td>
					<input type="text"
						id="cph_grid_border_top"
						name="cph_grid_border_top"
						value="<?php echo esc_attr( $border_top ); ?>"
						class="regular-text"
						placeholder="1px solid #333333" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="cph_grid_border_right"><?php esc_html_e( 'Border Right', 'cph-elements' ); ?></label>
				</th>
				<td>
					<input type="text"
						id="cph_grid_border_right"
						name="cph_grid_border_right"
						value="<?php echo esc_attr( $border_right ); ?>"
						class="regular-text"
						placeholder="1px solid #333333" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="cph_grid_border_bottom"><?php esc_html_e( 'Border Bottom', 'cph-elements' ); ?></label>
				</th>
				<td>
					<input type="text"
						id="cph_grid_border_bottom"
						name="cph_grid_border_bottom"
						value="<?php echo esc_attr( $border_bottom ); ?>"
						class="regular-text"
						placeholder="1px solid #333333" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="cph_grid_border_left"><?php esc_html_e( 'Border Left', 'cph-elements' ); ?></label>
				</th>
				<td>
					<input type="text"
						id="cph_grid_border_left"
						name="cph_grid_border_left"
						value="<?php echo esc_attr( $border_left ); ?>"
						class="regular-text"
						placeholder="1px solid #333333" />
				</td>
			</tr>
		</table>

		<h3 style="margin-top: 1.5em;"><?php esc_html_e( 'Borders — Mobile', 'cph-elements' ); ?></h3>
		<p class="description"><?php esc_html_e( 'Override borders at 690px and below. Leave empty to inherit from Desktop.', 'cph-elements' ); ?></p>

		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="cph_grid_border_top_mobile"><?php esc_html_e( 'Border Top', 'cph-elements' ); ?></label>
				</th>
				<td>
					<input type="text"
						id="cph_grid_border_top_mobile"
						name="cph_grid_border_top_mobile"
						value="<?php echo esc_attr( $border_top_mobile ); ?>"
						class="regular-text"
						placeholder="<?php esc_attr_e( 'Inherit from Desktop', 'cph-elements' ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="cph_grid_border_right_mobile"><?php esc_html_e( 'Border Right', 'cph-elements' ); ?></label>
				</th>
				<td>
					<input type="text"
						id="cph_grid_border_right_mobile"
						name="cph_grid_border_right_mobile"
						value="<?php echo esc_attr( $border_right_mobile ); ?>"
						class="regular-text"
						placeholder="<?php esc_attr_e( 'Inherit from Desktop', 'cph-elements' ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="cph_grid_border_bottom_mobile"><?php esc_html_e( 'Border Bottom', 'cph-elements' ); ?></label>
				</th>
				<td>
					<input type="text"
						id="cph_grid_border_bottom_mobile"
						name="cph_grid_border_bottom_mobile"
						value="<?php echo esc_attr( $border_bottom_mobile ); ?>"
						class="regular-text"
						placeholder="<?php esc_attr_e( 'Inherit from Desktop', 'cph-elements' ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="cph_grid_border_left_mobile"><?php esc_html_e( 'Border Left', 'cph-elements' ); ?></label>
				</th>
				<td>
					<input type="text"
						id="cph_grid_border_left_mobile"
						name="cph_grid_border_left_mobile"
						value="<?php echo esc_attr( $border_left_mobile ); ?>"
						class="regular-text"
						placeholder="<?php esc_attr_e( 'Inherit from Desktop', 'cph-elements' ); ?>" />
				</td>
			</tr>
		</table>
		<?php
	}
}

if ( ! function_exists( 'cph_grid_item_save_meta_box' ) ) {
	/**
	 * Save grid item meta box data.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The post ID.
	 * @return void
	 */
	function cph_grid_item_save_meta_box( $post_id ) {
		if ( ! isset( $_POST['cph_grid_item_meta_box_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['cph_grid_item_meta_box_nonce'], 'cph_grid_item_meta_box' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save button fields.
		if ( isset( $_POST['cph_grid_btn_text'] ) ) {
			update_post_meta( $post_id, '_cph_grid_btn_text', sanitize_text_field( $_POST['cph_grid_btn_text'] ) );
		}
		if ( isset( $_POST['cph_grid_btn_url'] ) ) {
			update_post_meta( $post_id, '_cph_grid_btn_url', esc_url_raw( $_POST['cph_grid_btn_url'] ) );
		}

		// Save border fields (desktop + mobile).
		$border_keys = array(
			'cph_grid_border_top',
			'cph_grid_border_right',
			'cph_grid_border_bottom',
			'cph_grid_border_left',
			'cph_grid_border_top_mobile',
			'cph_grid_border_right_mobile',
			'cph_grid_border_bottom_mobile',
			'cph_grid_border_left_mobile',
		);
		foreach ( $border_keys as $key ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, '_' . $key, sanitize_text_field( $_POST[ $key ] ) );
			}
		}
	}
	add_action( 'save_post_cph_grid_item', 'cph_grid_item_save_meta_box' );
}
