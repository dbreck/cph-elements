/**
 * CPH Horizontal Scroller
 *
 * Infinite horizontal carousel using GSAP Draggable with InertiaPlugin.
 * Supports drag/swipe and arrow button navigation.
 *
 * @package Salient_Child
 * @since   1.0.0
 */

(function() {
	'use strict';

	/**
	 * Configuration
	 */
	var CONFIG = {
		selector: '.cph-scroller',
		trackSelector: '.cph-scroller__track',
		viewportSelector: '.cph-scroller__viewport',
		arrowSelector: '.cph-scroller__arrow',
		cardSelector: '.cph-scroller__card',
		animationDuration: 0.5,
		animationEase: 'power2.out',
		snapDuration: 0.3,
		snapEase: 'power2.out'
	};

	/**
	 * Store for scroller instances
	 */
	var instances = [];

	/**
	 * Initialize a single scroller instance
	 *
	 * @param {HTMLElement} container The scroller container element
	 */
	function initScroller(container) {
		// Skip if already initialized
		if (container.dataset.scrollerInit === 'true') {
			return;
		}

		var track = container.querySelector(CONFIG.trackSelector);
		var viewport = container.querySelector(CONFIG.viewportSelector);
		var arrow = container.querySelector(CONFIG.arrowSelector);
		var cards = container.querySelectorAll(CONFIG.cardSelector);

		if (!track || !viewport || cards.length === 0) {
			return;
		}

		var direction = container.dataset.direction || 'left';
		var cardCount = cards.length;

		// Get actual card dimensions from the first card element
		var firstCard = cards[0];
		var cardRect = firstCard.getBoundingClientRect();
		var cardWidth = cardRect.width;

		// Get gap from computed styles
		var trackStyle = getComputedStyle(track);
		var gap = parseFloat(trackStyle.gap) || 178;

		// Calculate total width of one set of cards
		var cardTotalWidth = cardWidth + gap;
		var totalWidth = cardTotalWidth * cardCount;

		// Clone cards for seamless infinite loop (clone entire set)
		var clonesBefore = [];
		var clonesAfter = [];

		cards.forEach(function(card) {
			var cloneBefore = card.cloneNode(true);
			var cloneAfter = card.cloneNode(true);
			cloneBefore.classList.add('cph-scroller__card--clone');
			cloneAfter.classList.add('cph-scroller__card--clone');
			cloneBefore.setAttribute('aria-hidden', 'true');
			cloneAfter.setAttribute('aria-hidden', 'true');
			clonesBefore.push(cloneBefore);
			clonesAfter.push(cloneAfter);
		});

		// Insert clones (before = prepend, after = append)
		clonesBefore.reverse().forEach(function(clone) {
			track.insertBefore(clone, track.firstChild);
		});
		clonesAfter.forEach(function(clone) {
			track.appendChild(clone);
		});

		// Set initial position to show original cards
		// Left direction: show left side of originals (position = -totalWidth to skip prepended clones)
		// Right direction: show right side of originals (position so rightmost card is at right edge of viewport)
		var viewportWidth = viewport.getBoundingClientRect().width;
		var initialX;
		if (direction === 'right') {
			// Position track so the rightmost original card aligns with right edge of viewport
			// Originals end at 2*totalWidth from track start, we want that at viewport's right edge
			initialX = viewportWidth - (2 * totalWidth);
		} else {
			initialX = -totalWidth;
		}
		gsap.set(track, { x: initialX });

		// Track state
		var state = {
			x: initialX,
			isDragging: false,
			bounds: {
				minX: -totalWidth * 2, // Two sets before
				maxX: 0               // Start of first clone set
			}
		};

		/**
		 * Wrap position to create infinite loop effect
		 */
		function wrapPosition(x) {
			// If we've scrolled past the clones on either end, wrap to the original set
			if (x > state.bounds.maxX) {
				// Wrapped past the start, jump to equivalent position in originals
				return x - totalWidth;
			} else if (x < state.bounds.minX + totalWidth) {
				// Wrapped past the end, jump to equivalent position in originals
				return x + totalWidth;
			}
			return x;
		}

		/**
		 * Update position and handle wrapping
		 */
		function updatePosition(newX, animate) {
			var wrappedX = wrapPosition(newX);

			// If position wrapped, instantly teleport then continue
			if (wrappedX !== newX) {
				gsap.set(track, { x: wrappedX });
				state.x = wrappedX;
			} else if (animate) {
				gsap.to(track, {
					x: newX,
					duration: CONFIG.animationDuration,
					ease: CONFIG.animationEase,
					onUpdate: function() {
						state.x = gsap.getProperty(track, 'x');
					},
					onComplete: function() {
						state.x = newX;
						// Check wrap after animation
						var finalWrapped = wrapPosition(state.x);
						if (finalWrapped !== state.x) {
							gsap.set(track, { x: finalWrapped });
							state.x = finalWrapped;
						}
					}
				});
			} else {
				gsap.set(track, { x: newX });
				state.x = newX;
			}
		}

		/**
		 * Initialize GSAP Draggable
		 */
		var draggable = Draggable.create(track, {
			type: 'x',
			inertia: true,
			bounds: { minX: -totalWidth * 3, maxX: totalWidth },
			edgeResistance: 0.65,
			throwResistance: 2000,
			onDragStart: function() {
				state.isDragging = true;
			},
			onDrag: function() {
				state.x = this.x;
				// Handle wrap during drag
				var wrapped = wrapPosition(state.x);
				if (wrapped !== state.x) {
					gsap.set(track, { x: wrapped });
					this.update();
					state.x = wrapped;
				}
			},
			onThrowUpdate: function() {
				state.x = this.x;
				// Handle wrap during throw
				var wrapped = wrapPosition(state.x);
				if (wrapped !== state.x) {
					gsap.set(track, { x: wrapped });
					this.update();
					state.x = wrapped;
				}
			},
			onDragEnd: function() {
				state.isDragging = false;
			},
			onThrowComplete: function() {
				state.isDragging = false;
				// Final wrap check
				var wrapped = wrapPosition(state.x);
				if (wrapped !== state.x) {
					gsap.set(track, { x: wrapped });
					state.x = wrapped;
				}
			}
		})[0];

		/**
		 * Position arrow at center of card image (not whole card)
		 */
		function positionArrow() {
			if (!arrow) return;
			var cardImage = firstCard.querySelector('.cph-scroller__card-image');
			if (cardImage) {
				var imageHeight = cardImage.getBoundingClientRect().height;
				arrow.style.top = (imageHeight / 2) + 'px';
			}
		}
		positionArrow();

		/**
		 * Arrow click handler
		 */
		if (arrow) {
			arrow.addEventListener('click', function(e) {
				e.preventDefault();

				if (state.isDragging) {
					return;
				}

				// Direction determines scroll direction
				// Left arrow = scroll content left (x decreases, next cards come from right)
				// Right arrow = scroll content right (x increases, next cards come from left)
				var scrollAmount = direction === 'left' ? -cardTotalWidth : cardTotalWidth;
				var targetX = state.x + scrollAmount;

				gsap.to(track, {
					x: targetX,
					duration: CONFIG.animationDuration,
					ease: CONFIG.animationEase,
					onUpdate: function() {
						state.x = gsap.getProperty(track, 'x');
						var wrapped = wrapPosition(state.x);
						if (wrapped !== state.x) {
							gsap.set(track, { x: wrapped });
							state.x = wrapped;
						}
					},
					onComplete: function() {
						state.x = gsap.getProperty(track, 'x');
						var wrapped = wrapPosition(state.x);
						if (wrapped !== state.x) {
							gsap.set(track, { x: wrapped });
							state.x = wrapped;
						}
					}
				});
			});
		}

		// Mark as initialized
		container.dataset.scrollerInit = 'true';

		// Store instance for cleanup/reference
		var instance = {
			container: container,
			draggable: draggable,
			state: state,
			recalculate: function() {
				// Recalculate dimensions on resize
				var newCardRect = firstCard.getBoundingClientRect();
				var newCardWidth = newCardRect.width;
				var newTrackStyle = getComputedStyle(track);
				var newGap = parseFloat(newTrackStyle.gap) || 178;
				var newCardTotalWidth = newCardWidth + newGap;
				var newTotalWidth = newCardTotalWidth * cardCount;

				// Update calculations used by arrow click
				cardTotalWidth = newCardTotalWidth;
				totalWidth = newTotalWidth;

				// Update bounds
				state.bounds.minX = -totalWidth * 2;
				state.bounds.maxX = 0;

				// Reposition arrow
				positionArrow();

				// For right direction, recalculate position to keep rightmost cards visible
				if (direction === 'right') {
					var newViewportWidth = viewport.getBoundingClientRect().width;
					var newX = newViewportWidth - (2 * totalWidth);
					gsap.set(track, { x: newX });
					state.x = newX;
				}
			}
		};

		instances.push(instance);
	}

	/**
	 * Initialize all scrollers on the page
	 */
	function initAll() {
		var scrollers = document.querySelectorAll(CONFIG.selector);
		scrollers.forEach(initScroller);
	}

	/**
	 * Check if GSAP and required plugins are available
	 */
	function checkDependencies() {
		if (typeof gsap === 'undefined') {
			console.warn('CPH Horizontal Scroller: GSAP not found');
			return false;
		}
		if (typeof Draggable === 'undefined') {
			console.warn('CPH Horizontal Scroller: Draggable plugin not found');
			return false;
		}
		return true;
	}

	/**
	 * Main initialization
	 */
	function init() {
		if (!checkDependencies()) {
			return;
		}

		// Register plugins
		gsap.registerPlugin(Draggable);
		if (typeof InertiaPlugin !== 'undefined') {
			gsap.registerPlugin(InertiaPlugin);
		}

		initAll();
	}

	/**
	 * Handle window resize - recalculate dimensions for vw units
	 */
	var resizeTimer = null;
	function handleResize() {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function() {
			instances.forEach(function(instance) {
				if (instance.recalculate) {
					instance.recalculate();
				}
			});
		}, 250);
	}

	/**
	 * DOM Ready handler
	 */
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}

	// Listen for resize events
	window.addEventListener('resize', handleResize);

	/**
	 * MutationObserver for dynamically added scrollers
	 */
	var observerTimer = null;
	var observer = new MutationObserver(function(mutations) {
		clearTimeout(observerTimer);
		observerTimer = setTimeout(function() {
			initAll();
		}, 100);
	});

	observer.observe(document.body, {
		childList: true,
		subtree: true
	});

	/**
	 * Handle Nectar AJAX page loads
	 */
	if (typeof jQuery !== 'undefined') {
		jQuery(document).on('nectar-ajax-loaded', function() {
			setTimeout(initAll, 100);
		});
	}

	/**
	 * Public API
	 */
	window.CPHHorizontalScroller = {
		init: init,
		initAll: initAll,
		initScroller: initScroller,
		instances: instances,
		config: CONFIG
	};

})();

