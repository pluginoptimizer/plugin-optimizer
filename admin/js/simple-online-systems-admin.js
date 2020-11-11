(function ($) {
    'use strict';

    $(document).ready(function () {
        //created filters
        (function () {
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
                        'category_filter': $('input[name="category_filter"]').val(),
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                    }
                })
            });
        })();


        // search pages
        (function (){
            $('.popup-close').click(function () {
                $('#result').css( 'display', 'none' )
            });

            $('#search_pages').on('input', function () {
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

                        });
                    }
                });
            });
        })();

        //search filters
        (function(){
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
        })();

        //search works
        (function(){
            $('#search_works').keyup(function () {
                let type_works;
                if($('#all_works').css('font-weight') === '700'){
                    type_works = 'all';
                } else {
                    type_works = 'trash';
                }
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_search_works',
                        'type_works': type_works,
                        'keyword': $('#search_works').val(),
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                    }
                });
            });
        })();

        // create plugins
        (function(){
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
        })();

        //worklist
        (function () {
            const params = window
                .location
                .search
                .replace('?','')
                .split('&')
                .reduce(
                    function(p,e){
                        const a = e.split('=');
                        p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                        return p;
                    },
                    {}
                );
            if(params['work_title']){
                $('input[name="title_filter"]').val('Page acceleration ' + params['work_title'].replace('_', ' '));
                $('#search_pages').val(params['work_link']);
            }
        })();

        //all works
        (function(){
            $('#all_works').click(function () {
                $('select#check_works option[value="restore"]').remove();
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_all_works',
                    },
                    success: function (response) {
                        $('#all_works').css('font-weight', '700');
                        $('#trash_works').css('font-weight', '400');
                        $('#the-list').html(response.data);
                        window.checkWorks.check_all_works();
                    }
                });
            });
        })();

        // trash works
        (function(){
            $('#trash_works').click(function () {
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_trash_works',
                    },
                    success: function (response) {
                        $('#trash_works').css('font-weight', '700');
                        $('#all_works').css('font-weight', '400');
                        $('#the-list').html(response.data);
                        $('select#check_works option[value="delete"]').before('<option value="restore">Restore</option>');
                        window.checkWorks.check_all_works();
                    }
                });
            });
        })();

        // delete element
        (function(){
            $('#btn_apply').click(function () {
                if($('#check_works option:selected').text() === 'Delete'){
                    let type_works;
                    if($('#all_works').css('font-weight') === '700'){
                        type_works = 'all';
                    } else {
                        type_works = 'trash';
                    }
                    $.ajax({
                        url: simple_online_systems_groups.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'sos_delete_works',
                            'type_works': type_works,
                            'id_works': $('input:checked').toArray().map(item => item.id).join(','),
                        },
                        success: function (response) {
                            $('#the-list').html(response.data);
                            if($('#check_all').is( ":checked" )){
                                $('#check_all').prop('checked', false);
                            }
                            $.ajax({
                                url: simple_online_systems_groups.ajax_url,
                                type: 'POST',
                                data: {
                                    action: 'sos_count_works',
                                },
                                success: function (response) {
                                    $('#count_all_works').text(response.data.all);
                                    $('#count_trash_works').text(response.data.trash);
                                }
                            });
                        }
                    });
                } else if($('#check_works option:selected').text() === 'Restore'){
                    $.ajax({
                        url: simple_online_systems_groups.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'sos_publish_works',
                            'id_works': $('input:checked').toArray().map(item => item.id).join(','),
                        },
                        success: function (response) {
                            $('#the-list').html(response.data);
                            if($('#check_all').is( ":checked" )){
                                $('#check_all').prop('checked', false);
                            }
                            $.ajax({
                                url: simple_online_systems_groups.ajax_url,
                                type: 'POST',
                                data: {
                                    action: 'sos_count_works',
                                    'type_works': 'trash',
                                },
                                success: function (response) {
                                    $('#count_all_works').text(response.data.all);
                                    $('#count_trash_works').text(response.data.trash);
                                }
                            });
                        }
                    });
                }

            });
        })();

        // check all works
        (function(){
            window.checkWorks = {
                check_all_works : function(){
                    $('#check_all').change(function () {
                        if($(this).is( ":checked" )){
                            $('tbody input:checkbox').prop('checked', true);
                        } else {
                            $('tbody input:checkbox').prop('checked', false);
                        }
                    });
                    $('tbody input:checkbox').change(function () {
                        if($('#check_all').is( ":checked" )){
                            $('#check_all').prop('checked', false);
                        }
                        if($('tbody input:checkbox').length === $('tbody input:checkbox:checked').length){
                            $('#check_all').prop('checked', true);
                        }
                    });
                }
            };
            window.checkWorks.check_all_works();
        })()






    });





})(jQuery);