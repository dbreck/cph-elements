<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'CPH Expandable Grid',
	'shortcode' => 'cph_expandable_grid',
	'version'   => '1.0.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/expandable-grid-map.php',
		'render' => __DIR__ . '/expandable-grid-render.php',
		'setup'  => __DIR__ . '/expandable-grid-setup.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-expandable-grid',
				'file'     => 'assets/css/expandable-grid.css',
				'priority' => 100,
			),
		),
		'js'  => array(
			array(
				'handle'   => 'cph-expandable-grid',
				'file'     => 'assets/js/expandable-grid.js',
				'priority' => 100,
			),
		),
	),

	'gsap' => array(),

	'admin_device_groups' => array(),

	'requires' => array(
		'wpbakery' => true,
		'salient'  => false,
	),
);
