import { deleteCategory } from './delete-category.js';

let addCategory;
(function ($) {
    'use strict';

    $(document).ready(function () {
        /*
        * Add new category for filters on filters page
        * @const      object self button "+ category" in categories block
        * @const      text name_category name new category entered by the client in category block
        * @const      text id_filter the ID of the filter for which the category is created
        * */
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
                        /* Change the content of the block of categories already with a new category */
                        $(self).parent().html(response.data);
                        /* Added the ability to delete categories, because after changing the look, the created events are deleted */
                        deleteCategory();
                    }
                });
            })
        }
    });
})(jQuery);

export {addCategory};