<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'CPH Testimonials',
	'shortcode' => 'cph_testimonials',
	'version'   => '1.0.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/testimonials-map.php',
		'render' => __DIR__ . '/testimonials-render.php',
		'setup'  => __DIR__ . '/testimonials-setup.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-testimonials',
				'file'     => 'assets/css/testimonials.css',
				'priority' => 100,
			),
		),
		'js'  => array(
			array(
				'handle' => 'cph-testimonials',
				'file'   => 'assets/js/testimonials.js',
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
