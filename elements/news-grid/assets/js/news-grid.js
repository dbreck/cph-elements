/**
 * CPH News Grid JavaScript
 *
 * Handles AJAX load more and filter functionality.
 *
 * @package Salient_Child
 * @since   1.0.0
 */

( function() {
	'use strict';

	/**
	 * CPH News Grid Controller
	 */
	const CPHNewsGrid = {
		/**
		 * Store all grid instances.
		 */
		instances: [],

		/**
		 * Initialize all news grids on the page.
		 */
		init: function() {
			const grids = document.querySelectorAll( '.cph-news-grid' );

			grids.forEach( ( grid ) => {
				this.initGrid( grid );
			} );
		},

		/**
		 * Initialize a single news grid instance.
		 *
		 * @param {HTMLElement} grid The grid container element.
		 */
		initGrid: function( grid ) {
			const instance = {
				container: grid,
				itemsContainer: grid.querySelector( '.cph-news-grid__items' ),
				loadMoreBtn: grid.querySelector( '.cph-news-load-more' ),
				resetBtn: grid.querySelector( '.cph-news-button--reset' ),
				viewToggle: grid.querySelector( '.cph-news-view-toggle' ),
				filters: {
					category: null,
					year: null
				},
				state: {
					page: 1,
					category: '',
					year: '',
					view: grid.dataset.defaultView || 'grid',
					loading: false
				},
				config: {
					postsPerPage: parseInt( grid.dataset.postsPerPage, 10 ) || 6,
					orderby: grid.dataset.orderby || 'date',
					defaultCategory: grid.dataset.defaultCategory || '',
					excludedPost: grid.dataset.excludedPost || '',
					showSource: grid.dataset.showSource || 'yes',
					showDate: grid.dataset.showDate || 'yes',
					cardRadius: grid.dataset.cardRadius || '45px',
					defaultView: grid.dataset.defaultView || 'grid'
				}
			};

			// Initialize default category from config.
			instance.state.category = instance.config.defaultCategory;

			// Initialize filters.
			this.initFilters( instance );

			// Initialize load more button.
			this.initLoadMore( instance );

			// Initialize reset button.
			this.initReset( instance );

			// Initialize view toggle.
			this.initViewToggle( instance );

			// Store instance.
			this.instances.push( instance );
		},

		/**
		 * Initialize filter dropdowns.
		 *
		 * @param {Object} instance Grid instance.
		 */
		initFilters: function( instance ) {
			const categoryFilter = instance.container.querySelector( '.cph-news-filter--category' );
			const yearFilter = instance.container.querySelector( '.cph-news-filter--year' );

			if ( categoryFilter ) {
				instance.filters.category = this.setupDropdown( categoryFilter, 'category', instance );
			}

			if ( yearFilter ) {
				instance.filters.year = this.setupDropdown( yearFilter, 'year', instance );
			}

			// Close dropdowns when clicking outside.
			document.addEventListener( 'click', ( e ) => {
				if ( ! e.target.closest( '.cph-news-filter' ) ) {
					this.closeAllDropdowns( instance );
				}
			} );

			// Close dropdowns on escape key.
			document.addEventListener( 'keydown', ( e ) => {
				if ( 'Escape' === e.key ) {
					this.closeAllDropdowns( instance );
				}
			} );
		},

		/**
		 * Setup a single dropdown filter.
		 *
		 * @param {HTMLElement} filterEl  The filter container element.
		 * @param {string}      filterKey The filter key (category or year).
		 * @param {Object}      instance  Grid instance.
		 * @return {Object} Filter object with trigger, dropdown, and value.
		 */
		setupDropdown: function( filterEl, filterKey, instance ) {
			const trigger = filterEl.querySelector( '.cph-news-filter__trigger' );
			const dropdown = filterEl.querySelector( '.cph-news-filter__dropdown' );
			const label = trigger.querySelector( '.cph-news-filter__label' );
			const options = dropdown.querySelectorAll( 'li' );

			const filter = {
				trigger: trigger,
				dropdown: dropdown,
				label: label,
				defaultLabel: label.textContent,
				value: ''
			};

			// Toggle dropdown on trigger click.
			trigger.addEventListener( 'click', ( e ) => {
				e.stopPropagation();
				const isOpen = dropdown.classList.contains( 'is-open' );

				// Close other dropdowns.
				this.closeAllDropdowns( instance );

				if ( ! isOpen ) {
					dropdown.classList.add( 'is-open' );
					trigger.setAttribute( 'aria-expanded', 'true' );
				}
			} );

			// Handle option selection.
			options.forEach( ( option ) => {
				option.addEventListener( 'click', () => {
					const value = option.dataset.value;
					const text = option.textContent;

					// Update selected state.
					options.forEach( ( opt ) => opt.removeAttribute( 'aria-selected' ) );
					option.setAttribute( 'aria-selected', 'true' );

					// Update label.
					filter.value = value;
					label.textContent = value ? text : filter.defaultLabel;

					// Close dropdown.
					dropdown.classList.remove( 'is-open' );
					trigger.setAttribute( 'aria-expanded', 'false' );

					// Update state and reload.
					instance.state[ filterKey ] = value;
					instance.state.page = 1;
					this.loadPosts( instance, true );
				} );
			} );

			// Keyboard navigation.
			trigger.addEventListener( 'keydown', ( e ) => {
				if ( 'Enter' === e.key || ' ' === e.key ) {
					e.preventDefault();
					trigger.click();
				}
			} );

			return filter;
		},

		/**
		 * Close all dropdowns for an instance.
		 *
		 * @param {Object} instance Grid instance.
		 */
		closeAllDropdowns: function( instance ) {
			const dropdowns = instance.container.querySelectorAll( '.cph-news-filter__dropdown' );
			const triggers = instance.container.querySelectorAll( '.cph-news-filter__trigger' );

			dropdowns.forEach( ( dropdown ) => dropdown.classList.remove( 'is-open' ) );
			triggers.forEach( ( trigger ) => trigger.setAttribute( 'aria-expanded', 'false' ) );
		},

		/**
		 * Initialize load more button.
		 *
		 * @param {Object} instance Grid instance.
		 */
		initLoadMore: function( instance ) {
			if ( ! instance.loadMoreBtn ) {
				return;
			}

			instance.loadMoreBtn.addEventListener( 'click', () => {
				if ( instance.state.loading ) {
					return;
				}

				instance.state.page++;
				this.loadPosts( instance, false );
			} );
		},

		/**
		 * Initialize reset button.
		 *
		 * @param {Object} instance Grid instance.
		 */
		initReset: function( instance ) {
			if ( ! instance.resetBtn ) {
				return;
			}

			instance.resetBtn.addEventListener( 'click', () => {
				// Reset state.
				instance.state.category = instance.config.defaultCategory;
				instance.state.year = '';
				instance.state.page = 1;

				// Reset filter labels and selections.
				if ( instance.filters.category ) {
					instance.filters.category.label.textContent = instance.filters.category.defaultLabel;
					instance.filters.category.value = '';
					const options = instance.filters.category.dropdown.querySelectorAll( 'li' );
					options.forEach( ( opt ) => opt.removeAttribute( 'aria-selected' ) );
				}

				if ( instance.filters.year ) {
					instance.filters.year.label.textContent = instance.filters.year.defaultLabel;
					instance.filters.year.value = '';
					const options = instance.filters.year.dropdown.querySelectorAll( 'li' );
					options.forEach( ( opt ) => opt.removeAttribute( 'aria-selected' ) );
				}

				// Reload posts.
				this.loadPosts( instance, true );
			} );
		},

		/**
		 * Initialize view toggle buttons.
		 *
		 * @param {Object} instance Grid instance.
		 */
		initViewToggle: function( instance ) {
			if ( ! instance.viewToggle ) {
				return;
			}

			const buttons = instance.viewToggle.querySelectorAll( '.cph-news-view-toggle__btn' );

			buttons.forEach( ( btn ) => {
				btn.addEventListener( 'click', () => {
					const newView = btn.dataset.view;

					// Skip if already active.
					if ( newView === instance.state.view ) {
						return;
					}

					// Update button states.
					buttons.forEach( ( b ) => b.classList.remove( 'is-active' ) );
					btn.classList.add( 'is-active' );

					// Update state.
					instance.state.view = newView;

					// Update container data attribute.
					instance.itemsContainer.setAttribute( 'data-view', newView );

					// Reload posts with new view (replace content).
					instance.state.page = 1;
					this.loadPosts( instance, true );
				} );
			} );
		},

		/**
		 * Load posts via AJAX.
		 *
		 * @param {Object}  instance Grid instance.
		 * @param {boolean} replace  Whether to replace existing content.
		 */
		loadPosts: function( instance, replace ) {
			if ( instance.state.loading ) {
				return;
			}

			instance.state.loading = true;

			// Show loading state.
			if ( instance.loadMoreBtn ) {
				instance.loadMoreBtn.classList.add( 'is-loading' );
			}

			// Build form data.
			const formData = new FormData();
			formData.append( 'action', 'cph_news_load_more' );
			formData.append( 'nonce', btiNewsGridData.nonce );
			formData.append( 'page', replace ? 1 : instance.state.page );
			formData.append( 'posts_per_page', instance.config.postsPerPage );
			formData.append( 'category', instance.state.category );
			formData.append( 'year', instance.state.year );
			formData.append( 'orderby', instance.config.orderby );
			formData.append( 'excluded_post', instance.config.excludedPost );
			formData.append( 'show_source', instance.config.showSource );
			formData.append( 'show_date', instance.config.showDate );
			formData.append( 'card_radius', instance.config.cardRadius );

			// Make AJAX request.
			fetch( btiNewsGridData.ajaxUrl, {
				method: 'POST',
				body: formData,
				credentials: 'same-origin'
			} )
				.then( ( response ) => response.json() )
				.then( ( data ) => {
					if ( data.success ) {
						// Select the appropriate HTML based on current view.
						const html = 'list' === instance.state.view ? data.data.html_list : data.data.html_grid;

						if ( replace ) {
							// Replace content.
							instance.itemsContainer.innerHTML = html || '<p class="cph-news-grid__empty">No posts found.</p>';
							instance.state.page = 1;
						} else {
							// Append content.
							instance.itemsContainer.insertAdjacentHTML( 'beforeend', html );
						}

						// Update load more button visibility.
						if ( instance.loadMoreBtn ) {
							const loadMoreContainer = instance.loadMoreBtn.closest( '.cph-news-grid__load-more' );
							if ( loadMoreContainer ) {
								loadMoreContainer.style.display = data.data.has_more ? 'flex' : 'none';
							}
						}
					}
				} )
				.catch( ( error ) => {
					console.error( 'CPH News Grid AJAX Error:', error );
				} )
				.finally( () => {
					instance.state.loading = false;

					if ( instance.loadMoreBtn ) {
						instance.loadMoreBtn.classList.remove( 'is-loading' );
					}
				} );
		}
	};

	/**
	 * Initialize on DOM ready.
	 */
	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', () => CPHNewsGrid.init() );
	} else {
		CPHNewsGrid.init();
	}

	/**
	 * Expose to global scope for external access.
	 */
	window.CPHNewsGrid = CPHNewsGrid;
} )();
