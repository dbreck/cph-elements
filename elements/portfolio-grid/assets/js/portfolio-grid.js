/**
 * CPH Portfolio Grid - Client-side category filter for Zigzag layout.
 *
 * @package CPH_Elements
 * @since   1.2.0
 */

(function() {
	document.addEventListener('DOMContentLoaded', function() {
		document.querySelectorAll('.cph-zigzag__filter').forEach(function(filter) {
			var grid = filter.closest('.cph-portfolio-grid--zigzag');

			if ( ! grid ) {
				return;
			}

			var rows = grid.querySelectorAll('.cph-zigzag__row');
			var btns = filter.querySelectorAll('.cph-zigzag__filter-btn');

			btns.forEach(function(btn) {
				btn.addEventListener('click', function() {
					var slug = btn.getAttribute('data-filter');

					// Update active state.
					btns.forEach(function(b) {
						b.classList.remove('is-active');
					});
					btn.classList.add('is-active');

					// Filter rows.
					rows.forEach(function(row) {
						var cats = (row.getAttribute('data-categories') || '').split(' ');
						row.style.display = ( ! slug || cats.indexOf(slug) !== -1 ) ? '' : 'none';
					});
				});
			});
		});
	});
})();
