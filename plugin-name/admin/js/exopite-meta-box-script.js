/* global console */
;(function($) {
    "use strict";

    $(document).ready(function () {

        var metaImageFrame;

        $('.exopite-meta-boxes-upload-preview-close').on('click', function(e){
            $(this).parent().css({
                'display': 'none',
            });
            $(this).parent().next().val('');
        });

        $('.exopite-meta-boxes-upload-button').on('click', function(e){

            // Sets up the media library frame
            metaImageFrame = wp.media.frames.metaImageFrame = wp.media({
                title: 'bla',
                button: { text: 'Use this file' },
                multiple: false,
                library: {
                    type: 'image',
                }
            });

            var $that = $(this);

            // Runs when an image is selected.
            metaImageFrame.on('select', function () {

                // Grabs the attachment selection and creates a JSON representation of the model.
                var media_attachment = metaImageFrame.state().get('selection').first().toJSON();
                var $mediaUrlField = $that.closest('span').children('.exopite-meta-boxes-upload-url');
                var $mediaPreviewElement = $that.closest('span').children('.exopite-meta-boxes-upload-preview');

                $mediaPreviewElement.css({
                    'background-image': 'url("' + media_attachment.url + '")',
                    'display': 'block',
                });

                // Sends the attachment URL to our custom image input field.
                $mediaUrlField.val(media_attachment.url);

            });

            // Opens the media library frame.
            metaImageFrame.open();

        });

    });

}(jQuery));
