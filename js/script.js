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
