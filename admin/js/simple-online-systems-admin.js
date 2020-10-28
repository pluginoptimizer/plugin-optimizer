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

        // search pages
        $('.popup-close').click(function () {
            $('#result').css( 'display', 'none' )
        });
        $('#search_pages').keyup(function () {
            $.ajax({
                url: "/wp-admin/admin-ajax.php",
                type: 'POST',
                data: {
                    action: 'search_pages',
                    keyword: $('#search_pages').val()
                },
                success: function(response) {
                    $('#result').css( 'display', 'block' );
                    $('#result_search').html( response.data );
                    if($('#search_pages').val() === '') $('#result').css( 'display', 'none' );

                    $('.link_search_page').click(function(e){
                        e.preventDefault();

                        let select_link = $('#search_pages').val();

                        if(select_link.includes('http')){
                            $('#search_pages').val(select_link + ', ' + $(this).attr('href'));
                            $('#search_pages').focus();
                        } else {
                            $('#search_pages').val($(this).attr('href'));
                        }

                    })
                }
            });
        })

    });


})(jQuery);