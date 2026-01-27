/**
 * CPH Gallery Slider
 *
 * Center-mode carousel using GSAP with optional Draggable support.
 * Features 3D depth effect, infinite loop, and autoplay.
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
		selector: '.cph-gallery-slider',
		trackSelector: '.cph-gallery-slider__track',
		viewportSelector: '.cph-gallery-slider__viewport',
		slideSelector: '.cph-gallery-slider__slide',
		arrowPrevSelector: '.cph-gallery-slider__arrow--prev',
		arrowNextSelector: '.cph-gallery-slider__arrow--next',
		paginationSelector: '.cph-gallery-slider__pagination',
		dotSelector: '.cph-gallery-slider__dot'
	};

	/**
	 * Store for slider instances
	 */
	var instances = [];

	/**
	 * Initialize a single slider instance
	 *
	 * @param {HTMLElement} container The slider container element
	 */
	function initSlider(container) {
		// Skip if already initialized
		if (container.dataset.sliderInit === 'true') {
			return;
		}

		var track = container.querySelector(CONFIG.trackSelector);
		var viewport = container.querySelector(CONFIG.viewportSelector);
		var slides = container.querySelectorAll(CONFIG.slideSelector);
		var arrowPrev = container.querySelector(CONFIG.arrowPrevSelector);
		var arrowNext = container.querySelector(CONFIG.arrowNextSelector);
		var pagination = container.querySelector(CONFIG.paginationSelector);
		var dots = pagination ? pagination.querySelectorAll(CONFIG.dotSelector) : [];

		if (!track || !viewport || slides.length < 2) {
			return;
		}

		// Parse data attributes
		var settings = {
			startSlide: parseInt(container.dataset.startSlide, 10) || 0,
			infinite: container.dataset.infinite === 'true',
			autoplay: container.dataset.autoplay === 'true',
			autoplaySpeed: parseInt(container.dataset.autoplaySpeed, 10) || 5000,
			pauseOnHover: container.dataset.pauseOnHover === 'true',
			transition: parseInt(container.dataset.transition, 10) || 600,
			draggable: container.dataset.draggable === 'true',
			effectStyle: container.dataset.effectStyle || 'classic',
			easing: container.dataset.easing || 'power2.out',
			sideScale: parseFloat(container.dataset.sideScale) || 0.75,
			sideOpacity: parseFloat(container.dataset.sideOpacity) || 0.6,
			rotateY: parseFloat(container.dataset.rotateY) || 45,
			zOffset: parseFloat(container.dataset.zOffset) || -200,
			enableShadow: container.dataset.enableShadow === 'true',
			enableBlur: container.dataset.enableBlur === 'true',
			blurAmount: parseFloat(container.dataset.blurAmount) || 4
		};

		var slideCount = slides.length;
		var currentIndex = 0;
		var isAnimating = false;
		var autoplayTimer = null;
		var isPaused = false;

		// Set CSS variable for transition duration
		container.style.setProperty('--transition-duration', settings.transition + 'ms');

		/**
		 * Get computed dimensions (unscaled)
		 */
		function getDimensions() {
			// Use container width for centering (it's what clips the content)
			var containerRect = container.getBoundingClientRect();
			var computedStyle = getComputedStyle(container);
			var gap = parseFloat(computedStyle.getPropertyValue('--slide-gap')) || 40;

			// Get the unscaled slide width from CSS, not getBoundingClientRect
			// (getBoundingClientRect returns scaled dimensions when transform is applied)
			var slideWidthValue = computedStyle.getPropertyValue('--slide-width').trim();
			var slideWidth;

			if (slideWidthValue.indexOf('%') > -1) {
				// Percentage of container
				slideWidth = containerRect.width * (parseFloat(slideWidthValue) / 100);
			} else {
				// Pixel or other unit - parse it
				slideWidth = parseFloat(slideWidthValue) || slides[0].offsetWidth;
			}

			return {
				containerWidth: containerRect.width,
				slideWidth: slideWidth,
				gap: gap,
				slideTotal: slideWidth + gap
			};
		}

		/**
		 * Calculate position to center a slide
		 */
		function getCenterPosition(index, dims) {
			var slideTotal = dims.slideTotal;
			var containerCenter = dims.containerWidth / 2;
			var slideCenter = dims.slideWidth / 2;

			// Position to center the slide at the given index
			return containerCenter - slideCenter - (index * slideTotal);
		}

		/**
		 * Update slide states (active class and advanced 3D effects)
		 */
		function updateSlideStates(animate) {
			var dims = getDimensions();
			var duration = animate ? settings.transition / 1000 : 0;

			slides.forEach(function(slide, index) {
				var isActive = index === currentIndex;
				var distance = index - currentIndex; // Signed distance (negative = left, positive = right)
				var absDistance = Math.abs(distance);

				// Toggle active class
				if (isActive) {
					slide.classList.add('is-active');
				} else {
					slide.classList.remove('is-active');
				}

				// Skip transforms for flat style
				if (settings.effectStyle === 'flat') {
					return;
				}

				// Calculate transform values based on effect style
				var animProps = {
					duration: duration,
					ease: settings.easing,
					overwrite: 'auto'
				};

				if (isActive) {
					// Active slide - reset all transforms
					animProps.scale = 1;
					animProps.opacity = 1;
					animProps.rotationY = 0;
					animProps.z = 0;
					animProps.filter = 'blur(0px)';
					animProps.boxShadow = 'none';
				} else {
					// Calculate base values
					var scale = settings.sideScale;
					var opacity = settings.sideOpacity;

					// Further reduce for slides further away
					if (absDistance > 1) {
						scale = Math.max(settings.sideScale * 0.9, 0.5);
						opacity = Math.max(settings.sideOpacity * 0.7, 0.3);
					}

					animProps.scale = scale;
					animProps.opacity = opacity;

					// Effect-specific transforms
					switch (settings.effectStyle) {
						case 'coverflow':
							// Rotation based on position (left slides rotate right, right slides rotate left)
							var rotateY = distance < 0 ? settings.rotateY : -settings.rotateY;
							animProps.rotationY = rotateY;
							animProps.transformOrigin = distance < 0 ? 'right center' : 'left center';
							break;

						case 'carousel':
							// Cylinder effect - outer edges rotate away from viewer
							// Left slides: negative rotation (left edge goes back)
							// Right slides: positive rotation (right edge goes back)
							var rotateY = distance < 0 ? -settings.rotateY : settings.rotateY;
							animProps.rotationY = rotateY;
							animProps.z = settings.zOffset * absDistance;
							animProps.transformOrigin = distance < 0 ? 'right center' : 'left center';
							break;

						case 'custom':
							// Allow custom combinations
							if (settings.rotateY > 0) {
								var rotateY = distance < 0 ? settings.rotateY : -settings.rotateY;
								animProps.rotationY = rotateY;
								animProps.transformOrigin = distance < 0 ? 'right center' : 'left center';
							}
							if (settings.zOffset !== 0) {
								animProps.z = settings.zOffset * absDistance;
							}
							break;

						default: // 'classic'
							// Just scale and opacity (already set above)
							break;
					}

					// Blur effect
					if (settings.enableBlur) {
						var blurValue = settings.blurAmount * absDistance;
						animProps.filter = 'blur(' + blurValue + 'px)';
					}

					// Shadow effect
					if (settings.enableShadow) {
						var shadowIntensity = 0.2 + (absDistance * 0.1);
						var shadowBlur = 20 + (absDistance * 10);
						animProps.boxShadow = '0 ' + (10 + absDistance * 5) + 'px ' + shadowBlur + 'px rgba(0,0,0,' + shadowIntensity + ')';
					}
				}

				gsap.to(slide, animProps);
			});
		}

		/**
		 * Go to a specific slide
		 */
		function goToSlide(index, animate) {
			if (isAnimating) {
				return;
			}

			if (typeof animate === 'undefined') {
				animate = true;
			}

			// Handle bounds for non-infinite mode
			if (!settings.infinite) {
				if (index < 0) {
					index = 0;
				} else if (index >= slideCount) {
					index = slideCount - 1;
				}
			} else {
				// Wrap for infinite mode
				if (index < 0) {
					index = slideCount - 1;
				} else if (index >= slideCount) {
					index = 0;
				}
			}

			currentIndex = index;
			var dims = getDimensions();
			var targetX = getCenterPosition(currentIndex, dims);
			var duration = animate ? settings.transition / 1000 : 0;

			isAnimating = true;

			gsap.to(track, {
				x: targetX,
				duration: duration,
				ease: settings.easing,
				onComplete: function() {
					isAnimating = false;
					updateArrowStates();
					updatePagination();
				}
			});

			updateSlideStates(animate);
		}

		/**
		 * Go to next slide
		 */
		function nextSlide() {
			goToSlide(currentIndex + 1);
		}

		/**
		 * Go to previous slide
		 */
		function prevSlide() {
			goToSlide(currentIndex - 1);
		}

		/**
		 * Update arrow visibility for non-infinite mode
		 */
		function updateArrowStates() {
			if (!arrowPrev || !arrowNext || settings.infinite) {
				return;
			}

			if (currentIndex <= 0) {
				arrowPrev.classList.add('is-hidden');
			} else {
				arrowPrev.classList.remove('is-hidden');
			}

			if (currentIndex >= slideCount - 1) {
				arrowNext.classList.add('is-hidden');
			} else {
				arrowNext.classList.remove('is-hidden');
			}
		}

		/**
		 * Update pagination dots active state
		 */
		function updatePagination() {
			if (!dots || dots.length === 0) {
				return;
			}

			dots.forEach(function(dot, index) {
				if (index === currentIndex) {
					dot.classList.add('is-active');
					dot.setAttribute('aria-selected', 'true');
				} else {
					dot.classList.remove('is-active');
					dot.setAttribute('aria-selected', 'false');
				}
			});
		}

		/**
		 * Start autoplay
		 */
		function startAutoplay() {
			if (!settings.autoplay || isPaused) {
				return;
			}

			stopAutoplay();
			autoplayTimer = setInterval(function() {
				if (!isPaused) {
					nextSlide();
				}
			}, settings.autoplaySpeed);
		}

		/**
		 * Stop autoplay
		 */
		function stopAutoplay() {
			if (autoplayTimer) {
				clearInterval(autoplayTimer);
				autoplayTimer = null;
			}
		}

		/**
		 * Pause autoplay (on hover)
		 */
		function pauseAutoplay() {
			isPaused = true;
		}

		/**
		 * Resume autoplay (on mouse leave)
		 */
		function resumeAutoplay() {
			isPaused = false;
			if (settings.autoplay) {
				startAutoplay();
			}
		}

		/**
		 * Initialize draggable functionality
		 */
		function initDraggable() {
			if (!settings.draggable || typeof Draggable === 'undefined') {
				return;
			}

			container.classList.add('cph-gallery-slider--draggable');

			var dims = getDimensions();
			var startX = 0;
			var startIndex = 0;
			var draggableInstance = null;

			/**
			 * Generate snap points for all slides (centered positions)
			 */
			function getSnapPoints() {
				var d = getDimensions();
				var points = [];
				for (var i = 0; i < slideCount; i++) {
					points.push(getCenterPosition(i, d));
				}
				return points;
			}

			/**
			 * Find the closest snap point and return the corresponding index
			 */
			function getClosestSlideIndex(x) {
				var d = getDimensions();
				var closestIndex = 0;
				var closestDistance = Infinity;

				for (var i = 0; i < slideCount; i++) {
					var snapPoint = getCenterPosition(i, d);
					var distance = Math.abs(x - snapPoint);
					if (distance < closestDistance) {
						closestDistance = distance;
						closestIndex = i;
					}
				}

				return closestIndex;
			}

			draggableInstance = Draggable.create(track, {
				type: 'x',
				inertia: typeof InertiaPlugin !== 'undefined',
				edgeResistance: 0.85,
				snap: {
					x: function(endValue) {
						// Find the closest slide position and snap to it
						var targetIndex = getClosestSlideIndex(endValue);

						// Constrain for non-infinite mode
						if (!settings.infinite) {
							targetIndex = Math.max(0, Math.min(slideCount - 1, targetIndex));
						}

						return getCenterPosition(targetIndex, getDimensions());
					}
				},
				onDragStart: function() {
					dims = getDimensions();
					startX = this.x;
					startIndex = currentIndex;
					isAnimating = true;
					pauseAutoplay();
				},
				onDrag: function() {
					// Update 3D effects during drag based on current position
					var targetIndex = getClosestSlideIndex(this.x);

					// Constrain for non-infinite mode
					if (!settings.infinite) {
						targetIndex = Math.max(0, Math.min(slideCount - 1, targetIndex));
					}

					// Preview the target slide
					if (targetIndex !== currentIndex) {
						currentIndex = targetIndex;
						updateSlideStates(false);
						updatePagination();
					}
				},
				onThrowUpdate: function() {
					// Update 3D effects during throw/inertia
					var targetIndex = getClosestSlideIndex(this.x);

					if (!settings.infinite) {
						targetIndex = Math.max(0, Math.min(slideCount - 1, targetIndex));
					}

					if (targetIndex !== currentIndex) {
						currentIndex = targetIndex;
						updateSlideStates(false);
						updatePagination();
					}
				},
				onDragEnd: function() {
					// Determine final slide index from snap position
					var finalX = this.endX !== undefined ? this.endX : this.x;
					var targetIndex = getClosestSlideIndex(finalX);

					if (!settings.infinite) {
						targetIndex = Math.max(0, Math.min(slideCount - 1, targetIndex));
					}

					currentIndex = targetIndex;
					updateSlideStates(true);
					isAnimating = false;
					updateArrowStates();
					updatePagination();

					if (settings.autoplay && settings.pauseOnHover) {
						resumeAutoplay();
					}
				}
			})[0];
		}

		/**
		 * Bind event listeners
		 */
		function bindEvents() {
			// Arrow clicks
			if (arrowPrev) {
				arrowPrev.addEventListener('click', function(e) {
					e.preventDefault();
					prevSlide();
				});
			}

			if (arrowNext) {
				arrowNext.addEventListener('click', function(e) {
					e.preventDefault();
					nextSlide();
				});
			}

			// Pagination dot clicks
			if (dots && dots.length > 0) {
				dots.forEach(function(dot) {
					dot.addEventListener('click', function(e) {
						e.preventDefault();
						var index = parseInt(dot.dataset.index, 10);
						if (!isNaN(index)) {
							goToSlide(index);
						}
					});
				});
			}

			// Pause on hover
			if (settings.autoplay && settings.pauseOnHover) {
				container.addEventListener('mouseenter', pauseAutoplay);
				container.addEventListener('mouseleave', resumeAutoplay);
			}

			// Keyboard navigation
			container.addEventListener('keydown', function(e) {
				if (e.key === 'ArrowLeft') {
					e.preventDefault();
					prevSlide();
				} else if (e.key === 'ArrowRight') {
					e.preventDefault();
					nextSlide();
				}
			});

			// Make container focusable for keyboard nav
			if (!container.hasAttribute('tabindex')) {
				container.setAttribute('tabindex', '0');
			}
		}

		/**
		 * Handle resize
		 */
		function handleResize() {
			setSlideWidths();
			goToSlide(currentIndex, false);
		}

		/**
		 * Set slide widths and gaps in pixels (for precise control)
		 */
		function setSlideWidths() {
			var computedStyle = getComputedStyle(container);
			var containerWidth = container.getBoundingClientRect().width;
			var slideWidthValue = computedStyle.getPropertyValue('--slide-width').trim();
			var gapValue = computedStyle.getPropertyValue('--slide-gap').trim();
			var slideWidth;
			var gap;

			// Calculate slide width
			if (slideWidthValue.indexOf('%') > -1) {
				slideWidth = containerWidth * (parseFloat(slideWidthValue) / 100);
			} else {
				slideWidth = parseFloat(slideWidthValue) || 400;
			}

			// Calculate gap
			gap = parseFloat(gapValue) || 40;

			// Set explicit pixel width and margin on each slide
			slides.forEach(function(slide, index) {
				slide.style.width = slideWidth + 'px';
				// Add margin-right for gap (except on last slide)
				if (index < slides.length - 1) {
					slide.style.marginRight = gap + 'px';
				} else {
					slide.style.marginRight = '0';
				}
			});

			// Force layout recalculation
			void track.offsetWidth;
		}

		/**
		 * Initialize the slider
		 */
		function init() {
			// Set slide widths explicitly (CSS % doesn't work correctly in flex track)
			setSlideWidths();

			// Validate start slide index
			var startIndex = settings.startSlide;
			if (startIndex < 0) {
				startIndex = 0;
			} else if (startIndex >= slideCount) {
				startIndex = slideCount - 1;
			}

			// Initial positioning
			goToSlide(startIndex, false);

			// Ensure pagination reflects the start slide
			updatePagination();

			// Initialize draggable
			initDraggable();

			// Bind events
			bindEvents();

			// Start autoplay
			if (settings.autoplay) {
				startAutoplay();
			}

			// Mark as initialized
			container.dataset.sliderInit = 'true';
			container.classList.add('is-initialized');
		}

		// Initialize
		init();

		// Store instance
		var instance = {
			container: container,
			goToSlide: goToSlide,
			nextSlide: nextSlide,
			prevSlide: prevSlide,
			getCurrentIndex: function() { return currentIndex; },
			startAutoplay: startAutoplay,
			stopAutoplay: stopAutoplay,
			recalculate: handleResize,
			setSlideWidths: setSlideWidths
		};

		instances.push(instance);
	}

	/**
	 * Initialize all sliders on the page
	 */
	function initAll() {
		var sliders = document.querySelectorAll(CONFIG.selector);
		sliders.forEach(initSlider);
	}

	/**
	 * Check if GSAP is available
	 */
	function checkDependencies() {
		if (typeof gsap === 'undefined') {
			console.warn('CPH Gallery Slider: GSAP not found');
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

		// Register plugins if available
		if (typeof Draggable !== 'undefined') {
			gsap.registerPlugin(Draggable);
		}
		if (typeof InertiaPlugin !== 'undefined') {
			gsap.registerPlugin(InertiaPlugin);
		}

		initAll();
	}

	/**
	 * Handle window resize
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
	 * MutationObserver for dynamically added sliders
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
	window.CPHGallerySlider = {
		init: init,
		initAll: initAll,
		initSlider: initSlider,
		instances: instances,
		config: CONFIG
	};

})();
