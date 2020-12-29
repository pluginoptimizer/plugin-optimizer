let showPluginsSettings;
(function ($) {
    // 'use strict';

    $(document).ready(function () {

        showPluginsSettings = () => {
            $(`#activate_plugins, #deactivate_plugins`).click(function (){
                const self = this;
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_show_plugins',
                        type_plugins: $(self).attr(`id`),
                    },
                    success: function (response) {
                        $(self).css(`font-weight`, 600);
                        if($(self).attr(`id`) === 'activate_plugins'){
                            $(`#deactivate_plugins`).css(`font-weight`, 400);
                        } else {
                            $(`#activate_plugins`).css(`font-weight`, 400);
                        }
                        $('#the-list').html(response.data);
                    }
                });
            })
        };

    });
})(jQuery);

export {showPluginsSettings};