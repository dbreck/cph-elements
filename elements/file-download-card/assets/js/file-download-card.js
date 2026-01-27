/**
 * File Download Card - Frontend JavaScript
 *
 * Initializes FancyBox for image preview functionality.
 * FancyBox3 auto-initializes elements with data-fancybox attribute,
 * but we add explicit initialization for better control.
 *
 * @package Salient Child Theme
 */

(function($) {
    'use strict';

    /**
     * Initialize FancyBox on file download card preview links
     */
    function initFileDownloadCards() {
        var $previewLinks = $('.file-download-card__preview-link[data-fancybox]');

        if ($previewLinks.length === 0) {
            return;
        }

        // Check if FancyBox is available
        if (typeof $.fancybox === 'undefined') {
            console.warn('File Download Card: FancyBox is not loaded.');
            return;
        }

        // FancyBox3 auto-initializes with data-fancybox attribute
        // This explicit bind ensures proper initialization after AJAX loads
        $('[data-fancybox="fdc-gallery"]').fancybox({
            // Animation
            animationEffect: 'fade',
            animationDuration: 300,
            transitionEffect: 'fade',
            transitionDuration: 300,

            // UI options
            buttons: [
                'zoom',
                'close'
            ],

            // Behavior
            loop: true,
            protect: false,
            infobar: true,
            toolbar: true,

            // Click behavior
            clickContent: 'close',
            clickSlide: 'close',

            // Touch
            touch: {
                vertical: true
            },

            // Callbacks
            afterLoad: function(instance, current) {
                $('body').addClass('fancybox-file-preview-open');
            },
            afterClose: function() {
                $('body').removeClass('fancybox-file-preview-open');
            }
        });
    }

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        initFileDownloadCards();
    });

    /**
     * Re-initialize after AJAX content loads (for WPBakery frontend editor)
     */
    $(document).on('vc-full-width-row-single', function() {
        initFileDownloadCards();
    });

    /**
     * Re-initialize after any AJAX call completes
     */
    $(document).ajaxComplete(function() {
        // Small delay to ensure DOM is updated
        setTimeout(initFileDownloadCards, 100);
    });

})(jQuery);
