jQuery(function (jQuery) {
    var $ = jQuery;

    function initAutocomplete(element)
    {
        var $this = $(element);
        $this.autocomplete({
            source: php_array.admin_ajax + '?action=morepress_'+$this.attr('data-callback')+'_ajax',
            select: function (event, ui) {
                $this.val(ui.item.post_title);
                $this.next().val(ui.item.ID);
                return false;
            },
            change: function (event, ui) {
                if (!ui.item) {
                    $this.val('');
                    $this.next().val('');
                }
            }
        });
        $this.autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                    .append("<a>" + item.ID + ": " + item.post_title + " (" + item.post_type + ")</a>")
                    .appendTo(ul);
        };
    }

    $(document).on('click', '.custom_upload_image_button', function (e) {
        var formfield = $(this).parent().parent().find('.custom_upload_image');
        var preview = $(this).parent().parent().find('.custom_preview_image');
        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
        window.send_to_editor = function (html) {
            var imgurl = $('img', html).attr('src');
            var classes = $('img', html).attr('class');
            var id = classes.replace(/(.*?)wp-image-/, '');
            formfield.val(id);
            preview.attr('src', imgurl);
            tb_remove();
        };
        return e.preventDefault();
    });

    $(document).on('click', '.custom_clear_image_button', function (e) {
        var defaultImage = $(this).parent().parent().find('.custom_default_image').text();
        $(this).parent().parent().find('.custom_upload_image').val('');
        $(this).parent().parent().find('.custom_preview_image').attr('src', defaultImage);
        return e.preventDefault();
    });

    // Reapeatable fields
    $(document).on('click', '.repeatable-add', function (e) {

        var field = $(this).closest('td').find('.custom_repeatable li:last').clone(true);
        var fieldLocation = $(this).closest('td').find('.custom_repeatable li:last');
        field.insertAfter(fieldLocation, $(this).closest('td'));
        return e.preventDefault();
    });

    $(document).on('click', '.repeatable-remove', function (e) {
        $(this).parent().remove();
        return e.preventDefault();
    });

    $('.custom_repeatable').sortable({
        opacity: 0.6,
        revert: true,
        cursor: 'move',
        handle: '.sort'
    });

    // Reapeatable fieldset
    $(document).on('click', '.group-repeatable-add', function (e) {
        var $newFieldset = $($(this).attr('href')).html();
        $newFieldset = $newFieldset.replace(/__INDEX__/g, $(this).parent().parent().find('fieldset').size());
        $(this).parent().before($newFieldset);
        $('.morepress_post_list').each(function () {
            initAutocomplete(this);
        });
        return e.preventDefault();
    });

    $(document).on('click', '.group-repeatable-remove', function (e) {
        $(this).parent().parent().remove();
        return e.preventDefault();
    });

    $('.morepress_post_list').each(function () {
        initAutocomplete(this);
    });

});  