import {allElements} from "./check-all-element.js";

let createdFilters;
(function ($) {
    'use strict';

    $(document).ready(function () {
        //created filters
        createdFilters = () => {
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
                        allElements.count_element(name_post_type);
                        allElements.check_all_element();
                    }
                })
            });
        }
    });
})(jQuery);

export { createdFilters };