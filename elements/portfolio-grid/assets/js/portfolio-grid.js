/**
 * CPH Portfolio Grid - Client-side category filter for Zigzag layout.
 *
 * @package CPH_Elements
 * @since   1.2.0
 */

(function() {
	var FADE_DURATION = 350;

	document.addEventListener('DOMContentLoaded', function() {
		document.querySelectorAll('.cph-zigzag__filter').forEach(function(filter) {
			var grid = filter.closest('.cph-portfolio-grid--zigzag');

			if ( ! grid ) {
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
