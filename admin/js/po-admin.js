jQuery( document ).ready( function($){
    'use strict';

    let current_page = $('#name_page').attr('class');
    current_page = current_page === 'filters_categories' ? 'categories' : current_page;
    current_page = current_page === 'add-filters'        ? 'filters'    : current_page;
    current_page = current_page === 'add-groups'         ? 'groups'     : current_page;
    $(`#window_${current_page}`).css('background-color', '#d7b70a');

    // NEW: Edit Filter page template, Edit Group page template - Clicking on a plugin
    $('#edit_filter, #edit_group').on('click', '.block-plugin-wrapper .single_plugin', function(){

        $(this).toggleClass('blocked');
        
        let $checkbox = $(this).find('input[type="checkbox"]');
        $checkbox.prop( "checked", ! $checkbox.prop("checked") );
        
    });

    // NEW: Edit Filter page template - Clicking on a group
    $('#edit_filter').on('click', '.block-group-plugin-wrapper .single_group', function(){
        
        $(this).toggleClass('blocked');
        
        let $checkbox = $(this).find('input[type="checkbox"]');
        $checkbox.prop( "checked", ! $checkbox.prop("checked") );
        
        toggle_plugins_by_group( $(this) );
    });
    
    // NEW: Toggles plugins for a group if it is being selected for blocking
    function toggle_plugins_by_group( $group_element, only_labels = false ){
        
        let plugins_to_block = $group_element.data("plugins");
        let group_name       = $group_element.children("span").text();
        
        if( $group_element.hasClass('blocked') ){
            
            // console.log( "Name: ", group_name );
            // console.log( "Plugins: ", plugins_to_block );
            
            $.each( plugins_to_block, function( index, plugin_name ){
                
                // console.log( "Block: ", plugin_name );
                
                if( ! only_labels ){
                
                    $(`.single_plugin[data-name="${plugin_name}"]`).addClass("blocked");
                    $(`.single_plugin[data-name="${plugin_name}"] input[type="checkbox"]`).prop("checked", true);
                
                }
                
                $(`.single_plugin[data-name="${plugin_name}"] span.group_name`).append(`<span data-name="${group_name}">${group_name}</span>`);
                
            });
            
        } else {
            
            $(`.single_plugin span.group_name span[data-name="${group_name}"]`).remove();
        }
        
    }
    
    // NEW: Edit Filter page template - Select a category for a new filter, does nothing but marks the selected category
    $('#edit_filter').on('click', '.category-wrapper .single_category', function(){

        $(this).toggleClass('blocked');
        
        let $checkbox = $(this).find('input[type="checkbox"]');
        $checkbox.prop( "checked", ! $checkbox.prop("checked") );
        
    });
    
    // NEW: Edit Filter page template - Create new category, show input field
    $('#edit_filter').on('click', '#add_category.before_add', function(){

        $('#add_category').removeClass('before_add');
        $('#add_category').addClass('during_add');
        $('#add_category input').focus();
        
    });
    
    // NEW: Edit Filter page template - Create new category, Cancel
    $('#edit_filter').on('click', '#add_category.during_add .cancel', function(){

        $('#add_category').removeClass('during_add');
        $('#add_category').addClass('before_add');
        
    });
    
    // NEW: Edit Filter page template - Create new category, OK
    $('#edit_filter').on('click', '#add_category.during_add .ok', function(){

        let category_name = $('#add_category input').val();
        
        if( ! category_name ){
            return;
        }
        
        $.post( po_object.ajax_url, { action  : 'po_create_category', category_name : category_name }, function( response ) {
            console.log( "po_create_category: ", response );
            
            if( response.data.message ){
                alert( response.data.message );
            } else {
                
                $('#add_category').removeClass('during_add');
                $('#add_category').addClass('before_add');
                
                $('#add_category input').val('');
                
                $('#add_category').before(`
					<div class="single_category content blocked">
                        <input class="noeyes" type="checkbox" name="PO_filter_data[categories][${response.data.category_id}]" value="${category_name}" checked="checked"/>
						<span value="${response.data.category_id}">${category_name}</span>
                    </div>
                `);
            }
            
        }, "json");
        
    });
    
    // NEW: Edit Filter page template - Toggle plugins for already selected groups on page load
    $('#edit_filter .block-group-plugin-wrapper .single_group.blocked').each(function(){
        
        toggle_plugins_by_group( $(this), true );
        
    });
    
    // NEW: Edit Filter page template - Change filter type
    $('#edit_filter').on('change', '#set_type', function(){
        
        let type = $(this).val();
        
        if( type == "_endpoint" ){
            $('#edit_filter #endpoints_wrapper').slideDown();
        } else {
            $('#edit_filter #endpoints_wrapper').slideUp();
        }
        
        
    }).change();
    
    // NEW: Edit Filter page template - Save filter
    $('#edit_filter').on('click', '#save_filter', function(){
        
        let filter_data = $('#edit_filter').find('select, textarea, input').serialize();
        
        $.post( po_object.ajax_url, { action  : 'po_save_filter', data : filter_data }, function( response ) {
            console.log( "po_save_filter: ", response );
            
            alert( response.data.message );
            
        }, "json");
        
    });
    
    // NEW: On a new Filter or new Group pages: disable/enable all in a section
    $('body').on('click', '.all-check', function(){
        
        if( $(this).text() === 'Disable All'){
            
            $(this).text('Enable All');
            
            if( $(this).hasClass("toggle_plugins") ){
                
                $(this).parents('.row.block-plugin-wrapper').find('.single_plugin').addClass('blocked');
                $(this).parents('.row.block-plugin-wrapper').find('.single_plugin input[type="checkbox"]').prop( "checked", true );
                
            } else if( $(this).hasClass("toggle_groups") ){
                
                $(this).parents('.row.block-group-plugin-wrapper').find('.single_group').addClass('blocked');
                $(this).parents('.row.block-group-plugin-wrapper').find('.single_group input[type="checkbox"]').prop( "checked", true );
                
                $(this).parents('.row.block-group-plugin-wrapper').find('.single_group').each(function(){
                    
                    toggle_plugins_by_group( $(this) );
                });
                
            }
            
        } else {
            
            $(this).text('Disable All');
            
            if( $(this).hasClass("toggle_plugins") ){
                
                $(this).parents('.row.block-plugin-wrapper').find('.single_plugin').removeClass('blocked');
                $(this).parents('.row.block-plugin-wrapper').find('.single_plugin input[type="checkbox"]').prop( "checked", false );
                
            } else if( $(this).hasClass("toggle_groups") ){
                
                $(this).parents('.row.block-group-plugin-wrapper').find('.single_group').removeClass('blocked');
                $(this).parents('.row.block-group-plugin-wrapper').find('.single_group input[type="checkbox"]').prop( "checked", false );
                
                $(this).parents('.row.block-group-plugin-wrapper').find('.single_group').each(function(){
                    
                    toggle_plugins_by_group( $(this) );
                });
                
            }
            
        }
    });
    
    // NEW: Potentially replace the domain in the link with the local domain
    function force_local_domain( link ){
        
        let new_link = link;
            new_link = get_hostname(new_link)               ? new_link.replace(get_hostname(new_link), '')  : new_link;
            new_link = new_link.indexOf('/') === 0          ? new_link.replace('/', '')                       : new_link;
            new_link = new_link.includes(location.hostname) ? new_link                                        : `${location.hostname}/${new_link}`;
            new_link = new_link.includes('https://')        ? new_link                                        : `https://${new_link}`;
        
        // TODO what if the site runs on http:// ?
        
        return new_link;
    }
    
    // NEW: On the Add New Filter page, the button #add_endpoint is used to add a new endpoint to the filter
    $('body').on('click', '#add_endpoint', function(){
        
        let link = force_local_domain( $('#first_endpoint').val() );

        $(this).parent().after(`
			<div class="col-12 additional_endpoint_wrapper">
                <input class="additional_endpoint" type="text" name="PO_filter_data[endpoints][]" placeholder="Put your URL here" value="${link}"/>
                <div class="remove_additional_endpoint circle_button remove_something">-</div>
			</div>
        `);
        
        $('#first_endpoint').val('');
        $('#first_endpoint').focus();
        
    });

    // NEW: On the Add New Filter page, the button #add_endpoint is used to add new endpoint to the filter
    $('body').on('click', '.remove_additional_endpoint', function(){
        
        $(this).parent().remove();
    });

    // NEW: On the Add New Filter page, we need to force to local domain, can't filter plugins for other domains
    $('body').on('focusout', '.additional_endpoint_wrapper input', function(){
        
        let link = force_local_domain( $(this).val() );

        $(this).val( link );
    });

    // NEW: On the Add New Filter page, #first_endpoint is the input field where you put the initial permalink/endpoint for the filter
    $('body').on('keypress', '#first_endpoint', function(e){
        
        if (e.keyCode == 13) {
            $('#add_endpoint').click();
        }
    });

    // NEW: Show only the published items
    $('body').on('click', '#all_elements', function(){
        
        $('#all_elements').addClass("filtered");
        $('#trash_elements').removeClass("filtered");
        
        $('#the-list > [data-status="trash"]').addClass("filtered_out__status");
        $('#the-list > [data-status="publish"]').removeClass("filtered_out__status");
        
        $('select#check_all_elements option[value="restore"]').remove();
        
    });
    
    // NEW: Show only the trashed items
    $('body').on('click', '#trash_elements', function(){
        
        $('#trash_elements').addClass("filtered");
        $('#all_elements').removeClass("filtered");
        
        $('#the-list > [data-status="publish"]').addClass("filtered_out__status");
        $('#the-list > [data-status="trash"]').removeClass("filtered_out__status");
        
        $('select#check_all_elements option[value="delete"]').before('<option value="restore">Restore</option>');
        
    });
    
    // NEW: Filter by date
    $('body').on('click', '#btn_date_filter', function(){
        
        let date_filter = $('#filter_all_elements').val();
        
        $('#the-list > *').removeClass("filtered_out__date");
        
        if( date_filter != "default" ){
            
            $(`#the-list > *:not([data-date="${date_filter}"])`).addClass("filtered_out__date");
        }
        
    });
    
    // NEW: Filter by type (filters only)
    $('body').on('click', '#btn_type_filter', function(){
        
        let date_filter = $('#filter_by_type').val();
        
        $('#the-list > *').removeClass("filtered_out__type");
        
        if( date_filter != "default" ){
            
            $(`#the-list > *:not([data-type="${date_filter}"])`).addClass("filtered_out__type");
        }
        
    });
    
    
    
    
    
    
    
    
    // TODO normalize this, use classes instead styles, doh
    // Switch submenu on the Settings page
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

    // Switch submenu on the Settings page
    $('body').on('click', '#show_settings_general', function(){
        $(this).css('font-weight', 600);
        hidden_settings();
        $('#settings_general').css('display', 'flex');
    });

    // Switch submenu on the Settings page
    $('body').on('click', '#show_settings_plugins', function(){
        $(this).css('font-weight', 600);
        hidden_settings();
        $('#settings_plugins').css('display', 'block');
    });

    // Switch submenu on the Settings page
    $('body').on('click', '#show_settings_debug', function(){
        $(this).css('font-weight', 600);
        hidden_settings();
        $('#settings_debug').css('display', 'flex');
    });
    
    // switch between tabs menu pages
    $('body').on('click', '#window_filters, #window_categories, #window_groups, #window_worklist, #window_settings', function(){
        
        console.log( "OLD: switch between tabs menu pages" );
        
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
    
    


    // Bulk actions button (usually delete or restore element)
    $('body').on('click', '#btn_apply', function(){
        
        console.log( "SOMEWHAT FIXED: Bulk actions button (usually delete or restore element)" );
        
        let name_post_type;
        let data = false;
        
        if($('#name_page').attr("class") === 'worklist'){
            name_post_type = 'sos_work';
        } else if($('#name_page').attr("class") === 'filters'){
            name_post_type = 'sos_filter';
        } else if($('#name_page').attr("class") === 'groups'){
            name_post_type = 'sos_group';
        } else if($('#name_page').attr("class") === 'filters_categories'){
            name_post_type = 'cat';
        }

        console.log( "aAjax: delete-restore-element.js 222" );
        
        if($('#check_all_elements option:selected').text() === 'Delete'){
            
            data = {
                action          : 'sos_delete_elements',
                'name_post_type': name_post_type,
                'type_elements' : ( $('#all_elements').css('font-weight') === '700' ? 'all' : 'trash' ),
                'id_elements'   : $('input:checked').toArray().map(item => item.id).join(','),
            };
            
        } else if($('#check_all_elements option:selected').text() === 'Restore'){
        
            data = {
                action          : 'sos_publish_elements',
                'name_post_type': name_post_type,
                'id_elements'   : $('input:checked').toArray().map(item => item.id).join(','),
            };
            
        }
        
        if( data ){
            
            $.ajax({
                url     : po_object.ajax_url,
                type    : 'POST',
                data    : data,
                success : function (response) {
                    
                    $('#the-list').html( response.data );
                    
                    if($('#check_all').is( ":checked" )){
                        $('#check_all').prop('checked', false);
                    }
                    
                    allElements.count_element(name_post_type);
                }
            });
            
        }
        
    });








    // on the list of plugins, switch between active and inactive ones
    $('body').on('click', '#activate_plugins, #deactivate_plugins', function(){
        
        console.log( "OLD: on the list of plugins, switch between active and inactive ones" );
        
        const self = this;
        
        console.log( "aAjax: show-plugins-to-settings.js" );
        
        // $.ajax({
            // url: po_object.ajax_url,
            // type: 'POST',
            // data: {
                // action: 'sos_show_plugins',
                // type_plugins: $(self).attr('id'),
            // },
            // success: function (response) {
                // $(self).css('font-weight', 600);
                // if($(self).attr('id') === 'activate_plugins'){
                    // $('#deactivate_plugins').css('font-weight', 400);
                // } else {
                    // $('#activate_plugins').css('font-weight', 400);
                // }
                // $('#the-list').html(response.data);
            // }
        // });
    });
    
    // Overview page - switch between accordion elements
    $('body').on('click', '.tab-overview', function(){
        
        console.log( "OLD: Overview page - switch between accordion elements" );
        
        // TODO - the page doesn't remember the current state of completed tasks
        
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
    
    // Select a parent category on the Add New Category screen
    $('body').on('click', '.select_parent_to_category', function(){
        
        console.log( "OLD: Select a parent category on the Add New Category screen" );
        
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

    // search elements, a box on most of the PO pages
    $('body').on('keyup', '#search_elements', function(){
        
        console.log( "OLD: search elements, a box on most of the PO pages" );
        
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
        
        // $.ajax({
            // url: po_object.ajax_url,
            // type: 'POST',
            // data: {
                // action          : 'sos_search_elements',
                // 'name_post_type': name_post_type,
                // 'type_elements' : type_elements,
                // 'keyword'       : $('#search_elements').val(),
            // },
            // success: function (response) {
                // $('#the-list').html(response.data);
            // }
        // });
    });

    // #add_elements is a button used to get the Create New XYZ form
    $('body').on('click', '#add_elements', function(){
        
        console.log( "OLD: #add_elements is a button used to get the Create New XYZ form" );
        
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

    //
    $('body').on('click', '.filter-category .close', function(){
        
        console.log( "OLD: delete-category.js" );
        
        let selfDelete = this;
        
        console.log( "aAjax: delete-category.js" );
        
        // $.ajax({
            // url: po_object.ajax_url,
            // type: 'POST',
            // data: {
                // action          : 'sos_delete_category',
                // 'id_category'   : $(this).attr('id'),
                // 'id_filter'     : $(this).parent().parent().children('button').attr('id').substr(5),
            // },
            // success: function (response) {
                // $(selfDelete).parent().parent().html(response.data);
            // }
        // });
    });

    // Save New Group button on the Create New Filter Group page
    $('body').on('click', '.save-group', function(){
        
        console.log( "OLD: Save New Group button on the Create New Filter Group page" );
        
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
        
            // $.ajax({
                // url: po_object.ajax_url,
                // type: 'POST',
                // data: {
                    // action: 'sos_add_group_plugins',
                    // 'title_group': $('#set_title').val(),
                    // 'group_plugins': $('.block-plugin-wrapper .block span').toArray().map(item => $(item).text()).join(', '),
                // },
                // success: function (response) {
                    // $('#the-list').html(response.data);
                    // // $('.content-new-element').css('display', 'none');
                    // $('#set_title').val('');
                    // $('#set_type').val('');
                    // $('.block-plugin-wrapper .block').toArray().map(item => $(item).removeClass('block'));
                    // allElements.count_element('sos_group');
        
                    // console.log( "aAjax: create-groups.js 222" );
        
                    // $.ajax({
                        // url: po_object.ajax_url,
                        // type: 'POST',
                        // data: {
                            // action: 'sos_get_parent_group',
                        // },
                        // success: function ({data}) {
                            // $('.block-group-plugin-wrapper').children().html(data);
                        // }
                    // });
                // }
            // });
        }


    });

    // Save New Filter button on the Create New Filter page
    $('body').on('click', 'DISABLED ----------------------------       .save-filter', function(){
        
        console.log( "OLD: Save New Filter button on the Create New Filter page" );
        
        let result = true;
        $('.content-new-element input#set_title').toArray().some(function (item) {
            if ($(item).val().trim() === "" && result) {
                $(item).focus();
                return result = false;
            }
            /*if ($(item).val().trim() === "" && result && item.id !== 'first_endpoint') {
                $(item).focus();
                return result = false;
            } else if ($(item).val().trim() === "" && result && item.id === 'first_endpoint' && !$('span').is('.text_link')) {
                $(item).focus();
                return result = false;
            } else if ($(item).val().trim() !== "" && item.id === 'first_endpoint' && !$('span').is('.text_link')) {
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
        
            // $.ajax({
                // url: po_object.ajax_url,
                // type: 'POST',
                // data: {
                    // action                  : 'sos_add_plugin_to_filter',
                    // 'block_plugins'         : $('.block-plugin-wrapper .block span').toArray().map(item => $(item).text()).join(', '),
                    // 'block_value_plugins'   : $('.block-plugin-wrapper .block span').toArray().map(item => $(item).attr('value')).join(', '),
                    // 'block_group_plugins'   : $('.block-group-plugin-wrapper .block>span').toArray().map(item => $(item).text()).join(', ') ? $('.block-group-plugin-wrapper .block>span').toArray().map(item => $(item).text()).join(', ') : 'None',
                    // 'pages'                 : $('.content-permalinks .link span.text_link').toArray().map(item => $(item).text()).join(', '),
                    // 'title_filter'          : $('input#set_title').val(),
                    // 'type_filter'           : $('#set_type').val(),
                    // 'category_filter'       : $('.category-wrapper .block span').toArray().map(item => $(item).text()).join(', '),
                    // 'category_id_filter'    : $('.category-wrapper .block span').toArray().map(item => $(item).attr('value')).join(', '),
                // },
                // success: function (response) {
                    // $('#the-list').html(response.data);
                    // // $('.content-new-element').css('display', 'none');
                    // allElements.count_element('sos_filter');

                    // if($('.content-new-element *').is('.block')){
                        // $('.content-new-element *').removeClass('block');
                    // }

                    // $('#set_title').val('');
                    // $('#first_endpoint').val('');
                    // $('.link').remove();
                    // $('#set_type option:first').prop('selected', true);
                // }
            // });
        }
    });

    // #add_elements.save-category is a button used to get the Create New Category form
    $('body').on('click', '.save-category', function(){
        
        console.log( "OLD: #add_elements.save-category is a button used to get the Create New Category form" );
        
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
        
        // $.ajax({
            // url: po_object.ajax_url,
            // type: 'POST',
            // data: {
                // action                  : 'sos_create_cat_subcat',
                // 'name_category'         : $('#set_title').val(),
                // 'description_category'  : $('#set_description').val(),
                // 'parent_category'       : $('.parent-category-wrapper .block span').toArray().map(item => $(item).attr('value')).join(', '),
            // },
            // success: function ({data}) {
                // $('#the-list').html(data);
                // $('.content-new-element').css('display', 'none');
                // $('#set_title').val('');
                // allElements.count_element('cat');
        
                // console.log( "aAjax: create-category.js 222" );
        
                // $.ajax({
                    // url: po_object.ajax_url,
                    // type: 'POST',
                    // data: {
                        // action: 'sos_get_parent_cat',
                    // },
                    // success: function ({data}) {
                        // $('.content-new-element .plugin-wrapper').html(data);
                    // }
                // });
            // }
        // });
    });

    // Select a plugins for a new filter or select plugins for a new group
    $('body').on('click', '.block-plugin-wrapper .content', function(){

        console.log( "OLD: Select a plugins for a new filter or select plugins for a new group" );
        
        console.log( "choice-plugin.js" );
        
        if($(this).hasClass('block')){
            $(this).removeClass('block');
        } else {
            $(this).addClass('block');
        }
    });

    // Clicking on element on the list (filter, group, category) redirects to the edit page
    $('body').on('click', '.block_info > td:not(:nth-child(1))', function(){
        
        console.log( "OLD: Clicking on element on the list (filter, group, category) redirects to the edit page" );
        
        const element_id = $(this).parent().children('td:nth-child(1)').children().attr('id');

        if($('#name_page').attr("class") === 'filters_categories'){
            location.href=`/wp-admin/term.php?taxonomy&tag_ID=${element_id}&post_type=sos_filter`;
        } else if($('#name_page').hasClass('filters')){
            location.href=`/wp-admin/admin.php?page=plugin_optimizer_add_filters&filter_id=${element_id}`;
        } else {
            location.href=`/wp-admin/post.php?post=${element_id}&action=edit`;
        }

    });

    // Change plugins on the group edit screen
    $('body').on('click', '.wrapper-group-plugins .content', function(){
        
        console.log( "OLD: Change plugins on the group edit screen" );
        
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
            url: po_object.ajax_url,
            type: 'POST',
            data: {
                action: 'sos_change_plugins_to_group',
                'group_id': $(this).attr('group_id'),
                'plugin_name':  $(this).children().text(),
                'trigger': $(this).hasClass('block') ? 'delete' : 'add',
            },
            success: function (response) {
                $('#the-list').html(response.data.return);
            }
        });*/
    });
    
    // Groups section on the filter edit page
    $('body').on('click', '.group-wrapper > .content', function(){
        
        console.log( "OLD: Groups section on the filter edit page" );
        
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
        
        console.log( "aAjax: change-groups.js" );
        
        // $.ajax({
            // url: po_object.ajax_url,
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
            // }
        // });
    });
    
    // DELETE ?
    // Add or delete category that already exists for filters on filters page
    $('body').on('click', '.filter-category', function() {

        console.log( "OLD: Add or delete category that already exists for filters on filters page" );
        
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
        
        // $.ajax({
            // url: po_object.ajax_url,
            // type: 'POST',
            // data: {
                // action: 'sos_add_category_to_filter',
                // /* ID category that have chosen */
                // 'id_category': $(this).children('span.close').attr('id'),
                // /* ID filter for which add or delete a category */
                // 'id_filter': $(this).parent().children('button').attr('id').substr(5),
                // /* Depending on whether the filter belongs to this category, we remove or add it */
                // 'trigger': $(this).hasClass('block') ? 'delete' : 'add',
                // 'page': $('#name_page').attr('class'),
            // },
            // success: function (response) {
                // /* Change the content of the block of categories */
                // // $(self).parent().html(response.data);
            // }
        // });
    });

    // Add new category for filters on Edit Filter? page
    $('body').on('click', '.add-category', function (e) {
        
        console.log( "OLD: Add new category for filters on Edit Filter? page" );
        
        const self = this;
        const name_category = $(this).prev().val();
        const id_filter = $(this).attr('id').substr(5);
        
        console.log( "aAjax: add-category.js" );
        
        // $.ajax({
            // url: po_object.ajax_url,
            // type: 'POST',
            // data: {
                // action: 'sos_create_category',
                // 'name_category': name_category,
                // 'id_filter': id_filter,
            // },
            // success: function (response) {
                // /* Change the content of the block of categories already with a new category */
                // $( self ).parent().html( response.data );
            // }
        // });
    });

    // TODO Needs a better selector
    // Select all elements
    $('body').on('change', '#check_all', function(){
        
        console.log( "OLD: Select all elements" );
        
        if($(this).is( ":checked" )){
            $('tbody input:checkbox').prop('checked', true);
        } else {
            $('tbody input:checkbox').prop('checked', false);
        }
    });
    
    // TODO Needs a better selector
    // Change appearance checkbox all elements
    $('body').on('change', 'tbody input:checkbox', function(){
        
        console.log( "OLD: Change appearance checkbox all elements" );
        
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
            
            // $.ajax({
                // url: po_object.ajax_url,
                // type: 'POST',
                // data: {
                    // action: 'sos_count_elements',
                    // 'name_post_type': name_post_type,
                // },
                // success: function (response) {
                    // try {
                        // $('#count_all_elements').text(response.data.all);
                        // $('#count_trash_elements').text(response.data.trash);
                    // } catch (err) {
                        // console.error(err.message);
                    // }
                // },
            // });
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