<?php
/**
 * CPH Admin Handler
 *
 * Enqueues shared admin assets for WPBakery element settings panels.
 * Collects device group mappings from element configs and passes them
 * to the admin JS via wp_localize_script.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CPH_Admin
 *
 * @since 1.0.0
 */
class CPH_Admin {

	/**
	 * Reference to the element loader.
	 *
	 * @var CPH_Element_Loader
	 */
	private $loader;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param CPH_Element_Loader $loader The element loader.
	 */
	public function __construct( CPH_Element_Loader $loader ) {
		$this->loader = $loader;
	}

	/**
	 * Initialize: hook into admin_enqueue_scripts.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Enqueue admin JS and CSS for WPBakery panels.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_admin_assets() {
		$screen = get_current_screen();

		// Only load on post edit screens and WPBakery frontend editor.
		if ( ! $screen || ( 'post' !== $screen->base && 'toplevel_page_vc-welcome' !== $screen->id ) ) {
			// Also allow on any page where WPBakery might be active.
			if ( ! defined( 'WPB_VC_VERSION' ) ) {
				return;
			}
		}

		wp_enqueue_script(
			'cph-wpbakery-admin',
			CPH_ELEMENTS_URL . 'shared/assets/js/cph-wpbakery-admin.js',
			array( 'jquery' ),
			CPH_ELEMENTS_VERSION,
			true
		);

		wp_enqueue_style(
			'cph-wpbakery-admin',
			CPH_ELEMENTS_URL . 'shared/assets/css/cph-wpbakery-admin.css',
			array(),
			CPH_ELEMENTS_VERSION
		);

		// Collect device group mappings from all active elements.
		$device_groups = $this->collect_device_groups();

		wp_localize_script(
			'cph-wpbakery-admin',
			'cphDeviceGroups',
			$device_groups
		);
	}

	/**
	 * Collect device group mappings from all active elements.
	 *
	 * Returns an object keyed by shortcode base, each containing
	 * an array of device group class names.
	 *
	 * @since 1.0.0
	 *
	 * @return array Device groups keyed by shortcode.
	 */
	private function collect_device_groups() {
		$groups   = array();
		$elements = $this->loader->get_elements();

		foreach ( $elements as $config ) {
			if ( ! empty( $config['admin_device_groups'] ) && ! empty( $config['shortcode'] ) ) {
				$groups[ $config['shortcode'] ] = $config['admin_device_groups'];
			}
		}

		return $groups;
	}
}
