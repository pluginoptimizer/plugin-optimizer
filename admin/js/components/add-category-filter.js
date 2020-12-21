import {deleteCategory} from "./delete-category.js";
import {addCategory} from "./add-category.js";

let addCategoryFilter;
(function ($) {
    'use strict';

    $(document).ready(function () {

        addCategoryFilter = () => {
            $('.filter-category').click(function () {
                let self = this;
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_add_category_to_filter',
                        'id_category': $(this).children('span.close').attr('id'),
                        'id_filter': $(this).parent().children('button').attr('id').substr(5),
                        'trigger': $(this).hasClass(`block`) ? `delete` : `add`,
                    },
                    success: function (response) {
                        $(self).parent().html(response.data);
                        deleteCategory();
                        addCategory();
                        addCategoryFilter();
                    }
                });
            })
        }
    });
})(jQuery);

export {addCategoryFilter};