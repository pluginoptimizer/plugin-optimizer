let changeType;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*     Change the selected type    */
        changeType = () => {

            $('.data-type').on('input', function (){
                const text_type = $(this).text();
                const filter_id = $(this).attr('filter_id');

                $('#type_filter').val(text_type.trim());

                $(`tr#filter-${filter_id}>.data-type-filter`).text(text_type);

                /*$.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_change_type',
                        'text_type': text_type,
                        'filter_id': filter_id,
                    },
                    success: function ({data}) {
                        $(`tr#filter-${filter_id}>.data-type-filter`).text(data);
                    }
                });*/
            })


        }
    });
})(jQuery);

export {changeType};