/**
 * CPH Team Member Popup
 *
 * Handles popup functionality for team member cards.
 *
 * @package Salient_Child
 * @since   1.0.0
 */
(function() {
	'use strict';

	var popup = null;
	var isOpen = false;
	var focusedElementBeforeOpen = null;

	/**
	 * DOM element references
	 */
	var elements = {
		popup: null,
		backdrop: null,
		closeBtn: null,
		container: null,
		image: null,
		name: null,
		title: null,
		bio: null,
		loading: null
	};

	/**
	 * Cache DOM elements
	 */
	function cacheElements() {
		elements.popup = document.getElementById('cph-team-popup');
		if (!elements.popup) {
			return false;
		}

		elements.backdrop = elements.popup.querySelector('.cph-team-popup__backdrop');
		elements.closeBtn = elements.popup.querySelector('.cph-team-popup__close');
		elements.container = elements.popup.querySelector('.cph-team-popup__container');
		elements.image = document.getElementById('cph-team-popup-image');
		elements.name = document.getElementById('cph-team-popup-name');
		elements.title = document.getElementById('cph-team-popup-title');
		elements.bio = document.getElementById('cph-team-popup-bio');
		elements.loading = elements.popup.querySelector('.cph-team-popup__loading');

		return true;
	}

	/**
	 * Show loading state
	 */
	function showLoading() {
		elements.popup.classList.add('is-loading');
	}

	/**
	 * Hide loading state
	 */
	function hideLoading() {
		elements.popup.classList.remove('is-loading');
	}

	/**
	 * Populate popup with team member data
	 *
	 * @param {Object} data Team member data
	 */
	function populatePopup(data) {
		elements.name.textContent = data.name || '';
		elements.title.textContent = data.job_title || '';
		elements.bio.innerHTML = data.bio || '';

		if (data.image_url) {
			elements.image.src = data.image_url;
			elements.image.alt = data.name || '';
		} else {
			elements.image.src = '';
			elements.image.alt = '';
		}
	}

	/**
	 * Clear popup content
	 */
	function clearPopup() {
		elements.name.textContent = '';
		elements.title.textContent = '';
		elements.bio.innerHTML = '';
		elements.image.src = '';
		elements.image.alt = '';
	}

	/**
	 * Open the popup
	 */
	function openPopup() {
		if (isOpen) {
			return;
		}

		// Store currently focused element
		focusedElementBeforeOpen = document.activeElement;

		// Lock body scroll
		document.body.classList.add('cph-popup-open');

		// Show popup
		elements.popup.setAttribute('aria-hidden', 'false');
		isOpen = true;

		// Focus the close button after animation
		setTimeout(function() {
			elements.closeBtn.focus();
		}, 400);
	}

	/**
	 * Close the popup
	 */
	function closePopup() {
		if (!isOpen) {
			return;
		}

		// Hide popup
		elements.popup.setAttribute('aria-hidden', 'true');
		isOpen = false;

		// Unlock body scroll
		document.body.classList.remove('cph-popup-open');

		// Restore focus
		if (focusedElementBeforeOpen) {
			focusedElementBeforeOpen.focus();
		}

		// Clear content after animation
		setTimeout(function() {
			clearPopup();
		}, 400);
	}

	/**
	 * Fetch team member data via AJAX
	 *
	 * @param {number} postId Team member post ID
	 */
	function fetchTeamMember(postId) {
		if (typeof btiTeamPopup === 'undefined') {
			console.error('CPH Team Popup: AJAX configuration not found');
			return;
		}

		showLoading();
		openPopup();

		var formData = new FormData();
		formData.append('action', 'cph_get_team_member');
		formData.append('post_id', postId);
		formData.append('nonce', btiTeamPopup.nonce);

		fetch(btiTeamPopup.ajaxUrl, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin'
		})
		.then(function(response) {
			return response.json();
		})
		.then(function(response) {
			hideLoading();

			if (response.success && response.data) {
				populatePopup(response.data);
			} else {
				console.error('CPH Team Popup:', response.data?.message || 'Unknown error');
				closePopup();
			}
		})
		.catch(function(error) {
			hideLoading();
			console.error('CPH Team Popup: Fetch error', error);
			closePopup();
		});
	}

	/**
	 * Handle team card button clicks (scroller and grid)
	 *
	 * @param {Event} e Click event
	 */
	function handleReadMoreClick(e) {
		// Check for scroller "Read More" buttons or grid card buttons
		var button = e.target.closest('.cph-scroller__card-button[data-team-id], .cph-team-grid__card-button[data-team-id]');

		if (!button) {
			return;
		}

		e.preventDefault();
		e.stopPropagation();

		var teamId = button.getAttribute('data-team-id');

		if (teamId) {
			fetchTeamMember(teamId);
		}
	}

	/**
	 * Handle keyboard events
	 *
	 * @param {KeyboardEvent} e Keyboard event
	 */
	function handleKeydown(e) {
		if (!isOpen) {
			return;
		}

		// Close on Escape
		if (e.key === 'Escape') {
			e.preventDefault();
			closePopup();
			return;
		}

		// Trap focus within popup
		if (e.key === 'Tab') {
			var focusableElements = elements.popup.querySelectorAll(
				'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
			);

			var firstElement = focusableElements[0];
			var lastElement = focusableElements[focusableElements.length - 1];

			if (e.shiftKey && document.activeElement === firstElement) {
				e.preventDefault();
				lastElement.focus();
			} else if (!e.shiftKey && document.activeElement === lastElement) {
				e.preventDefault();
				firstElement.focus();
			}
		}
	}

	/**
	 * Bind event listeners
	 */
	function bindEvents() {
		// Close button click
		if (elements.closeBtn) {
			elements.closeBtn.addEventListener('click', closePopup);
		}

		// Backdrop click
		if (elements.backdrop) {
			elements.backdrop.addEventListener('click', closePopup);
		}

		// Read More button clicks (delegated)
		document.addEventListener('click', handleReadMoreClick);

		// Keyboard events
		document.addEventListener('keydown', handleKeydown);
	}

	/**
	 * Initialize popup functionality
	 */
	function initPopup() {
		if (!cacheElements()) {
			// Popup shell not found, may load later
			return;
		}

		bindEvents();
	}

	/**
	 * DOM Ready handler
	 */
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initPopup);
	} else {
		initPopup();
	}

	/**
	 * MutationObserver for dynamically added popup
	 */
	var popupObserver = new MutationObserver(function(mutations) {
		if (elements.popup) {
			return;
		}

		var popupElement = document.getElementById('cph-team-popup');
		if (popupElement) {
			initPopup();
			popupObserver.disconnect();
		}
	});

	popupObserver.observe(document.body, {
		childList: true,
		subtree: true
	});

	/**
	 * Public API
	 */
	window.CPHTeamPopup = {
		open: fetchTeamMember,
		close: closePopup,
		isOpen: function() { return isOpen; }
	};

})();
