let changeGroups;

(function ($) {
    'use strict';

    $(document).ready(function () {
        /*
        * Change plugins to filter
        * @const      text filter_id ID filter that changes the presence of plugins
        * @const      text plugin_name name plugin chose
        * @const      text plugin_link link plugin chose
        * @const      text change_plugins ('+' or '×') add or delete to filter
        * */
        changeGroups = () => {
            $('.group-wrapper>.content').click(function () {
                const filter_id = $(this).attr('value');
                const plugin_name = $(this).attr('id');
                const plugin_link = $(this).attr('link');
                const change_plugins = $(this).text();
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_change_groups_to_filter',
                        'filter_id': filter_id,
                        'plugin_name': plugin_name,
                        'plugin_link': plugin_link,
                        'change_plugins': change_plugins,
                    },
                    success: function (response) {
                        /* Change the content of the block plugins */
                        $(`tr#filter-${response.data.filter_id}`).next('.hidden_info').children().children().children('.content-plugins').html(response.data.content_plugins);
                        $(`tr#filter-${response.data.filter_id}`).next('.hidden_info').children().children().children('.group-wrapper').html(response.data.content_groups);
                        /* Added the ability change plugins */
                        changeGroups();
                    }
                });
            })
        }
    });
})(jQuery);

export {changeGroups};