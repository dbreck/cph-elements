/**
 * File Download Card - Admin JavaScript
 *
 * Auto-populates the title field from the selected filename in WPBakery editor.
 *
 * @package CPH_Elements
 */

(function($) {
    'use strict';

    /**
     * Convert filename to readable title
     * Removes extension, replaces dashes/underscores with spaces, title cases
     */
    function filenameToTitle(filename) {
        if (!filename) {
            return '';
        }

        // Remove extension
        var name = filename.replace(/\.[^/.]+$/, '');

        // Replace dashes and underscores with spaces
        name = name.replace(/[-_]/g, ' ');

        // Title case each word
        name = name.replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });

        return name;
    }

    /**
     * Initialize the auto-populate functionality
     */
    function initAutoPopulate() {
        // Listen for WPBakery shortcode edit panel open
        $(document).on('click', '.wpb_edit_form_elements .attach_file .vc_btn', function() {
            // Store reference to the current edit panel
            var $editPanel = $(this).closest('.vc_shortcode-param');

            // Wait for media library to open and file to be selected
            if (wp && wp.media) {
                // Get or create the media frame
                var frame = wp.media.editor.get();

                if (frame) {
                    frame.on('select', function() {
                        handleFileSelection($editPanel);
                    });
                }
            }
        });

        // Alternative: Listen for changes on the file input field
        $(document).on('change', '.vc_shortcode-param[data-vc-shortcode-param-name="file"] input', function() {
            var $editPanel = $(this).closest('.wpb_edit_form_elements');
            handleFileInputChange($editPanel, $(this).val());
        });

        // Watch for attribute changes on file input (WPBakery updates this)
        observeFileInputChanges();
    }

    /**
     * Handle file selection from media library
     */
    function handleFileSelection($editPanel) {
        setTimeout(function() {
            var $form = $editPanel.closest('.wpb_edit_form_elements');
            var $fileInput = $form.find('[data-vc-shortcode-param-name="file"] input');
            var $titleInput = $form.find('[data-vc-shortcode-param-name="title"] input');

            if ($fileInput.length && $titleInput.length) {
                var attachmentId = $fileInput.val();

                // Only auto-populate if title is empty
                if (attachmentId && !$titleInput.val().trim()) {
                    fetchAttachmentFilename(attachmentId, function(filename) {
                        if (filename) {
                            var title = filenameToTitle(filename);
                            $titleInput.val(title).trigger('change');
                        }
                    });
                }
            }
        }, 500);
    }

    /**
     * Handle file input value change
     */
    function handleFileInputChange($editPanel, attachmentId) {
        if (!attachmentId) {
            return;
        }

        var $titleInput = $editPanel.find('[data-vc-shortcode-param-name="title"] input');

        // Only auto-populate if title is empty
        if ($titleInput.length && !$titleInput.val().trim()) {
            fetchAttachmentFilename(attachmentId, function(filename) {
                if (filename) {
                    var title = filenameToTitle(filename);
                    $titleInput.val(title).trigger('change');
                }
            });
        }
    }

    /**
     * Fetch attachment filename via AJAX
     */
    function fetchAttachmentFilename(attachmentId, callback) {
        if (!attachmentId || !window.ajaxurl) {
            callback(null);
            return;
        }

        $.ajax({
            url: window.ajaxurl,
            type: 'POST',
            data: {
                action: 'fdc_get_attachment_filename',
                attachment_id: attachmentId,
                nonce: (window.fdcAdmin && window.fdcAdmin.nonce) ? window.fdcAdmin.nonce : ''
            },
            success: function(response) {
                if (response.success && response.data && response.data.filename) {
                    callback(response.data.filename);
                } else {
                    callback(null);
                }
            },
            error: function() {
                callback(null);
            }
        });
    }

    /**
     * Use MutationObserver to watch for file input changes
     * WPBakery sometimes updates inputs without triggering change events
     */
    function observeFileInputChanges() {
        // Create observer instance
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                    var $input = $(mutation.target);

                    // Check if this is a file input in our element
                    if ($input.closest('[data-vc-shortcode-param-name="file"]').length) {
                        var $form = $input.closest('.wpb_edit_form_elements');
                        handleFileInputChange($form, $input.val());
                    }
                }
            });
        });

        // Watch for WPBakery edit panel to open
        $(document).on('DOMNodeInserted', function(e) {
            var $panel = $(e.target);

            if ($panel.hasClass('vc_ui-panel') || $panel.find('.vc_ui-panel').length) {
                setTimeout(function() {
                    var $fileInputs = $('.vc_shortcode-param[data-vc-shortcode-param-name="file"] input');

                    $fileInputs.each(function() {
                        observer.observe(this, {
                            attributes: true,
                            attributeFilter: ['value']
                        });
                    });
                }, 200);
            }
        });
    }

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        initAutoPopulate();
    });

})(jQuery);
