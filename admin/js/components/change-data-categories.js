let changeDataCategory;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*     Change the category name   */
        changeDataCategory = () => {

            $(`.data-title-cat`).on(`input change`, function (){
                const text_name = $(this).text().trim();
                const description_category = $(this).parent().parent().parent().parent().children(`.description`).children().children(`.content-description`).children(`.data-description-cat`).text().trim();
                const cat_id = $(this).attr(`cat_id`);

                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_change_data_category',
                        'text_name': text_name,
                        'description_category': description_category,
                        'cat_id': cat_id,
                    },
                    success: function ({data}) {
                        $(`tr#cat-${cat_id}>.data-title-category`).text(data);
                    }
                });
            })

            $(`.data-description-cat`).on(`input`, function (){
                $(`.data-title-cat`).change();
            })


        }
    });
})(jQuery);

export {changeDataCategory};