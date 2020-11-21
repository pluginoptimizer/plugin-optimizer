import { deleteCategory } from './delete-category.js';

let addCategory;
(function ($) {
    'use strict';

    $(document).ready(function () {
        //all elements
        addCategory = () => {
            $('.add-category').click(function (e) {
                const self = this;
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_create_category',
                        'name_category': $(this).prev().val(),
                        'id_filter': $(this).val().substr(5),
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