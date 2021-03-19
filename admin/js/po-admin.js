jQuery( document ).ready( function($){
    'use strict';
    
    // Edit Filter screen, Edit Group screen - Clicking on a plugin
    $('#edit_filter, #edit_group').on('click', '.block-plugin-wrapper .single_plugin', function(){

        $(this).toggleClass('blocked');
        
        let $checkbox = $(this).find('input[type="checkbox"]');
        $checkbox.prop( "checked", ! $checkbox.prop("checked") );
        
    });

    // Edit Filter screen - Clicking on a group
    $('#edit_filter').on('click', '.block-group-plugin-wrapper .single_group', function(){
        
        $(this).toggleClass('blocked');
        
        let $checkbox = $(this).find('input[type="checkbox"]');
        $checkbox.prop( "checked", ! $checkbox.prop("checked") );
        
        toggle_plugins_by_group( $(this) );
    });
    
    // Toggles plugins for a group if it is being selected for blocking
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
    
    // Edit Filter screen - Select a category for a new filter, does nothing but marks the selected category
    $('#edit_filter').on('click', '.category-wrapper .single_category', function(){

        $(this).toggleClass('blocked');
        
        let $checkbox = $(this).find('input[type="checkbox"]');
        $checkbox.prop( "checked", ! $checkbox.prop("checked") );
        
    });
    
    // Edit Filter screen - Create new category, show input field
    $('#edit_filter').on('click', '#add_category.before_add', function(){

        $('#add_category').removeClass('before_add');
        $('#add_category').addClass('during_add');
        $('#add_category input').focus();
        
    });
    
    // Edit Filter screen - Create new category, Cancel
    $('#edit_filter').on('click', '#add_category.during_add .cancel', function(){

        $('#add_category').removeClass('during_add');
        $('#add_category').addClass('before_add');
        
    });
    
    // Edit Filter screen - Create new category, OK
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
                        <input class="noeyes" type="checkbox" name="SOSPO_filter_data[categories][${response.data.category_id}]" value="${category_name}" checked="checked"/>
						<span value="${response.data.category_id}">${category_name}</span>
                    </div>
                `);
            }
            
        }, "json");
        
    });
    
    // Edit Filter screen - Toggle plugins for already selected groups on page load
    $('#edit_filter .block-group-plugin-wrapper .single_group.blocked').each(function(){
        
        toggle_plugins_by_group( $(this), true );
        
    });
    
    // Edit Filter screen - Change filter type
    $('#edit_filter').on('change', '#set_type', function(){
        
        let type = $(this).val();
        
        if( type == "_endpoint" ){
            $('#edit_filter #endpoints_wrapper').slideDown();
        } else {
            $('#edit_filter #endpoints_wrapper').slideUp();
        }
        
        
    }).change();
    
    // Edit Filter screen - Save filter
    $('#edit_filter').on('click', '#save_filter', function(){
        
        let filter_data = $('#edit_filter').find('select, textarea, input').serialize();
        
        $.post( po_object.ajax_url, { action  : 'po_save_filter', data : filter_data }, function( response ) {
            // console.log( "po_save_filter: ", response );
            
            alert( response.data.message );
            
            if( response.data.id ){
                
                $('input[name="SOSPO_filter_data[ID]"]').val( response.data.id );
            }
            
        }, "json");
        
    });
    
    // Edit Group screen - Save Group
    $('#edit_group').on('click', '#save_group', function(){
        
        let group_data = $('#edit_group').find('select, textarea, input').serialize();
        
        $.post( po_object.ajax_url, { action  : 'po_save_group', data : group_data }, function( response ) {
            // console.log( "po_save_group: ", response );
            
            alert( response.data.message );
            
            if( response.data.id ){
                
                $('input[name="SOSPO_filter_data[ID]"]').val( response.data.id );
            }
            
        }, "json");
        
    });
    
    // Edit Category screen - Save Category
    $('#edit_category').on('click', '#save_category', function(){
        
        let category_data = $('#edit_category').find('select, textarea, input').serialize();
        // console.log( "category_data: ", category_data );
        
        $.post( po_object.ajax_url, { action  : 'po_save_category', data : category_data }, function( response ) {
            // console.log( "po_save_category: ", response );
            
            alert( response.data.message );
            
            if( response.data.id ){
                
                $('input[name="SOSPO_filter_data[ID]"]').val( response.data.id );
            }
            
        }, "json");
        
    });
    
    // On a new Filter or new Group pages: disable/enable all in a section
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
    
    // On the Edit Filter screen, the button #add_endpoint is used to add a new endpoint to the filter
    $('body').on('click', '#add_endpoint', function(){
        
        $('#endpoints_wrapper > div:eq(-1)').after(`
			<div class="col-12 additional_endpoint_wrapper">
                <input class="additional_endpoint" type="text" name="SOSPO_filter_data[endpoints][]" placeholder="Put your URL here" value=""/>
                <div class="remove_additional_endpoint circle_button remove_something">-</div>
			</div>
        `);
        
        $('#endpoints_wrapper > div:eq(-1) input.additional_endpoint').focus();
        
    });

    // On the Edit Filter screen, the button #add_endpoint is used to add new endpoint to the filter
    $('body').on('click', '.remove_additional_endpoint', function(){
        
        $(this).parent().remove();
    });

    // On the Edit Filter screen, we need to force to local domain, can't filter plugins for other domains
    $('body').on('input', '.additional_endpoint_wrapper input', function(ev){
        
        console.log( "Event type: ", ev.type );
        
        let full_url = new URL( $(this).val(), po_object.home_url );
        let relative = full_url.href.replace( full_url.origin, '' );

        $(this).val( relative );
        
        $(this).parent().removeClass("error__empty_input");
    });

    // On the Edit Filter screen, we need to force to local domain, can't filter plugins for other domains
    $('body').on('focusout', '.additional_endpoint_wrapper input', function(ev){
        
        if( ! $(this).val() ){
            
            $(this).parent().addClass("error__empty_input");
        }
        
    });

    // On the Edit Filter screen, #first_endpoint is the input field where you put the initial permalink/endpoint for the filter
    $('body').on('keypress', '#first_endpoint', function(e){
        
        if (e.keyCode == 13) {
            $('#add_endpoint').click();
        }
    });

    // Show only the published items
    $('body').on('click', '#all_elements', function(){
        
        $('#all_elements').addClass("filtered");
        $('#trash_elements').removeClass("filtered");
        
        $('#the-list').addClass("filter_on__status_publish");
        $('#the-list').removeClass("filter_on__status_trash");
        
        $('select#check_all_elements option[value="restore"]').remove();
        
        $('#the-list input:checked').prop('checked', false );
        
    });
    
    // Show only the trashed items
    $('body').on('click', '#trash_elements', function(){
        
        $('#trash_elements').addClass("filtered");
        $('#all_elements').removeClass("filtered");
        
        $('#the-list').addClass("filter_on__status_trash");
        $('#the-list').removeClass("filter_on__status_publish");
        
        $('select#check_all_elements option[value="delete"]').before('<option value="restore">Restore</option>');
        
        $('#the-list input:checked').prop('checked', false );
        
    });
    
    // Filter by date
    $('body').on('click', '#btn_date_filter', function(){
        
        let date_filter = $('#filter_all_elements').val();
        
        $('#the-list > *').removeClass("filtered_out__date");
        
        if( date_filter != "default" ){
            
            $(`#the-list > *:not([data-date="${date_filter}"])`).addClass("filtered_out__date");
        }
        
    });
    
    // Filter by type (filters only)
    $('body').on('click', '#btn_type_filter', function(){
        
        let date_filter = $('#filter_by_type').val();
        
        $('#the-list > *').removeClass("filtered_out__type");
        
        if( date_filter != "default" ){
            
            $(`#the-list > *:not([data-type="${date_filter}"])`).addClass("filtered_out__type");
        }
        
    });
    
    // Change appearance checkbox all elements
    $('body').on('change', '#the-list input:checkbox', function(){
        
        if($('#check_all').is( ":checked" )){
            $('#check_all').prop('checked', false);
        }
        if($('#the-list input:checkbox').length === $('#the-list input:checkbox:checked').length){
            $('#check_all').prop('checked', true);
        }
    });
    
    // Select all elements
    $('body').on('change', '#check_all', function(){
        
        $('#the-list input:checkbox').prop('checked', $(this).is(":checked") );
        
    });
    
    // Bulk actions button (usually delete or restore element)
    $('body').on('click', '#btn_apply', function(){
        
        if( $('#check_all_elements').val() === 'default' ){
            
            alert("Select an action!");
            return;
        }

        let name_post_type;
        let data = false;
        
        if( $('#name_page').attr("class") === 'worklist' ){
            name_post_type = 'sos_work';
        } else if( $('#name_page').attr("class") === 'filters' ){
            name_post_type = 'sos_filter';
        } else if( $('#name_page').attr("class") === 'groups' ){
            name_post_type = 'sos_group';
        } else if( $('#name_page').attr("class") === 'categories' ){
            name_post_type = 'cat';
        }
        
        let selected_ids = $('#the-list input:checked').toArray().map( item => item.id ).filter( id => id );
        
        if( selected_ids.length < 1 ){
            
            alert("Select some items!");
            return;
        }

        if($('#check_all_elements option:selected').text() === 'Delete'){
            
            data = {
                action          : 'po_delete_elements',
                'name_post_type': name_post_type,
                'type_elements' : ( $('#all_elements').hasClass('filtered') ? 'all' : 'trash' ),
                'id_elements'   : selected_ids,
            };
            
        } else if($('#check_all_elements option:selected').text() === 'Restore'){
        
            data = {
                action          : 'po_publish_elements',
                'name_post_type': name_post_type,
                'id_elements'   : selected_ids,
            };
            
        }
        
        // console.log( "Bulk: ", data );
        // console.log( "selected_ids: ", selected_ids );
        
        if( data ){
            
            $.ajax({
                url     : po_object.ajax_url,
                type    : 'POST',
                data    : data,
                success : function (response) {
                    
                    $('#bulk_actions select').val('default');
                    $('#bulk_actions button:not(#btn_apply)').click();
                    
                    $('#the-list input:checked').prop('checked', false );
                    $('#check_all').prop('checked', false);
                    
                    if( data.action == 'po_publish_elements' ){
                        
                        $.each( selected_ids, function( index, id ){
                            
                            $('input#' + id ).parents('.block_info').attr("data-status", "publish");
                            
                        });
                        
                    }
                    
                    if( data.action == 'po_delete_elements' && data.type_elements == 'all' ){
                        
                        $.each( selected_ids, function( index, id ){
                            
                            $('input#' + id ).parents('.block_info').attr("data-status", "trash");
                            
                        });
                        
                    }
                    
                    if( data.action == 'po_delete_elements' && data.type_elements == 'trash' ){
                        
                        $.each( selected_ids, function( index, id ){
                            
                            $('input#' + id ).parents('.block_info').remove();
                            
                        });
                        
                    }
                    
                    alert( response.data.message );
                    
                    $('#count_all_elements').html( $('#the-list > [data-status="publish"]').length );
                    $('#count_trash_elements').html( $('#the-list > [data-status="trash"]').length );
                    
                }
                
            });
            
        }
        
    });

    // Overview page - toggle different sections
    $('body').on('click', '.tab-overview', function(){
        
        if($(this).next('.hidden-info_overview').hasClass("closed") ){
            
            $(this).next('.hidden-info_overview').removeClass("closed").addClass("opened");
            $(this).children('.trigger').removeClass('trigger_closed').addClass('trigger_opened');
            
        } else{
            
            $(this).next('.hidden-info_overview').addClass("closed").removeClass("opened");
            $(this).children('.trigger').addClass('trigger_closed').removeClass('trigger_opened');
            
        }
    });
    
    // switch between tabs menu pages
    $('body').on('click', '#window_filters, #window_categories, #window_groups, #window_worklist, #window_settings', function(){
        
        $('#main_tab_navigation > div.tabs').removeClass('current');
        
        $(this).addClass('current');

        const selfId = $(this).attr('id');

        switch (selfId) {
            case 'window_filters':
                // location.href = po_object.admin_url + 'admin.php?page=plugin_optimizer_filters';
                location.href = po_object.admin_url + 'admin.php?page=plugin_optimizer';
                break;
            case 'window_categories':
                location.href = po_object.admin_url + 'admin.php?page=plugin_optimizer_filters_categories';
                break;
            case 'window_groups':
                location.href = po_object.admin_url + 'admin.php?page=plugin_optimizer_groups';
                break;
            case 'window_worklist':
                location.href = po_object.admin_url + 'admin.php?page=plugin_optimizer_worklist';
                break;
            case 'window_settings':
                location.href = po_object.admin_url + 'admin.php?page=plugin_optimizer_settings';
                break;
        }

    });
    
    // Overview: mark tab complete
    $('body').on('click', '.mark_tab_complete', function(){
        
        let tab_id = $(this).parents('.hidden-info_overview').data('id').replace('tab_', '');
        
        console.log( "Tab ID: ", tab_id );
        
        $(this).parents('.hidden-info_overview').slideUp( 400, function(){
            
            $('#tab_' + tab_id + ' .info-passage').addClass('done');
            $('#tab_' + tab_id + ' .trigger').removeClass('trigger_opened').addClass('trigger_closed');
            $(this).removeClass("opened").addClass("closed").attr("style", "");
            
        });
        
        $(this).remove();
        
        $.post( po_object.ajax_url, { action  : 'po_mark_tab_complete', tab_id : tab_id, user_id : po_object.user_id }, function( response ) {
            console.log( "po_mark_tab_complete: ", response );
            
            if( response.data.message ){
                
                
            }
            
        }, "json");
        
    });
    
    // Overview: mark tab complete
    $('body').on('change', '#should_alphabetize_menu', function(){
        
        $('body').addClass("po_is_recreating_menu").append('<div id="po_please_wait"><div id="po_please_wait_message">Please wait...</div></div>');
        
        let should = $(this).prop('checked');
        
        // console.log( "should: ", should );
        
        $.post( po_object.ajax_url, { action  : 'po_save_option_alphabetize_menu', should_alphabetize : should }, function( response ) {
            console.log( "po_save_option_alphabetize_menu: ", response );
            
            if( response.data.message ){
                
                window.location.href = $('#wp-admin-bar-plugin_optimizer_recreate_the_menu a').attr("href");
            }
            
        }, "json");
        
    });
    
    // Overview: mark tab complete
    $('body').on('change', '#the-list .turn_off_filter', function(){
        
        let turned_off = ! $(this).prop('checked');
        let post_id    = $(this).data('id');
        
        // console.log( "post_id: ", post_id );
        // console.log( "turned_off: ", turned_off );
        
        $.post( po_object.ajax_url, { action  : 'po_turn_off_filter', turned_off : turned_off, post_id : post_id }, function( response ) {
            console.log( "po_save_option_alphabetize_menu: ", response );
            
            if( response.data.message ){
                
                
            }
            
        }, "json");
        
    });
    
    // check if all plugins from groups have been manually enabled
    function check_group_plugins_state(){
        
        // TODO Once a group is selected, you can manually re-enable each plugin and if all are enabled, we should deselect the group
    }
    
});