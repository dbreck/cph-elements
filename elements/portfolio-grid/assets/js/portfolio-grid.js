/**
 * CPH Portfolio Grid - Client-side category filters for Zigzag and Masonry layouts.
 *
 * @package CPH_Elements
 * @since   1.2.0
 */

(function() {
	var FADE_DURATION = 350;

	/**
	 * Kill GSAP ScrollTrigger entrance animations (fx-up) inside a container
	 * so that filter visibility toggles aren't blocked by stale opacity:0 state.
	 *
	 * @param {Element} container The grid wrapper element.
	 */
	function clearFxUpAnimations( container ) {
		if ( typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined' ) {
			return;
		}

		var fxEls = container.querySelectorAll('.fx-up');

		if ( ! fxEls.length ) {
			return;
		}

		fxEls.forEach(function( el ) {
			// Kill any ScrollTriggers targeting this element.
			ScrollTrigger.getAll().forEach(function( st ) {
				if ( st.trigger === el ) {
					st.kill();
				}
			});

			// Force to visible final state.
			gsap.set( el, { opacity: 1, y: 0, filter: 'none', scale: 1, rotation: 0, clearProps: 'filter' } );

			// Also clear any animated children (text lines).
			var children = el.querySelectorAll('p, h1, h2, h3, h4, h5, h6, li');
			if ( children.length ) {
				gsap.set( children, { opacity: 1, y: 0, filter: 'none', scale: 1, rotation: 0, clearProps: 'filter' } );
			}

			// Remove fx-up classes so MutationObserver doesn't re-init.
			el.className = el.className.replace( /\bfx-up\S*/g, '' ).trim();
			el.dataset.fxUpAnimated = 'true';
		});
	}

	/**
	 * Generic filter handler for masonry layout.
	 */
	function initMasonryFilter( grid ) {
		var filter = grid.querySelector('.cph-masonry__filter');

		if ( ! filter ) {
			return;
		}

		var items       = grid.querySelectorAll('.cph-masonry__item');
		var btns        = filter.querySelectorAll('.cph-masonry__filter-btn');
		var isAnimating = false;

		btns.forEach(function(btn) {
			btn.addEventListener('click', function() {
				var slug = btn.getAttribute('data-filter');

				if ( isAnimating ) {
					return;
				}

				if ( btn.classList.contains('is-active') ) {
					return;
				}

				isAnimating = true;

				// Update active button.
				btns.forEach(function(b) {
					b.classList.remove('is-active');
				});
				btn.classList.add('is-active');

				// Phase 1: Fade out visible items.
				items.forEach(function(item) {
					if ( ! item.classList.contains('is-hidden') ) {
						item.classList.add('is-filtering-out');
					}
				});

				// Phase 2: Toggle visibility.
				setTimeout(function() {
					// Clear GSAP entrance animations so cards aren't stuck at opacity 0.
					clearFxUpAnimations( grid );

					items.forEach(function(item) {
						var cats  = (item.getAttribute('data-categories') || '').split(' ');
						var match = ! slug || cats.indexOf(slug) !== -1;

						item.classList.remove('is-filtering-out');

						if ( match ) {
							item.classList.remove('is-hidden');
							item.classList.add('is-filtering-in');
						} else {
							item.classList.add('is-hidden');
							item.classList.remove('is-filtering-in');
						}
					});

					// Force reflow.
					void grid.offsetHeight;

					// Phase 3: Fade in.
					requestAnimationFrame(function() {
						items.forEach(function(item) {
							item.classList.remove('is-filtering-in');
						});
					});

					setTimeout(function() {
						isAnimating = false;
					}, FADE_DURATION);
				}, FADE_DURATION);
			});
		});
	}

	/**
	 * Re-apply the --flipped class to every other visible row so the
	 * zigzag pattern stays correct after filtering hides rows.
	 */
	function applyZigzag( grid ) {
		var visible = grid.querySelectorAll('.cph-zigzag__row:not(.is-hidden)');
		visible.forEach(function(row, i) {
			row.classList.toggle('cph-zigzag__row--flipped', i % 2 === 1);
		});
	}

	/**
	 * Mark image containers as loaded once their media finishes loading.
	 * Applies to both .cph-card__image (masonry) and .cph-zigzag__image (zigzag).
	 */
	function initLoadingSpinners() {
		var containers = document.querySelectorAll('.cph-card__image, .cph-zigzag__image');

		containers.forEach(function( container ) {
			var img   = container.querySelector('img');
			var video = container.querySelector('video');

			if ( img ) {
				if ( img.complete && img.naturalWidth > 0 ) {
					container.classList.add('is-loaded');
				} else {
					img.addEventListener('load', function() {
						container.classList.add('is-loaded');
					});
					img.addEventListener('error', function() {
						container.classList.add('is-loaded');
					});
				}
			} else if ( video ) {
				if ( video.readyState >= 2 ) {
					container.classList.add('is-loaded');
				} else {
					video.addEventListener('loadeddata', function() {
						container.classList.add('is-loaded');
					});
					video.addEventListener('error', function() {
						container.classList.add('is-loaded');
					});
				}
			} else {
				// No media — nothing to wait for.
				container.classList.add('is-loaded');
			}
		});
	}

	document.addEventListener('DOMContentLoaded', function() {

		// Loading spinners.
		initLoadingSpinners();

		// Masonry filter.
		document.querySelectorAll('.cph-portfolio-grid--masonry').forEach(function(grid) {
			initMasonryFilter( grid );
		});

		// Zigzag filter.
		document.querySelectorAll('.cph-portfolio-grid--zigzag').forEach(function(grid) {

			// Set initial zigzag on page load.
			applyZigzag( grid );

			var filter = grid.querySelector('.cph-zigzag__filter');

			if ( ! filter ) {
				return;
			}

			var rows = grid.querySelectorAll('.cph-zigzag__row');
			var btns = filter.querySelectorAll('.cph-zigzag__filter-btn');
			var isAnimating = false;

			btns.forEach(function(btn) {
				btn.addEventListener('click', function() {
					var slug = btn.getAttribute('data-filter');

					if ( isAnimating ) {
						return;
					}

					// Skip if already active.
					if ( btn.classList.contains('is-active') ) {
						return;
					}

					isAnimating = true;

					// Update active button state.
					btns.forEach(function(b) {
						b.classList.remove('is-active');
					});
					btn.classList.add('is-active');

					// Phase 1: Fade out all visible rows.
					rows.forEach(function(row) {
						if ( ! row.classList.contains('is-hidden') ) {
							row.classList.add('is-filtering-out');
						}
					});

					// Phase 2: After fade-out, toggle visibility and fade in.
					setTimeout(function() {
						// Clear GSAP entrance animations so cards aren't stuck at opacity 0.
						clearFxUpAnimations( grid );

						rows.forEach(function(row) {
							var cats = (row.getAttribute('data-categories') || '').split(' ');
							var match = ! slug || cats.indexOf(slug) !== -1;

							row.classList.remove('is-filtering-out');

							if ( match ) {
								row.classList.remove('is-hidden');
								row.classList.add('is-filtering-in');
							} else {
								row.classList.add('is-hidden');
								row.classList.remove('is-filtering-in');
							}
						});

						// Re-zigzag visible rows after filter change.
						applyZigzag( grid );

						// Force reflow so the browser registers the is-filtering-in state.
						void grid.offsetHeight;

						// Phase 3: Fade in the newly visible rows.
						requestAnimationFrame(function() {
							rows.forEach(function(row) {
								row.classList.remove('is-filtering-in');
							});
						});

						setTimeout(function() {
							isAnimating = false;
						}, FADE_DURATION);
					}, FADE_DURATION);
				});
			});
		});
	});
})();
