jQuery(function (jQuery) {
    var $ = jQuery
      , mp_media;

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

    $(document).on('click', '.upload_image_button', function (e) {
        if(! mp_media) {
            mp_media = wp.media({
                className: 'media-frame',
                multiple: false
            });
        }
        var $input = $(this).parent().parent().find('.upload_image')
          , $preview = $(this).parents('.form-field').parent().find('.upload_preview');
        mp_media.open();
        mp_media.off('select');
        mp_media.on('select', function () {
            var selection = mp_media.state().get('selection').first().toJSON();
            $input.attr('value', selection.id);
            $preview.find('img').attr('src', selection.url);
        });
        return e.preventDefault();
    });

    $(document).on('click', '.upload_button', function (e) {
        if(! mp_media) {
            mp_media = wp.media({
                className: 'media-frame',
                multiple: false
            });
        }
        var $input = $(this).parent().parent().find('.upload')
          , $preview = $(this).parent().parent().find('.upload_preview');
        mp_media.open();
        mp_media.off('select');
        mp_media.on('select', function () {
            var selection = mp_media.state().get('selection').first().toJSON();
            $input.attr('value', selection.id);
            $preview.find('a').attr('href', selection.url).text(selection.title);
        });
        return e.preventDefault();
    });

    $(document).on('click', '.clear_image_button', function (e) {
        var defaultImage = $(this).parent().parent().find('.default_image').text();
        $(this).parent().parent().find('.upload_image').val('');
        $(this).parent().parent().find('.preview_image').attr('src', defaultImage);
        return e.preventDefault();
    });
    $(document).on('click', '.clear_button', function (e) {
        $(this).parent().parent().find('.upload').val('');
        $(this).parent().parent().find('.upload_preview a').attr('href', '#').text('');
        return e.preventDefault();
    });

    // Reapeatable fields
    $(document).on('click', '.repeatable-add', function (e) {

        var field = $(this).closest('td').find('.repeatable li:last').clone(true);
        var fieldLocation = $(this).closest('td').find('.repeatable li:last');
        field.insertAfter(fieldLocation, $(this).closest('td'));
        return e.preventDefault();
    });

    $(document).on('click', '.repeatable-remove', function (e) {
        $(this).parent().remove();
        return e.preventDefault();
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
        $(this).parent().remove();
        return e.preventDefault();
    });

    $('.morepress_post_list').each(function () {
        initAutocomplete(this);
    });

});
