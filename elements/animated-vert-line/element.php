<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'CPH Animated Vert Line',
	'shortcode' => 'cph_animated_vert_line',
	'version'   => '1.0.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/animated-vert-line-map.php',
		'render' => __DIR__ . '/animated-vert-line-render.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-animated-vert-line',
				'file'     => 'assets/css/animated-vert-line.css',
				'priority' => 100,
			),
		),
		'js'  => array(
			array(
				'handle' => 'cph-animated-vert-line',
				'file'   => 'assets/js/animated-vert-line.js',
				'deps'   => array(),
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
