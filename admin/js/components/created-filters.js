import {allElements} from "./check-all-element.js";
import {hiddenInfoFilter} from "./hidden-info-filter.js";
import {changePlugins} from "./change-plugins.js";

let createdFilters;
(function ($) {
    'use strict';

    $(document).ready(function () {
        //created filters
        createdFilters = () => {
            $('.created-filters input[type="submit"]').click(function (e) {
                e.preventDefault();

                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_add_plugin_to_filter',
                        'block_plugins': $('select[name="block_plugins"] option:selected').toArray().map(item => item.text).join(', '),
                        'block_value_plugins': $('select[name="block_plugins"] option:selected').toArray().map(item => item.value).join(', '),
                        'block_group_plugins': $('select[name="block_group_plugins"] option:selected').toArray().map(item => item.text).join(', '),
                        'post_type': $('select[name="post_type"] option:selected').toArray().map(item => item.text).join(', '),
                        'pages': $('input[name="pages"]').val(),
                        'title_filter': $('input[name="title_filter"]').val(),
                        'type_filter': $('input[name="type_filter"]').val(),
                        'category_filter': $('select[name="category_filter"] option:selected').text(),
                        'category_id_filter': $('select[name="category_filter"] option:selected').val(),
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                        $('#create_elements').css('display', 'none');
                        allElements.count_element('sos_filter');
                        allElements.check_all_element();
                        hiddenInfoFilter();
                        changePlugins();
                    }
                })
            });
        }
    });
})(jQuery);

export { createdFilters };