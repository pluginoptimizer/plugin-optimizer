(function ($) {
    'use strict';

    $(document).ready(function () {
        $('.created-filters input[type="submit"]').click(function (e) {
            e.preventDefault();

            $.ajax({
                // url: filter_ajax.ajaxurl,
                url: simple_online_systems_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_add_plugin_to_filter',
                    'block_plugins': $('select[name="block_plugins"] option:selected').toArray().map(item => item.text).join(', '),
                    'block_group_plugins': $('select[name="block_group_plugins"] option:selected').toArray().map(item => item.text).join(', '),
                    'post_type': $('select[name="post_type"] option:selected').toArray().map(item => item.text).join(', '),
                    'pages': $('input[name="pages"]').val(),
                    'title_filter': $('input[name="title_filter"]').val(),
                    'type_filter': $('input[name="type_filter"]').val(),
                },
                success: function (response) {
                    $('#the-list').html(response.data);
                }
            })
        });

        // search pages
        $('.popup-close').click(function () {
            $('#result').css( 'display', 'none' )
        });
        $('#search_pages').keyup(function () {
            $.ajax({
                url: simple_online_systems_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_search_pages',
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
        });

        $('#search_filters').keyup(function () {
            $.ajax({
                url: simple_online_systems_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_search_filters',
                    keyword: $('#search_filters').val()
                },
                success: function (response) {
                    $('#the-list').html(response.data);
                }
            });
        });

        // create plugins
        $('.created-groups input[type="submit"]').click(function (e) {
            e.preventDefault();

            $.ajax({
                url: simple_online_systems_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_add_group_plugins',
                    'title_group': $('input[name="title_group"]').val(),
                    'type_group': $('input[name="type_group"]').val(),
                    'group_plugins': $('select[name="group_plugins"] option:selected').toArray().map(item => item.text).join(', '),
                },
                success: function (response) {
                    console.log(response);
                    $('#the-list').html(response.data);
                }
            })
        });

    });


})(jQuery);