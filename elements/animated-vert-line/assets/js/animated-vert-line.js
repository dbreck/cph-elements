/**
 * CPH Animated Vert Line — Scroll Animation
 *
 * Adds .is-visible to trigger the CSS grow animation
 * when the element scrolls into view.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */
document.addEventListener( 'DOMContentLoaded', function() {
	'use strict';

	var lines = document.querySelectorAll( '.cph-animated-vert-line[data-animate]' );

	if ( ! lines.length ) {
		return;
	}

	var observer = new IntersectionObserver( function( entries ) {
		entries.forEach( function( entry ) {
			if ( entry.isIntersecting ) {
				entry.target.classList.add( 'is-visible' );
				observer.unobserve( entry.target );
			}
		} );
	}, {
		threshold: 0.1
	} );

	lines.forEach( function( line ) {
		observer.observe( line );
	} );
} );
