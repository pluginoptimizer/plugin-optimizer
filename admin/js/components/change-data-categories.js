let changeDataCategory;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*     Change the category name   */
        changeDataCategory = () => {

            $(`.data-title-cat`).on(`input`, function (){
                const text_name = $(this).text();
                const cat_id = $(this).attr(`cat_id`);

                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_change_data_category',
                        'text_name': text_name,
                        'cat_id': cat_id,
                    },
                    success: function ({data}) {
                        $(`tr#cat-${cat_id}>.data-title-category`).text(data);
                    }
                });
            })


        }
    });
})(jQuery);

export {changeDataCategory};