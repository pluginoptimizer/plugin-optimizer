import { deleteCategory } from './delete-category.js';

let addCategory;
(function ($) {
    'use strict';

    $(document).ready(function () {
        //all elements
        addCategory = () => {
            $('.add-category').click(function (e) {
                const self = this;
                const name_category = $(this).prev().val();
                const id_filter = $(this).attr('id').substr(5);
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_create_category',
                        'name_category': name_category,
                        'id_filter': id_filter,
                    },
                    success: function (response) {
                        $(self).parent().html(response.data);
                        deleteCategory();
                    }
                });
            })
        }
    });
})(jQuery);

export {addCategory};