(function ($) {
    'use strict';

    $(document).ready(function () {
        //created filters
        (function () {
            $('.created-filters input[type="submit"]').click(function (e) {
                e.preventDefault();
                let name_post_type;
                if($('#name_page').text() === 'worklist'){
                    name_post_type = 'sos_work';
                } else if($('#name_page').text() === 'filters'){
                    name_post_type = 'sos_filter';
                }

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
                        window.methodAllElement.count_element(name_post_type);
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

        //search elements
        (function(){
            $('#search_elements').keyup(function () {
                let name_post_type;
                if($('#name_page').text() === 'worklist'){
                    name_post_type = 'sos_work';
                } else if($('#name_page').text() === 'filters'){
                    name_post_type = 'sos_filter';
                }
                let type_works;
                if($('#all_elements').css('font-weight') === '700'){
                    type_works = 'all';
                } else {
                    type_works = 'trash';
                }
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_search_elements',
                        'name_post_type': name_post_type,
                        'type_works': type_works,
                        'keyword': $('#search_elements').val(),
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                    }
                });
            });
        })();

        //all elements
        (function(){
            $('#all_elements').click(function () {
                let name_post_type;
                if($('#name_page').text() === 'worklist'){
                    name_post_type = 'sos_work';
                } else if($('#name_page').text() === 'filters'){
                    name_post_type = 'sos_filter';
                }
                $('select#check_all_elements option[value="restore"]').remove();
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_all_elements',
                        'name_post_type': name_post_type,
                    },
                    success: function (response) {
                        $('#all_elements').css('font-weight', '700');
                        $('#trash_elements').css('font-weight', '400');
                        $('#the-list').html(response.data);
                        window.methodAllElement.check_all_element();
                    }
                });
            });
        })();

        // trash elements
        (function(){
            $('#trash_elements').click(function () {
                let name_post_type;
                if($('#name_page').text() === 'worklist'){
                    name_post_type = 'sos_work';
                } else if($('#name_page').text() === 'filters'){
                    name_post_type = 'sos_filter';
                }
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_trash_elements',
                        'name_post_type': name_post_type,
                    },
                    success: function (response) {
                        $('#trash_elements').css('font-weight', '700');
                        $('#all_elements').css('font-weight', '400');
                        $('#the-list').html(response.data);
                        $('select#check_all_elements option[value="delete"]').before('<option value="restore">Restore</option>');
                        window.methodAllElement.check_all_element();
                    }
                });
            });
        })();

        // delete or restore element
        (function(){
            $('#btn_apply').click(function () {
                let name_post_type;
                if($('#name_page').text() === 'worklist'){
                    name_post_type = 'sos_work';
                } else if($('#name_page').text() === 'filters'){
                    name_post_type = 'sos_filter';
                }

                if($('#check_all_elements option:selected').text() === 'Delete'){
                    let type_works;
                    if($('#all_elements').css('font-weight') === '700'){
                        type_works = 'all';
                    } else {
                        type_works = 'trash';
                    }
                    $.ajax({
                        url: simple_online_systems_groups.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'sos_delete_elements',
                            'name_post_type': name_post_type,
                            'type_works': type_works,
                            'id_works': $('input:checked').toArray().map(item => item.id).join(','),
                        },
                        success: function (response) {
                            $('#the-list').html(response.data);
                            if($('#check_all').is( ":checked" )){
                                $('#check_all').prop('checked', false);
                            }
                            window.methodAllElement.count_element(name_post_type);
                        }
                    });
                } else if($('#check_all_elements option:selected').text() === 'Restore'){
                    $.ajax({
                        url: simple_online_systems_groups.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'sos_publish_elements',
                            'name_post_type': name_post_type,
                            'id_works': $('input:checked').toArray().map(item => item.id).join(','),
                        },
                        success: function (response) {
                            $('#the-list').html(response.data);
                            if($('#check_all').is( ":checked" )){
                                $('#check_all').prop('checked', false);
                            }
                            window.methodAllElement.count_element(name_post_type);
                        }
                    });
                }

            });
        })();

        // check all element
        (function(){
            window.methodAllElement = {
                check_all_element : function(){
                    $('#check_all').change(function () {
                        if($(this).is( ":checked" )){
                            $('tbody input:checkbox').prop('checked', true);
                        } else {
                            $('tbody input:checkbox').prop('checked', false);
                        }
                    });
                    $('tbody input:checkbox').change(function(){
                        if($('#check_all').is( ":checked" )){
                            $('#check_all').prop('checked', false);
                        }
                        if($('tbody input:checkbox').length === $('tbody input:checkbox:checked').length){
                            console.log('change');
                            $('#check_all').prop('checked', true);
                        }
                    });
                },
                count_element : function(name_post_type){
                    $.ajax({
                        url: simple_online_systems_groups.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'sos_count_elements',
                            'name_post_type': name_post_type,
                        },
                        success: function (response) {
                            try {
                                $('#count_all_elements').text(response.data.all);
                                $('#count_trash_elements').text(response.data.trash);
                            } catch (err) {
                                console.error(err.message);
                            }
                        },
                    });
                },
            };
            window.methodAllElement.check_all_element();
        })()

        // trash elements
        $('.filter_block').click(function () {
            if($(this).next().css('display') === 'none'){
                $(this).next().css('display', 'table-row');
            } else{
                $(this).next().css('display', 'none');
            }
        })





    });





})(jQuery);