let changeGroups;

(function ($) {
    'use strict';

    $(document).ready(function () {
        changeGroups = () => {
            $('.group-wrapper>.content').click(function () {
                const group_name = $(this).children('span').text();
                const filter_id = $(this).attr('value');
                const change_groups = $(this).is('.block') ? 'remove' : 'add';
                const plugins_names = $(this).children('.hidden_content').children().toArray().map(item => $(item).text()).join(', ');
                const plugins_links = $(this).children('.hidden_content').children().toArray().map(item => $(item).attr('value')).join(', ');
                console.log(plugins_names)
                console.log(plugins_links)
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_change_groups_to_filter',
                        'group_name': group_name,
                        'filter_id': filter_id,
                        'plugins_names': plugins_names,
                        'plugins_links': plugins_links,
                        'change_groups': change_groups,
                    },
                    success: function (response) {
                        $(`tr#filter-${response.data.filter_id}`).next('.hidden_info').children().children().children('.content-plugins').html(response.data.content_plugins);
                        $(`tr#filter-${response.data.filter_id}`).next('.hidden_info').children().children().children('.group-wrapper').html(response.data.content_groups);
                        changeGroups();
                    }
                });
            })
        }
    });
})(jQuery);

export {changeGroups};