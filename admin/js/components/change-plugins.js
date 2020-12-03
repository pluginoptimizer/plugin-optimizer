let changePlugins;

(function ($) {
    'use strict';

    $(document).ready(function () {
        //change plugins
        changePlugins = () => {
            $('.close').click(function () {
                const filter_id = $(this).attr('value');
                const plugin_name = $(this).attr('id');
                const plugin_link = $(this).attr('link');
                const change_plugins = $(this).text();
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_change_plugins_to_filter',
                        'filter_id': filter_id,
                        'plugin_name': plugin_name,
                        'plugin_link': plugin_link,
                        'change_plugins': change_plugins,
                    },
                    success: function (response) {
                        $(`tr#filter-${response.data.filter_id}`).next('.hidden_info').children().children().children('.block-plugin-wrapper').html(response.data.return);
                        changePlugins();
                    }
                });
            })
        }
    });
})(jQuery);

export {changePlugins};