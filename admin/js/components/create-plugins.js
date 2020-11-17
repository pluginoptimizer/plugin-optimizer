import {allElements} from "./check-all-element.js";

let createGroupPlugins;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // create plugins
        createGroupPlugins = () => {
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
                        $('#the-list').html(response.data);
                        allElements.count_element('sos_group');
                        allElements.check_all_element();
                    }
                })
            });
        }
    });
})(jQuery);

export { createGroupPlugins };