/**
 * CPH Testimonials - Slider Logic
 *
 * Vanilla JS fade slider with dot navigation, autoplay, and transition styles.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

(function() {
	'use strict';

	function initSlider(container) {
		if (container.dataset.init === 'true') {
			return;
		}

		var slides   = container.querySelectorAll('.cph-testimonials__slide');
		var dots     = container.querySelectorAll('.cph-testimonials__dot');
		var prevBtn  = container.querySelector('.cph-testimonials__arrow--prev');
		var nextBtn  = container.querySelector('.cph-testimonials__arrow--next');

		if (slides.length < 2) {
			return;
		}

		var autoplay      = container.dataset.autoplay === 'true';
		var autoplaySpeed = parseInt(container.dataset.autoplaySpeed, 10) || 6000;
		var currentIndex  = 0;
		var isAnimating   = false;
		var timer         = null;
		var slider        = container.querySelector('.cph-testimonials__slider');

		// Read transition duration from CSS custom property.
		var style = getComputedStyle(container);
		var transitionDuration = parseInt(style.getPropertyValue('--testimonials-transition'), 10) || 600;

		// Check if a fixed section height is set via modifier class.
		var isFixedHeight = container.classList.contains('cph-testimonials--fixed-height');

		// Add height transition to slider for smooth resizing (only needed in auto mode).
		if (!isFixedHeight) {
			slider.style.transition = 'min-height ' + transitionDuration + 'ms ease';
		}

		/**
		 * Measure a single slide's height by temporarily making it visible.
		 */
		function measureSlide(slide) {
			var wasActive = slide.classList.contains('is-active');

			if (!wasActive) {
				slide.style.position   = 'relative';
				slide.style.opacity    = '0';
				slide.style.visibility = 'hidden';
				slide.style.transform  = 'none';
			}

			var height = slide.offsetHeight;

			if (!wasActive) {
				slide.style.position   = '';
				slide.style.opacity    = '';
				slide.style.visibility = '';
				slide.style.transform  = '';
			}

			return height;
		}

		/**
		 * Set slider height. Skips when section has fixed height,
		 * otherwise measures all slides and uses the tallest.
		 */
		function updateHeight() {
			if (isFixedHeight) {
				return;
			}

			var maxHeight = 0;
			for (var i = 0; i < slides.length; i++) {
				var h = measureSlide(slides[i]);
				if (h > maxHeight) {
					maxHeight = h;
				}
			}
			if (maxHeight > 0) {
				slider.style.minHeight = maxHeight + 'px';
			}
		}

		function goTo(index) {
			if (index === currentIndex || isAnimating) {
				return;
			}

			isAnimating = true;

			var oldSlide = slides[currentIndex];

			// Exit old slide.
			oldSlide.classList.remove('is-active');
			oldSlide.classList.add('is-exiting');

			if (dots[currentIndex]) {
				dots[currentIndex].classList.remove('is-active');
				dots[currentIndex].setAttribute('aria-selected', 'false');
			}

			// Enter new slide.
			currentIndex = index;
			slides[currentIndex].classList.add('is-active');

			if (dots[currentIndex]) {
				dots[currentIndex].classList.add('is-active');
				dots[currentIndex].setAttribute('aria-selected', 'true');
			}

			// Clean up exiting slide after transition completes.
			setTimeout(function() {
				oldSlide.classList.remove('is-exiting');
				isAnimating = false;
			}, transitionDuration);
		}

		function next() {
			goTo((currentIndex + 1) % slides.length);
		}

		function prev() {
			goTo((currentIndex - 1 + slides.length) % slides.length);
		}

		function startAutoplay() {
			if (autoplay && !timer) {
				timer = setInterval(next, autoplaySpeed);
			}
		}

		function stopAutoplay() {
			if (timer) {
				clearInterval(timer);
				timer = null;
			}
		}

		function resetAutoplay() {
			stopAutoplay();
			startAutoplay();
		}

		// Dot clicks.
		dots.forEach(function(dot, i) {
			dot.addEventListener('click', function() {
				goTo(i);
				resetAutoplay();
			});
		});

		// Arrow clicks.
		if (prevBtn) {
			prevBtn.addEventListener('click', function() {
				prev();
				resetAutoplay();
			});
		}
		if (nextBtn) {
			nextBtn.addEventListener('click', function() {
				next();
				resetAutoplay();
			});
		}

		// Pause on hover.
		container.addEventListener('mouseenter', stopAutoplay);
		container.addEventListener('mouseleave', startAutoplay);

		// Swipe / drag support (touch + desktop pointer drag).
		var swipeStartX = 0;
		var swipeStartY = 0;
		var isSwipeActive = false;
		var activePointerId = null;
		var suppressClick = false;
		var swipeThreshold = 44;

		function startSwipe(point, pointerId) {
			if (isAnimating) {
				return;
			}
			swipeStartX = point.clientX;
			swipeStartY = point.clientY;
			isSwipeActive = true;
			activePointerId = pointerId;
			suppressClick = false;
			slider.classList.add('is-dragging');
			stopAutoplay();
		}

		function moveSwipe(point, pointerId) {
			var deltaX;
			var deltaY;

			if (!isSwipeActive) {
				return;
			}
			if (activePointerId !== null && pointerId !== null && activePointerId !== pointerId) {
				return;
			}

			deltaX = point.clientX - swipeStartX;
			deltaY = point.clientY - swipeStartY;

			if (Math.abs(deltaX) > 8 || Math.abs(deltaY) > 8) {
				suppressClick = true;
			}
		}

		function endSwipe(point, pointerId) {
			var deltaX;
			var deltaY;
			var isHorizontalSwipe;

			if (!isSwipeActive) {
				return;
			}
			if (activePointerId !== null && pointerId !== null && activePointerId !== pointerId) {
				return;
			}

			deltaX = point.clientX - swipeStartX;
			deltaY = point.clientY - swipeStartY;
			isHorizontalSwipe = Math.abs(deltaX) > swipeThreshold && Math.abs(deltaX) > Math.abs(deltaY);

			isSwipeActive = false;
			activePointerId = null;
			slider.classList.remove('is-dragging');

			if (Math.abs(deltaX) > 8 || Math.abs(deltaY) > 8) {
				suppressClick = true;
			}

			if (isHorizontalSwipe) {
				if (deltaX < 0) {
					next();
				} else {
					prev();
				}
				resetAutoplay();
				return;
			}

			startAutoplay();
		}

		function cancelSwipe() {
			if (!isSwipeActive) {
				return;
			}
			isSwipeActive = false;
			activePointerId = null;
			slider.classList.remove('is-dragging');
			startAutoplay();
		}

		if (window.PointerEvent) {
			slider.addEventListener('pointerdown', function(e) {
				if (e.pointerType === 'mouse' && e.button !== 0) {
					return;
				}
				startSwipe(e, e.pointerId);
			});

			window.addEventListener('pointermove', function(e) {
				moveSwipe(e, e.pointerId);
			});

			window.addEventListener('pointerup', function(e) {
				endSwipe(e, e.pointerId);
			});

			window.addEventListener('pointercancel', cancelSwipe);
		} else {
			slider.addEventListener('touchstart', function(e) {
				if (!e.changedTouches.length) {
					return;
				}
				startSwipe(e.changedTouches[0], null);
			}, { passive: true });

			window.addEventListener('touchmove', function(e) {
				if (!e.changedTouches.length) {
					return;
				}
				moveSwipe(e.changedTouches[0], null);
			}, { passive: true });

			window.addEventListener('touchend', function(e) {
				if (!e.changedTouches.length) {
					return;
				}
				endSwipe(e.changedTouches[0], null);
			}, { passive: true });

			window.addEventListener('touchcancel', cancelSwipe, { passive: true });

			slider.addEventListener('mousedown', function(e) {
				if (e.button !== 0) {
					return;
				}
				startSwipe(e, null);
			});

			window.addEventListener('mousemove', function(e) {
				moveSwipe(e, null);
			});

			window.addEventListener('mouseup', function(e) {
				if (e.button !== 0) {
					return;
				}
				endSwipe(e, null);
			});
		}

		slider.addEventListener('dragstart', function(e) {
			e.preventDefault();
		});

		// Suppress accidental click activation after a drag gesture.
		slider.addEventListener('click', function(e) {
			if (!suppressClick) {
				return;
			}
			e.preventDefault();
			e.stopPropagation();
			suppressClick = false;
		}, true);

		updateHeight();
		startAutoplay();
		container.dataset.init = 'true';
	}

	function initAll() {
		var containers = document.querySelectorAll('.cph-testimonials');
		for (var i = 0; i < containers.length; i++) {
			initSlider(containers[i]);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initAll);
	} else {
		initAll();
	}

	// Re-measure after fonts/images fully loaded (auto-height mode only).
	window.addEventListener('load', function() {
		var containers = document.querySelectorAll('.cph-testimonials');
		for (var i = 0; i < containers.length; i++) {
			if (containers[i].dataset.init !== 'true') {
				continue;
			}

			// Fixed-height sections don't need JS measurement.
			if (containers[i].classList.contains('cph-testimonials--fixed-height')) {
				continue;
			}

			var sliderEl = containers[i].querySelector('.cph-testimonials__slider');
			var allSlides = containers[i].querySelectorAll('.cph-testimonials__slide');
			if (sliderEl && allSlides.length) {
				var maxH = 0;
				for (var j = 0; j < allSlides.length; j++) {
					var wasActive = allSlides[j].classList.contains('is-active');
					if (!wasActive) {
						allSlides[j].style.position   = 'relative';
						allSlides[j].style.opacity     = '0';
						allSlides[j].style.visibility  = 'hidden';
						allSlides[j].style.transform   = 'none';
					}
					var h = allSlides[j].offsetHeight;
					if (h > maxH) {
						maxH = h;
					}
					if (!wasActive) {
						allSlides[j].style.position   = '';
						allSlides[j].style.opacity     = '';
						allSlides[j].style.visibility  = '';
						allSlides[j].style.transform   = '';
					}
				}
				if (maxH > 0) {
					sliderEl.style.minHeight = maxH + 'px';
				}
			}
		}
	});
})();
