<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'CPH Gallery Slider',
	'shortcode' => 'cph_gallery_slider',
	'version'   => '1.0.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/gallery-slider-map.php',
		'render' => __DIR__ . '/gallery-slider-render.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-gallery-slider',
				'file'     => 'assets/css/gallery-slider.css',
				'priority' => 100,
			),
		),
		'js'  => array(
			array(
				'handle'   => 'cph-gallery-slider',
				'file'     => 'assets/js/gallery-slider.js',
				'priority' => 60,
				'deps'     => array( 'fg-gsap-core' ),
			),
		),
	),

	'gsap' => array( 'core' ),

	'admin_device_groups' => array(
		'gallery-slider-height-device-group',
		'gallery-slider-width-device-group',
		'gallery-slider-gap-device-group',
	),

	'requires' => array(
		'wpbakery' => true,
		'salient'  => false,
	),
);
