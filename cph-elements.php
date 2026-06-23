<?php
/**
 * Plugin Name: CPH Elements
 * Description: Custom WPBakery elements by Clear pH. Auto-discovers elements from the elements/ directory.
 * Version:     1.9.6
 * Author:      Clear pH
 * Author URI:  https://clearph.com
 * Text Domain: cph-elements
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package CPH_Elements
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin constants.
 */
define( 'CPH_ELEMENTS_VERSION', '1.9.6' );
define( 'CPH_ELEMENTS_FILE', __FILE__ );
define( 'CPH_ELEMENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'CPH_ELEMENTS_URL', plugin_dir_url( __FILE__ ) );

/**
 * Get the URL for an element's directory.
 *
 * @since 1.0.0
 *
 * @param string $slug Element slug (directory name under elements/).
 * @return string URL to the element directory (with trailing slash).
 */
function cph_element_url( $slug ) {
	return CPH_ELEMENTS_URL . 'elements/' . $slug . '/';
}

/**
 * Get the filesystem path for an element's directory.
 *
 * @since 1.0.0
 *
 * @param string $slug Element slug (directory name under elements/).
 * @return string Path to the element directory (with trailing slash).
 */
function cph_element_path( $slug ) {
	return CPH_ELEMENTS_PATH . 'elements/' . $slug . '/';
}

/**
 * Load shared classes.
 */
require_once CPH_ELEMENTS_PATH . 'shared/class-cph-github-updater.php';
require_once CPH_ELEMENTS_PATH . 'shared/class-cph-element-loader.php';
require_once CPH_ELEMENTS_PATH . 'shared/class-cph-gsap.php';
require_once CPH_ELEMENTS_PATH . 'shared/class-cph-admin.php';

/**
 * Initialize the plugin on plugins_loaded.
 *
 * @since 1.0.0
 */
function cph_elements_init() {
	// Load optional config file for allow-list filtering.
	// wp-content/cph-config.php takes priority (site-specific, survives plugin
	// updates and symlinked installs); falls back to a copy in the plugin dir.
	$config = array();
	$config_file = WP_CONTENT_DIR . '/cph-config.php';
	if ( ! file_exists( $config_file ) ) {
		$config_file = CPH_ELEMENTS_PATH . 'cph-config.php';
	}
	if ( file_exists( $config_file ) ) {
		$config = include $config_file;
	}

	// Boot the loader.
	$loader = new CPH_Element_Loader( $config );
	$loader->init();

	// Boot the GSAP manager.
	$gsap = new CPH_GSAP( $loader );
	$gsap->init();

	// Boot the admin handler.
	$admin = new CPH_Admin( $loader );
	$admin->init();

	// Boot the GitHub updater.
	$updater = new CPH_GitHub_Updater( CPH_ELEMENTS_FILE, 'dbreck/cph-elements', CPH_ELEMENTS_VERSION );
	$updater->init();
}
add_action( 'plugins_loaded', 'cph_elements_init' );
