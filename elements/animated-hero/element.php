<?php
/**
 * Mira Mar Animated Hero - Element Config
 *
 * Layered CSS-only animated hero (gradient sky, drifting cloud bands, bird
 * flock pass, facade cutout, logo) ported from the Mira Mar brochure design
 * export. Site-specific artwork lives in assets/img/ — allow-list this
 * element only on Mira Mar.
 *
 * @package CPH_Elements
 * @since   1.11.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'      => 'Mira Mar Animated Hero',
	'shortcode' => 'miramar_animated_hero',
	'version'   => '1.0.0',
	'category'  => 'Clear pH Elements',

	'files' => array(
		'map'    => __DIR__ . '/animated-hero-map.php',
		'render' => __DIR__ . '/animated-hero-render.php',
	),

	'assets' => array(
		'css' => array(
			array(
				'handle'   => 'cph-animated-hero',
				'file'     => 'assets/css/animated-hero.css',
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
