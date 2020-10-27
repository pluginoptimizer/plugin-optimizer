(function ($) {
    'use strict';

    $(document).ready(function () {
        $('.created-filters input[type="submit"]').click(function (e) {
            e.preventDefault();

            $.ajax({
                // url: filter_ajax.ajaxurl,
                url: "/wp-admin/admin-ajax.php",
                type: 'POST',
                data: {
                    action: 'add_plugin_to_filter',
                    'block_plugins': $('select[name="block_plugins"] option:selected').toArray().map(item => item.text).join(', '),
                    'post_type': $('select[name="post_type"] option:selected').toArray().map(item => item.text).join(', '),
                    'pages': $('input[name="pages"]').val(),
                    'title_filter': $('input[name="title_filter"]').val(),
                    'type_filter': $('input[name="type_filter"]').val(),
                },
                success: function (data) {
                    $('#the-list').html(data.data);
                    console.log(data.data);
                }
            })
        });
    });


})(jQuery);