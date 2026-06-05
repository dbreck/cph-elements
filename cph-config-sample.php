<?php
/**
 * CPH Elements Configuration (Sample)
 *
 * Copy this file to wp-content/cph-config.php (preferred — site-specific,
 * survives plugin updates and symlinked installs) or to cph-config.php in
 * the plugin directory, then customize. The wp-content copy wins when both
 * exist. When a config exists, only the elements listed below will load.
 * Delete the file (or don't create it) to load all elements.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(

	/*
	 * Element allow-list.
	 *
	 * Only these element slugs will be loaded. Comment out or remove
	 * any elements you don't need on this site.
	 */
	'elements' => array(
		'animated-vert-line',
		'expandable-grid',
		'file-download-card',
		'gallery-slider',
		'horizontal-scroller',
		'icon-grid',
		'news-grid',
		'portfolio-grid',
		'stats-bubbles',
		'stats-grid',
		'stats-photos',
		'team-grid',
		'testimonials',
	),
);
