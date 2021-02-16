(function ($) {
'use strict';

    $(document).ready(function(){

        let namePage = $('#name_page').attr('class');
        namePage = namePage === 'filters_categories' ? 'categories' : namePage;
        $(`#window_${namePage}`).css('background-color', '#d7b70a');

        
        const params = window.location.search.replace('?','').split('&').reduce( function(p,e){
            const a = e.split('=');
            p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
            return p;
        }, {});
        if(params['work_title']){
            $('input#set_title').val(`Optimization for ${params['work_title'].replace('_', ' ')}`);
            params['work_link'].includes('http') ? $('#search_pages').val(params['work_link']) : $(`span[value="${params['work_link']}"]`).parent().addClass(`block`);
            $('.content-new-element').css('display', 'block');
        } else if(params['filter_title']) {
            $(`tr.block_info > td:nth-child(2):contains(${params['filter_title'].replace('+', ' ')})`).parent().next('.hidden_info').css('display', 'table-row');
            $('html').animate({ scrollTop: $(`tr.block_info > td:nth-child(2):contains(${params['filter_title'].replace('+', ' ')})`).parent().next('.hidden_info').children().children().children('.block-plugin-wrapper').children().children('.plugin-wrapper').offset().top - 100 }, 1000);
        }

        
        // TODO This element doesn't exist in the repository
        $('body').on('click', '.change_content_data', function(){
            if($(this).children('option:selected').val() === 'type'){
                $('.content-type').css('display', 'block');
                $('.content-permalinks').css('display', 'none');
            } else {
                $('.content-permalinks').css('display', 'block');
                $('.content-type').css('display', 'none');
            }
        });
        
        // trash CPTs
        $('body').on('click', '#trash_elements', function(){
            let name_post_type;
            if($('#name_page').attr("class") === 'worklist'){
                name_post_type = 'sos_work';
            } else if($('#name_page').attr("class") === 'filters'){
                name_post_type = 'sos_filter';
            } else if($('#name_page').attr("class") === 'groups'){
                name_post_type = 'sos_group';
            }
            
            console.log( "aAjax: trash-elements.js" );
            
            $.ajax({
                url: plugin_optimizer_groups.ajax_url,
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
                }
            });
        });
        
        // menu switching pages
        $('body').on('click', '#window_filters, #window_categories, #window_groups, #window_worklist, #window_settings', function(){
            $('.tabs').css('background', '#1e4d7d');

            const selfId = $(this).attr('id');

            switch (selfId) {
                case 'window_filters':
                    location.href='/wp-admin/admin.php?page=plugin_optimizer_filters';
                    break;
                case 'window_categories':
                    location.href='/wp-admin/admin.php?page=plugin_optimizer_filters_categories';
                    break;
                case 'window_groups':
                    location.href='/wp-admin/admin.php?page=plugin_optimizer_groups';
                    break;
                case 'window_worklist':
                    location.href='/wp-admin/admin.php?page=plugin_optimizer_worklist';
                    break;
                case 'window_settings':
                    location.href='/wp-admin/admin.php?page=plugin_optimizer_settings';
                    break;
            }

            $(`#${selfId}`).css('background-color', '#d7b70a');

        });
        
        // showPluginsSettings
        $('body').on('click', '#activate_plugins, #deactivate_plugins', function(){
            const self = this;
            
            console.log( "aAjax: show-plugins-to-settings.js" );
            
            $.ajax({
                url: plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_show_plugins',
                    type_plugins: $(self).attr('id'),
                },
                success: function (response) {
                    $(self).css('font-weight', 600);
                    if($(self).attr('id') === 'activate_plugins'){
                        $('#deactivate_plugins').css('font-weight', 400);
                    } else {
                        $('#activate_plugins').css('font-weight', 400);
                    }
                    $('#the-list').html(response.data);
                }
            });
        });
        
        // showHiddenOverview
        $('body').on('click', '.tab-overview', function(){
            if($(this).next('.hidden-info_overview').css('display') !== 'block'){
                $(this).next('.hidden-info_overview').css('display', 'block');
                $(this).children('.trigger').removeClass('trigger_exit');
                $(this).children('.trigger').addClass('trigger_open');
                $(this).children().children('.info-passage').addClass('done');
            } else{
                $(this).children('.trigger').addClass('trigger_exit');
                $(this).children('.trigger').removeClass('trigger_open');
                $(this).next('.hidden-info_overview').css('display', 'none');
            }
        })
        
        // Select a group plugins for a new filter or select parent for a new group
        $('body').on('click', '.block-group-plugin-wrapper .content', function(){
            
            console.log( "select-parent-group.js" );
            
            if($(this).text() !== 'None' && $('.none_group').hasClass('block')){
                $('.none_group').removeClass('block');
            }
            
            if($(this).hasClass('block')){
                
                $(this).removeClass('block');
                
                if($(this).text() !== 'None'){
                    let countItem = 0;
                    $( '.block-group-plugin-wrapper .content' ).each(function( item ) {
                        if($(this).hasClass('block')){
                            countItem++;
                        }
                    });
                    if(countItem === 0){
                        $('.none_group').addClass('block');
                    }
                }
            } else {
                $(this).addClass('block');
            }
        });
        
        // Select a parent for a new category
        $('body').on('click', '.select_parent_to_category', function(){
            const selfText = $(this).text();
            if($(this).hasClass('block')){
                $(this).removeClass('block');
                $('.none_parent').addClass('block');
            } else {
                $( '.select_parent_to_category' ).each(function( item ) {
                    if($(this).hasClass('block') && selfText !== $(this).text()){
                        $(this).removeClass('block');
                    }
                });
                $('.none_parent').removeClass('block');
                $(this).addClass('block');
            }

        });

        // Select all plugins for a new filter
        $('body').on('click', '.all-check', function(){
            if($(this).text() === 'Disable All'){
                $(this).text('Enable All');
                $(this).parent().parent().children('.plugin-wrapper').children('.content').addClass('block');
            } else {
                $(this).text('Disable All');
                $(this).parent().parent().children('.plugin-wrapper').children('.content').removeClass('block');
            }
        });

        // search filters
        $('body').on('keyup', '#search_filters', function(){
            
            console.log( "aAjax: search-filters.j" );
            
            $.ajax({
                url: plugin_optimizer_groups.ajax_url,
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

        // search elements
        $('body').on('keyup', '#search_elements', function(){
            let name_post_type;
            if($('#name_page').attr("class") === 'worklist'){
                name_post_type = 'sos_work';
            } else if($('#name_page').attr("class") === 'filters'){
                name_post_type = 'sos_filter';
            } else if($('#name_page').attr("class") === 'groups'){
                name_post_type = 'sos_group';
            } else if($('#name_page').attr("class") === 'filters_categories'){
                name_post_type = 'cat';
            } else if($('#name_page').attr("class") === 'settings'){
                name_post_type = 'plugins';
            }
            let type_elements;
            if($('#all_elements').css('font-weight') === '700'){
                type_elements = 'all';
            } else {
                type_elements = 'trash';
            }
            
            console.log( "aAjax: search-elements.js" );
            
            $.ajax({
                url: plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action          : 'sos_search_elements',
                    'name_post_type': name_post_type,
                    'type_elements' : type_elements,
                    'keyword'       : $('#search_elements').val(),
                },
                success: function (response) {
                    $('#the-list').html(response.data);
                }
            });
        });

        // delete or restore element
        $('body').on('click', '#btn_apply', function(){
            let name_post_type;
            if($('#name_page').attr("class") === 'worklist'){
                name_post_type = 'sos_work';
            } else if($('#name_page').attr("class") === 'filters'){
                name_post_type = 'sos_filter';
            } else if($('#name_page').attr("class") === 'groups'){
                name_post_type = 'sos_group';
            } else if($('#name_page').attr("class") === 'filters_categories'){
                name_post_type = 'cat';
            }

            if($('#check_all_elements option:selected').text() === 'Delete'){
                let type_elements;
                if($('#all_elements').css('font-weight') === '700'){
                    type_elements = 'all';
                } else {
                    type_elements = 'trash';
                }
            
            console.log( "aAjax: delete-restore-element.js 111" );
            
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action          : 'sos_delete_elements',
                        'name_post_type': name_post_type,
                        'type_elements' : type_elements,
                        'id_elements'   : $('input:checked').toArray().map(item => item.id).join(','),
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                        if($('#check_all').is( ":checked" )){
                            $('#check_all').prop('checked', false);
                        }
                        allElements.count_element(name_post_type);
                    }
                });
            } else if($('#check_all_elements option:selected').text() === 'Restore'){
            
            console.log( "aAjax: delete-restore-element.js 222" );
            
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action          : 'sos_publish_elements',
                        'name_post_type': name_post_type,
                        'id_elements'   : $('input:checked').toArray().map(item => item.id).join(','),
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                        if($('#check_all').is( ":checked" )){
                            $('#check_all').prop('checked', false);
                        }
                        allElements.count_element(name_post_type);
                    }
                });
            }

        });

        // create plugins
        $('body').on('click', '#add_elements', function(){
            if($('#name_page').hasClass('filters')){
                location.href='/wp-admin/admin.php?page=plugin_optimizer_add_filters';
            } else if($('#name_page').hasClass('groups')){
                location.href='/wp-admin/admin.php?page=plugin_optimizer_add_groups';
            } else {
                $('#create_elements').css('display', 'block');
                if($('.content-new-element').css('display') === 'block'){
                    $('.content-new-element').css('display', 'none');
                } else {
                    $('.content-new-element').css('display', 'block');
                }
            }
        });
        $('body').on('click', '.wrapper_create-elements > .popup-close', function(){
            $('#create_elements').css('display', 'none');
        });

        // all elements
        $('body').on('click', '.filter-category .close', function(){
            let selfDelete = this;
            
            console.log( "aAjax: delete-category.js" );
            
            $.ajax({
                url: plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action          : 'sos_delete_category',
                    'id_category'   : $(this).attr('id'),
                    'id_filter'     : $(this).parent().parent().children('button').attr('id').substr(5),
                },
                success: function (response) {
                    $(selfDelete).parent().parent().html(response.data);
                }
            });
        });

        // create group plugins
        $('body').on('click', '.save-group', function(){
            let result = true;
            $('.content-new-element input#set_title').toArray().some(function (item) {
                if ($(item).val().trim() === "" && result) {
                    $(item).focus();
                    return result = false;
                }
            })
            if (!result) {
                return false;
            }
            if (!$('.block-plugin-wrapper .content').hasClass('block')) {
                $('.block-plugin-wrapper .content').toArray().map(item => $(item).css('box-shadow', 'rgba(255, 255, 255, 0.2) 0px 0px 0px 1px inset, rgba(0, 0, 0, 0.9) 0px 0px 0px 1px'))
                return false;
            } else {
            
            console.log( "aAjax: create-groups.js 111" );
            
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_add_group_plugins',
                        'title_group': $('#set_title').val(),
                        'group_plugins': $('.block-plugin-wrapper .block span').toArray().map(item => $(item).text()).join(', '),
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                        // $('.content-new-element').css('display', 'none');
                        $('#set_title').val('');
                        $('#set_type').val('');
                        $('.block-plugin-wrapper .block').toArray().map(item => $(item).removeClass('block'));
                        allElements.count_element('sos_group');
            
            console.log( "aAjax: create-groups.js 222" );
            
                        $.ajax({
                            url: plugin_optimizer_groups.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'sos_get_parent_group',
                            },
                            success: function ({data}) {
                                $('.block-group-plugin-wrapper').children().html(data);
                            }
                        })
                    }
                })
            }


        });

        // created filters
        $('body').on('click', '.save-filter', function(){
            let result = true;
            $('.content-new-element input#set_title').toArray().some(function (item) {
                if ($(item).val().trim() === "" && result) {
                    $(item).focus();
                    return result = false;
                }
                /*if ($(item).val().trim() === "" && result && item.id !== 'search_pages') {
                    $(item).focus();
                    return result = false;
                } else if ($(item).val().trim() === "" && result && item.id === 'search_pages' && !$('span').is('.text_link')) {
                    $(item).focus();
                    return result = false;
                } else if ($(item).val().trim() !== "" && item.id === 'search_pages' && !$('span').is('.text_link')) {
                    $(item).focus();
                    return result = false;
                }*/
            })
            if (!result) {
                return false;
            }
            if (!$('.block-plugin-wrapper .content').hasClass('block')) {
                $('.block-plugin-wrapper .content').toArray().map(item => $(item).css('box-shadow', 'rgba(255, 255, 255, 0.2) 0px 0px 0px 1px inset, rgba(0, 0, 0, 0.9) 0px 0px 0px 1px'))
                return false;
            } else if (!$('.category-wrapper .content').hasClass('block')) {
                $('.category-wrapper .content').toArray().map(item => $(item).css('box-shadow', 'rgba(255, 255, 255, 0.2) 0px 0px 0px 1px inset, rgba(0, 0, 0, 0.9) 0px 0px 0px 1px'))
                return false;
            } else {
            
            console.log( "aAjax: created-filters.js" );
            
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_add_plugin_to_filter',
                        'block_plugins': $('.block-plugin-wrapper .block span').toArray().map(item => $(item).text()).join(', '),
                        'block_value_plugins': $('.block-plugin-wrapper .block span').toArray().map(item => $(item).attr('value')).join(', '),
                        'block_group_plugins': $('.block-group-plugin-wrapper .block>span').toArray().map(item => $(item).text()).join(', ') ? $('.block-group-plugin-wrapper .block>span').toArray().map(item => $(item).text()).join(', ') : 'None',
                        'pages': $('.content-permalinks .link span.text_link').toArray().map(item => $(item).text()).join(', '),
                        'title_filter': $('input#set_title').val(),
                        'type_filter': $('#set_type').val(),
                        'category_filter': $('.category-wrapper .block span').toArray().map(item => $(item).text()).join(', '),
                        'category_id_filter': $('.category-wrapper .block span').toArray().map(item => $(item).attr('value')).join(', '),
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                        // $('.content-new-element').css('display', 'none');
                        allElements.count_element('sos_filter');

                        if($('.content-new-element *').is('.block')){
                            $('.content-new-element *').removeClass('block');
                        }

                        $('#set_title').val('');
                        $('#search_pages').val('');
                        $('.link').remove();
                        $('.change_content_data option:first').prop('selected', true);
                        $('#set_type option:first').prop('selected', true);
                    }
                })
            }
        });

        // change plugins
        $('body').on('click', '.save-category', function(){
            let result = true;
            $('.content-new-element input').toArray().some(function (item) {
                if ($(item).val().trim() === "" && result) {
                    $(item).focus();
                    return result = false;
                }
            })
            if (!result) {
                return false;
            }
            
            console.log( "aAjax: create-category.js 111" );
            
            $.ajax({
                url: plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_create_cat_subcat',
                    'name_category': $('#set_title').val(),
                    'description_category': $('#set_description').val(),
                    'parent_category': $('.parent-category-wrapper .block span').toArray().map(item => $(item).attr('value')).join(', '),
                },
                success: function ({data}) {
                    $('#the-list').html(data);
                    $('.content-new-element').css('display', 'none');
                    $('#set_title').val('');
                    allElements.count_element('cat');
            
            console.log( "aAjax: create-category.js 222" );
            
                    $.ajax({
                        url: plugin_optimizer_groups.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'sos_get_parent_cat',
                        },
                        success: function ({data}) {
                            $('.content-new-element .plugin-wrapper').html(data);
                        }
                    });
                }
            });
        });

        // Select a plugins for a new filter or select plugins for a new group
        $('body').on('click', '.block-plugin-wrapper .content', function(){

            console.log( "choice-plugin.js" );
            
            if($(this).hasClass('block')){
                $(this).removeClass('block');
            } else {
                $(this).addClass('block');
            }
        });

        $('body').on('keypress', '#search_pages', function(e){
            if (e.keyCode == 13) {
                $('.add-permalink').click();
            }
        });

        $('body').on('click', '.add-permalink', function(){
            let linkClient = $('#search_pages').val();
            linkClient = get_hostname(linkClient)               ? linkClient.replace(get_hostname(linkClient), '')  : linkClient;
            linkClient = linkClient.indexOf('/') === 0          ? linkClient.replace('/', '')                       : linkClient;
            linkClient = linkClient.includes(location.hostname) ? linkClient                                        : `${location.hostname}/${linkClient}`;
            linkClient = linkClient.includes('https://')        ? linkClient                                        : `https://${linkClient}`;


            $(this).parent().after(`
                <div class="link">
                    <span class="data-interaction text_link" contenteditable>${linkClient}</span>
                    <span class="close-selected-link">×</span>
                </div>
            `);
            $('#search_pages').val('');
            $('#search_pages').focus();
            $('.close-selected-link').click(function(){
                $(this).parent().remove();
            })
        });

        // Select a category for a new filter
        $('body').on('click', '.category-wrapper .content', function(){

            console.log( "choice-category.js" );
            
            if($(this).hasClass('block')){
                $(this).removeClass('block');
            } else {
                $(this).addClass('block');
            }
        });

        // Check the name of the elements when creating them
        $('body').on('click', '#set_title', function(){
            const name_element = $(this).val();
            const type_element = $('#name_page').attr('class');
            
            console.log( "aAjax: check-name-elements.js" );
            
            $.ajax({
                url: plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_check_name_elements',
                    'name_element': name_element,
                    'type_element': type_element,
                },
                success: function (response) {
                    if( response.data === true && response.data !== 'nothing' ){
                        $('#set_title').css('border', '1px solid red');
                        $('#set_title').val('');
                        $('#set_title').focus();
                    } else {
                        $('#set_title').css('border-top', '0');
                        $('#set_title').css('border-right', '0');
                        $('#set_title').css('border-left', '0');
                        $('#set_title').css('border-bottom', '1px solid #000');
                    }
                }
            });
        });

        $('body').on('click', '#group_name_error .popup-close', function(){
            $('#set_title').css('border', '1px solid red');
            $('#set_title').val('');
            $('#set_title').focus();
        });

        // hidden info filter
        $('body').on('click', '.block_info > td:not(:nth-child(1))', function(){
            const element_id = $(this).parent().children('td:nth-child(1)').children().attr('id');

            if($('#name_page').attr("class") === 'filters_categories'){
                location.href=`/wp-admin/term.php?taxonomy&tag_ID=${element_id}&post_type=sos_filter`;
            } else {
                location.href=`/wp-admin/post.php?post=${element_id}&action=edit`;
            }

            /*if($(this).parent().next('.hidden_info').css('display') === 'none'){
                $(this).parent().next('.hidden_info').css('display', 'table-row');
            } else{
                $(this).parent().next('.hidden_info').css('display', 'none');
            }*/
        });

        // Change the appearance of the tab selection
        
        // Hidden appearance settings
        function hidden_settings(){
            if($('#settings_plugins').css('display') === 'block'){
                $('#settings_plugins').css('display', 'none');
                $('#show_settings_plugins').css('font-weight', 400);
            }
            switch ('flex') {
                case $('#settings_general').css('display'):
                    $('#settings_general').css('display', 'none');
                    $('#show_settings_general').css('font-weight', 400);
                    break;
                case $('#settings_debug').css('display'):
                    $('#settings_debug').css('display', 'none');
                    $('#show_settings_debug').css('font-weight', 400);
                    break;
            }
        }

        // Hidden appearance settings
        $('body').on('click', '#show_settings_general', function(){
            $(this).css('font-weight', 600);
            hidden_settings();
            $('#settings_general').css('display', 'flex');
        });

        $('body').on('click', '#show_settings_plugins', function(){
            $(this).css('font-weight', 600);
            hidden_settings();
            $('#settings_plugins').css('display', 'block');
        });

        $('body').on('click', '#show_settings_debug', function(){
            $(this).css('font-weight', 600);
            hidden_settings();
            $('#settings_debug').css('display', 'flex');
        });
        
        // Change plugins to group
        $('body').on('click', '.wrapper-group-plugins .content', function(){
            
            console.log( "change-plugins-group.js" );
            
            const plugin_name   = $(this).children().text();
            const block_plugins = $('#block_plugins');

            if(!$(this).hasClass('block')){
                /* Change appearance */
                $(this).addClass('block');

                /* Record data of selected plugins */
                block_plugins.val() ? block_plugins.val(`${block_plugins.val()}, ${plugin_name}`) : block_plugins.val(plugin_name);
            } else {
                /* Change appearance */
                $(this).removeClass('block');

                /* Delete data of selected plugins */
                block_plugins.val(block_plugins.val().split(', ').filter(item => item !== plugin_name).join(', '))
            }
            /*$.ajax({
                url: plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_change_plugins_to_group',
                    'group_id': $(this).attr('group_id'),
                    'plugin_name':  $(this).children().text(),
                    'trigger': $(this).hasClass('block') ? 'delete' : 'add',
                },
                success: function (response) {
                    $('#the-list').html(response.data.return);
                    $(`tr#group_${response.data.group_id}`).next('.hidden_info').css('display', 'table-row');
                }
            });*/
        });
        
        // Change plugins to filter
        $('body').on('click', '.plugin-wrapper:not(.group-wrapper) > .content', function(){
            
            console.log( "change-plugins.js" );
            
            let   $close = $(this).find('span.close');
            
            if( $close.length < 1 ){
                return;
            }
            
            const filter_id      = $close.attr('value');
            const plugin_name    = $close.attr('id');
            const plugin_link    = $close.attr('link');
            const change_plugins = $close.text();

            const block_plugins      = $('#block_plugins');
            const block_link_plugins = $('#block_link_plugins');

            if(change_plugins === '+'){
                /* Change appearance */
                $(this).addClass('block');
                $close.text('×');
                /* Record data of selected plugins */
                block_plugins.val() ? block_plugins.val(`${block_plugins.val()}, ${plugin_name}`) : block_plugins.val(plugin_name);

                /* Record data of selected link plugins */
                block_link_plugins.val() ? block_link_plugins.val(`${block_link_plugins.val()}, ${plugin_link}`) : block_link_plugins.val(plugin_link);
            } else {
                /* Change appearance */
                $(this).removeClass('block');
                $close.text('+');
                /* Delete data of selected plugins */
                block_plugins.val(block_plugins.val().split(', ').filter(item => item !== plugin_name).join(', '))

                /* Delete data of selected plugins */
                block_link_plugins.val(block_link_plugins.val().split(', ').filter(item => item !== plugin_link).join(', '))
            }

            /*$.ajax({
                url: plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_change_plugins_to_filter',
                    'filter_id': filter_id,
                    'plugin_name': plugin_name,
                    'plugin_link': plugin_link,
                    'change_plugins': change_plugins,
                },
                success: function (response) {
                    /!* Change the content of the block plugins *!/
                    $(`tr#filter-${response.data.filter_id}`).next('.hidden_info').children().children().children('.content-plugins').html(response.data.return);
                }
            });*/
        });
        
        // Change the selected links
        $('body').on('input', '.data-link', function(){
            const text_link = $(this).text();
            const filter_id = $(this).attr('filter_id');
            
            console.log( "aAjax: change-permalink.js" );
            
            $.ajax({
                url: plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_change_permalink',
                    'text_link': text_link,
                    'filter_id': filter_id,
                },
                success: function ({data}) {
                    $(`tr#filter-${filter_id}>.data-link-filter`).text(data);
                }
            });
        });
        
        // changeGroups
        $('body').on('click', '.group-wrapper > .content', function(){
            
            console.log( "change-groups.js" );
            
            const group_name    = $(this).children('span').text();
            const filter_id     = $(this).attr('value');
            const change_groups = $(this).is('.block') ? 'remove' : 'add';
            const plugins_names = $(this).children('.hidden_content').children().toArray().map(item => $(item).text()).join(', ');
            const plugins_links = $(this).children('.hidden_content').children().toArray().map(item => $(item).attr('value')).join(', ');


            const block_plugins      = $('#block_plugins');
            const block_link_plugins = $('#block_link_plugins');
            const block_group        = $('#block_group_plugins');


            if(!$(this).hasClass('block')){
                /* Change appearance */
                $(this).parent().addClass('block');

                /* Record data of group plugins */
                block_group.val() !== 'None' ? block_group.val(`${block_group.val()}, ${group_name}`) : block_group.val(group_name);

                /* Record data of selected plugins */
                block_plugins.val() ? block_plugins.val(`${block_plugins.val()}, ${plugins_names}`) : block_plugins.val(plugins_names);

                /* Record data of selected link plugins */
                block_link_plugins.val() ? block_link_plugins.val(`${block_link_plugins.val()}, ${plugins_links}`) : block_link_plugins.val(plugins_links);
            } else {
                /* Change appearance */
                $(this).parent().removeClass('block');
                /* Delete data of selected plugins */
                block_group.val(block_group.val().split(', ').filter(item => item !== group_name).join(', '))

                /* Delete data of selected plugins */
                block_plugins.val(block_plugins.val().split(', ').filter(item => item !== plugins_names).join(', '))

                /* Delete data of selected plugins */
                block_link_plugins.val(block_link_plugins.val().split(', ').filter(item => item !== plugins_links).join(', '))
            }
            
            // console.log( "aAjax: change-groups.js" );
            
            // $.ajax({
                // url: plugin_optimizer_groups.ajax_url,
                // type: 'POST',
                // data: {
                    // action: 'sos_change_groups_to_filter',
                    // 'group_name'   : group_name,
                    // 'filter_id'    : filter_id,
                    // 'plugins_names': plugins_names,
                    // 'plugins_links': plugins_links,
                    // 'change_groups': change_groups,
                // },
                // success: function (response) {
                    // $(`tr#filter-${response.data.filter_id}`).next('.hidden_info').children().children().children('.content-plugins').html(response.data.content_plugins);
                    // $(`tr#filter-${response.data.filter_id}`).next('.hidden_info').children().children().children('.group-wrapper').html(response.data.content_groups);
                    // changeGroups();
                // }
            // });
        });
        
        $('body').on('click', '.wrapper_filter_to_category .content', function(){
            
            console.log( "change-filter-to-category.js" );
            
            let self = this;
            
            console.log( "aAjax: change-filter-to-category.js" );
            
            $.ajax({
                url : plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action          : 'sos_add_category_to_filter',
                    'id_category'   : $(this).attr('cat_id').substr(4),
                    'id_filter'     : $(this).attr('id'),
                    'trigger'       : $(this).hasClass('block') ? 'delete' : 'add',
                    'page'          : $('#name_page').attr('class'),
                },
                success: function (response) {
                    $(self).parent().parent().parent().html(response.data);
                }
            });
        });
        
        // Change the category name
        $('.data-title-cat').on('input change', function(){
            const text_name = $(this).text().trim();
            const description_category = $(this).parent().parent().parent().parent().children('.description').children().children('.content-description').children('.data-description-cat').text().trim();
            const cat_id = $(this).attr('cat_id');
            
            console.log( "aAjax: change-data-categories.js" );
            
            $.ajax({
                url: plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_change_data_category',
                    'text_name': text_name,
                    'description_category': description_category,
                    'cat_id': cat_id,
                },
                success: function ({data}) {
                    $(`tr#cat-${cat_id}>.data-title-category`).text(data);
                }
            });
        });

        $('.data-description-cat').on('input', function(){
            $('.data-title-cat').change();
        });

        // Add or delete category that already exists for filters on filters page
        $('body').on('click', '.filter-category', function() {

            console.log( "add-category-filter.js" );
            
            const name_category = $(this).children('span:nth-child(1)').text();
            const category_filter = $('#category_filter');


            if(!$(this).hasClass('block')){
                /* Change appearance */
                $(this).addClass('block');

                /* Record data of name category */
                category_filter.val() ? category_filter.val(`${category_filter.val()}, ${name_category}`) : category_filter.val(name_category);

            } else {
                /* Change appearance */
                $(this).removeClass('block');
                /* Delete data of name category */
                category_filter.val(category_filter.val().split(', ').filter(item => item !== name_category).join(', '))
            }

            let self = this;
            
            console.log( "aAjax: add-category-filter.js" );
            
            $.ajax({
                url: plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_add_category_to_filter',
                    /* ID category that have chosen */
                    'id_category': $(this).children('span.close').attr('id'),
                    /* ID filter for which add or delete a category */
                    'id_filter': $(this).parent().children('button').attr('id').substr(5),
                    /* Depending on whether the filter belongs to this category, we remove or add it */
                    'trigger': $(this).hasClass('block') ? 'delete' : 'add',
                    'page': $('#name_page').attr('class'),
                },
                success: function (response) {
                    /* Change the content of the block of categories */
                    // $(self).parent().html(response.data);
                }
            });
        });

        // Show the available items on their pages after updating the data
        $('body').on('click', '#all_elements', function(){
            let name_post_type;
            if($('#name_page').attr("class") === 'worklist'){
                name_post_type = 'sos_work';
            } else if($('#name_page').attr("class") === 'filters'){
                name_post_type = 'sos_filter';
            } else if($('#name_page').attr("class") === 'groups'){
                name_post_type = 'sos_group';
            }
            /* Remove the ability to recover if we are out of the cart */
            $('select#check_all_elements option[value="restore"]').remove();
            
            console.log( "aAjax: all-elements.js" );
            
            $.ajax({
                url: plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_all_elements',
                    'name_post_type': name_post_type,
                },
                success: function (response) {
                    /* change the weight of the font to a larger one for the "ALL" link */
                    $('#all_elements').css('font-weight', '700');
                    /* change the font weight to a smaller one for the "TRASH" link */
                    $('#trash_elements').css('font-weight', '400');
                    /* Change the content of the page */
                    $('#the-list').html(response.data);
                }
            });
        });
        
        // Change the selected type
        $('body').on('input', '.data-type', function(){
            const text_type = $(this).text();
            const filter_id = $(this).attr('filter_id');

            $('#type_filter').val(text_type.trim());

            $(`tr#filter-${filter_id}>.data-type-filter`).text(text_type);

            /*$.ajax({
                url: plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_change_type',
                    'text_type': text_type,
                    'filter_id': filter_id,
                },
                success: function ({data}) {
                    $(`tr#filter-${filter_id}>.data-type-filter`).text(data);
                }
            });*/
        });
        
        // Add new category for filters on filters page
        $('body').on('click', '.add-category', function (e) {
            const self = this;
            const name_category = $(this).prev().val();
            const id_filter = $(this).attr('id').substr(5);
            
            console.log( "aAjax: add-category.js" );
            
            $.ajax({
                url: plugin_optimizer_groups.ajax_url,
                type: 'POST',
                data: {
                    action: 'sos_create_category',
                    'name_category': name_category,
                    'id_filter': id_filter,
                },
                success: function (response) {
                    /* Change the content of the block of categories already with a new category */
                    $( self ).parent().html( response.data );
                }
            });
        });

        // Select all elements
        $('body').on('change', '#check_all', function(){
            if($(this).is( ":checked" )){
                $('tbody input:checkbox').prop('checked', true);
            } else {
                $('tbody input:checkbox').prop('checked', false);
            }
        });
        // Change appearance checkbox all elements
        $('body').on('change', 'tbody input:checkbox', function(){
            if($('#check_all').is( ":checked" )){
                $('#check_all').prop('checked', false);
            }
            if($('tbody input:checkbox').length === $('tbody input:checkbox:checked').length){
                $('#check_all').prop('checked', true);
            }
        });
        
        // Actions for all elements
        let allElements = {
            /*
            * Change count elements after deleting or adding
            * */
            count_element : function(name_post_type){
                
                console.log( "aAjax: check-all-element.js" );
                
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
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

        function get_hostname(url) {
            let m = url.match(/^http:\/\/[^/]+/);

            if(m){
                return m[0];
            }
            m = url.match(/^https:\/\/[^/]+/);
            return m ? m[0] : null;
        }

    });
})(jQuery);
