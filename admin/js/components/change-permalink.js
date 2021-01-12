let changePermalink;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*     Change the selected links    */
        changePermalink = () => {

            $(`.show-link`).change(function (){
                const text_link = $(this).val();
                const filter_id = $(this).attr(`filter_id`);

                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_change_permalink',
                        'text_link': text_link,
                        'filter_id': filter_id,
                    },
                    success: function (response) {
                        // $(self).parent().html(response.data);
                    }
                });
            })


        }
    });
})(jQuery);

export {changePermalink};