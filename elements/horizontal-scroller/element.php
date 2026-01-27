<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'CPH Horizontal Scroller',
	'shortcode' => 'cph_horizontal_scroller',
	'version'   => '1.0.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/horizontal-scroller-map.php',
		'render' => __DIR__ . '/horizontal-scroller-render.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-horizontal-scroller',
				'file'     => 'assets/css/horizontal-scroller.css',
				'priority' => 100,
			),
		),
		'js'  => array(
			array(
				'handle'   => 'cph-horizontal-scroller',
				'file'     => 'assets/js/horizontal-scroller.js',
				'priority' => 60,
				'deps'     => array( 'fg-gsap-core', 'fg-gsap-draggable', 'fg-gsap-inertia' ),
			),
		),
	),

	'gsap' => array( 'core', 'draggable', 'inertia' ),

	'admin_device_groups' => array(
		'card-width-device-group',
		'gap-device-group',
		'arrow-offset-device-group',
		'content-inset-device-group',
	),

	'requires' => array(
		'wpbakery' => true,
		'salient'  => false,
	),
);
