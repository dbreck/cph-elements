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

		var slides = container.querySelectorAll('.cph-testimonials__slide');
		var dots   = container.querySelectorAll('.cph-testimonials__dot');

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

		// Pause on hover.
		container.addEventListener('mouseenter', stopAutoplay);
		container.addEventListener('mouseleave', startAutoplay);

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
