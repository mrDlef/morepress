jQuery(function ($) {
    $('.form-field-image').each(function() {
        // Set all variables to be used in scope
        var frame,
            formField = $(this),
            addImgLink = formField.find('.upload_image_button'),
            delImgLink = formField.find('.clear_image_button'),
            imgContainer = formField.find('.upload_preview'),
            imgIdInput = formField.find('.upload_image');
        // ADD IMAGE LINK
        addImgLink.on('click', function (event) {
            event.preventDefault();
            // If the media frame already exists, reopen it.
            if (frame) {
                frame.open();
                return;
            }
            // Create a new media frame
            frame = wp.media({
                multiple: false
            });
            // When an image is selected in the media frame...
            frame.on('select', function () {
                // Get media attachment details from the frame state
                var attachment = frame.state().get('selection').first().toJSON();
                // Send the attachment URL to our custom image input field.
                imgContainer.append('<img src="' + attachment.url + '" alt="" style="max-width:100%;"/>');
                // Send the attachment id to our hidden input
                imgIdInput.val(attachment.id);
                // Hide the add image link
                addImgLink.addClass('hidden');
                // Unhide the remove image link
                delImgLink.removeClass('hidden');
            });
            // Finally, open the modal on click
            frame.open();
        });
        // DELETE IMAGE LINK
        delImgLink.on('click', function (event) {
            event.preventDefault();
            // Clear out the preview image
            imgContainer.html('');
            // Un-hide the add image link
            addImgLink.removeClass('hidden');
            // Hide the delete image link
            delImgLink.addClass('hidden');
            // Delete the image id from the hidden input
            imgIdInput.val('');
        });
    });
    $('.form-field-file').each(function() {
        // Set all variables to be used in scope
        var frame,
            formField = $(this),
            addImgLink = formField.find('.upload_button'),
            delImgLink = formField.find('.clear_button'),
            imgContainer = formField.find('.upload_preview'),
            imgIdInput = formField.find('.upload');
        // ADD IMAGE LINK
        addImgLink.on('click', function (event) {
            event.preventDefault();
            // If the media frame already exists, reopen it.
            if (frame) {
                frame.open();
                return;
            }
            // Create a new media frame
            frame = wp.media({
                multiple: false
            });
            // When a file is selected in the media frame...
            frame.on('select', function () {
                // Get media attachment details from the frame state
                var attachment = frame.state().get('selection').first().toJSON();
                // Send the attachment URL to our custom file input field.
                imgContainer.append('<a href="' + attachment.url + '" target="_blank">'+attachment.title+'</a>');
                // Send the attachment id to our hidden input
                imgIdInput.val(attachment.id);
                // Hide the add file link
                addImgLink.addClass('hidden');
                // Unhide the remove file link
                delImgLink.removeClass('hidden');
            });
            // Finally, open the modal on click
            frame.open();
        });
        // DELETE IMAGE LINK
        delImgLink.on('click', function (event) {
            event.preventDefault();
            // Clear out the preview file
            imgContainer.html('');
            // Un-hide the add file link
            addImgLink.removeClass('hidden');
            // Hide the delete file link
            delImgLink.addClass('hidden');
            // Delete the file id from the hidden input
            imgIdInput.val('');
        });
    });
});