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


                const block_plugins = $(`#block_plugins`);
                const block_link_plugins = $(`#block_link_plugins`);
                const block_group = $(`#block_group_plugins`);


                if(!$(this).hasClass(`block`)){
                    /* Change appearance */
                    $(this).parent().addClass(`block`);

                    /* Record data of group plugins */
                    block_group.val() !== `None` ? block_group.val(`${block_group.val()}, ${group_name}`) : block_group.val(group_name);

                    /* Record data of selected plugins */
                    block_plugins.val() ? block_plugins.val(`${block_plugins.val()}, ${plugins_names}`) : block_plugins.val(plugins_names);

                    /* Record data of selected link plugins */
                    block_link_plugins.val() ? block_link_plugins.val(`${block_link_plugins.val()}, ${plugins_links}`) : block_link_plugins.val(plugins_links);
                } else {
                    /* Change appearance */
                    $(this).parent().removeClass(`block`);
                    /* Delete data of selected plugins */
                    block_group.val(block_group.val().split(', ').filter(item => item !== group_name).join(', '))

                    /* Delete data of selected plugins */
                    block_plugins.val(block_plugins.val().split(', ').filter(item => item !== plugins_names).join(', '))

                    /* Delete data of selected plugins */
                    block_link_plugins.val(block_link_plugins.val().split(', ').filter(item => item !== plugins_links).join(', '))
                }

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