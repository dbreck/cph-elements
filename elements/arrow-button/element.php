<?php
/**
 * Arrow Button - Element Config
 *
 * @package CPH_Elements
 * @since   1.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'Arrow Button',
	'shortcode' => 'cph_arrow_button',
	'version'   => '1.0.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/arrow-button-map.php',
		'render' => __DIR__ . '/arrow-button-render.php',
		'setup'  => __DIR__ . '/arrow-button-setup.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-arrow-button',
				'file'     => 'assets/css/arrow-button.css',
				'priority' => 100,
			),
		),
		'js'  => array(),
	),

	'gsap' => array(),

	'admin_device_groups' => array(),

	'requires' => array(
		'wpbakery' => true,
		'salient'  => false,
	),
);
