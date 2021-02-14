import {hiddenInfoFilter} from "./hidden-info-filter.js";

let searchElements;
(function ($) {
    'use strict';

    $(document).ready(function () {
        //search elements
        searchElements = () => {
            $('#search_elements').keyup(function () {
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
                        hiddenInfoFilter();
                    }
                });
            });
        }
    });
})(jQuery);

export {searchElements};