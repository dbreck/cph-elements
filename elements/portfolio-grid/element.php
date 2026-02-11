<?php
/**
 * CPH Portfolio Grid - Element Configuration
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'CPH Portfolio Grid',
	'shortcode' => 'cph_portfolio_grid',
	'version'   => '1.0.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/portfolio-grid-map.php',
		'render' => __DIR__ . '/portfolio-grid-render.php',
		'setup'  => __DIR__ . '/portfolio-grid-setup.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-portfolio-grid',
				'file'     => 'assets/css/portfolio-grid.css',
				'priority' => 100,
			),
		),
		'js'  => array(
			array(
				'handle'   => 'cph-portfolio-grid',
				'file'     => 'assets/js/portfolio-grid.js',
				'priority' => 60,
			),
		),
	),

	'gsap' => array(),

	'admin_device_groups' => array(
		'card-height-device-group',
		'location-font-size-device-group',
	),

	'requires' => array(
		'wpbakery' => true,
		'salient'  => false,
	),
);
