import { hiddenInfoFilter } from './hidden-info-filter.js';

let changePluginsGroup;
(function ($) {
    'use strict';

    $(document).ready(function () {
        /*
        * Change plugins to group
        * */
        changePluginsGroup = () => {
            $('.wrapper-group-plugins .content').click(function () {
                console.log('click')
                const plugin_name = $(this).children().text();
                const block_plugins = $(`#block_plugins`);

                if(!$(this).hasClass(`block`)){
                    /* Change appearance */
                    $(this).addClass(`block`);

                    /* Record data of selected plugins */
                    block_plugins.val() ? block_plugins.val(`${block_plugins.val()}, ${plugin_name}`) : block_plugins.val(plugin_name);
                } else {
                    /* Change appearance */
                    $(this).removeClass(`block`);

                    /* Delete data of selected plugins */
                    block_plugins.val(block_plugins.val().split(', ').filter(item => item !== plugin_name).join(', '))
                }
                /*$.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_change_plugins_to_group',
                        'group_id': $(this).attr('group_id'),
                        'plugin_name':  $(this).children().text(),
                        'trigger': $(this).hasClass(`block`) ? `delete` : `add`,
                    },
                    success: function (response) {
                        $('#the-list').html(response.data.return);
                        $(`tr#group_${response.data.group_id}`).next('.hidden_info').css('display', 'table-row');
                        hiddenInfoFilter();
                        changePluginsGroup();
                    }
                });*/
            })
        }
    });
})(jQuery);

export {changePluginsGroup};