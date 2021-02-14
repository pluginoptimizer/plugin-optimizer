let changePermalink;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*     Change the selected links    */
        changePermalink = () => {

            $('.data-link').on('input', function (){
                const text_link = $(this).text();
                const filter_id = $(this).attr('filter_id');
                
                console.log( "aAjax: change-permalink.js" );
                
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_change_permalink',
                        'text_link': text_link,
                        'filter_id': filter_id,
                    },
                    success: function ({data}) {
                        $(`tr#filter-${filter_id}>.data-link-filter`).text(data);
                    }
                });
            })


        }
    });
})(jQuery);

export {changePermalink};