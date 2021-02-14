import { addCategory } from './add-category.js';

let deleteCategory;
(function ($) {
    'use strict';

    $(document).ready(function () {
        //all elements
        deleteCategory = () => {
            $('.filter-category .close').click(function () {
                let selfDelete = this;
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action          : 'sos_delete_category',
                        'id_category'   : $(this).attr('id'),
                        'id_filter'     : $(this).parent().parent().children('button').attr('id').substr(5),
                    },
                    success: function (response) {
                        $(selfDelete).parent().parent().html(response.data);
                        deleteCategory();
                        addCategory();
                    }
                });
            })
        }
    });
})(jQuery);

export {deleteCategory};