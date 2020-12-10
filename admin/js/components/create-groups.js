import {allElements} from "./check-all-element.js";
import {hiddenInfoFilter} from "./hidden-info-filter.js";

let createGroupPlugins;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // create plugins
        createGroupPlugins = () => {
            $('.save-group').click(function () {

                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_add_group_plugins',
                        'title_group': $('#set_title').val(),
                        'type_group': $('#set_type').val(),
                        'group_parents': $(`.block-group-plugin-wrapper .block span`).toArray().map(item => $(item).text()).join(', '),
                        'group_plugins': $(`.block-plugin-wrapper .block span`).toArray().map(item => $(item).text()).join(', '),
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                        $('.content-new-element').css('display', 'none');
                        allElements.count_element('sos_group');
                        allElements.check_all_element();
                        hiddenInfoFilter();
                    }
                })
            });
        }
    });
})(jQuery);

export {createGroupPlugins};