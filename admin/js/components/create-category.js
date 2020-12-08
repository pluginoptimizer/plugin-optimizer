import {allElements} from "./check-all-element.js";

let createCat;

(function ($) {
    'use strict';

    $(document).ready(function () {
        //change plugins
        createCat = () => {
            $('form.created-cat input[type="submit"]').click(function (e) {
                e.preventDefault();

                let parent_category;
                $( `.select_parent_to_category` ).each(function( item ) {
                    if($(this).hasClass(`block`)){
                        // parent_category_name = $(this).text();
                        parent_category = $(this).attr(`value`);
                    }
                });

                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_create_cat_subcat',
                        'name_category': $(`#title_cat`).val(),
                        // 'parent_category_name': parent_category_name,
                        'parent_category': parent_category,
                        // 'parent_category': $('select[name="cat_parents"] option:selected').val(),
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                        $('#create_elements').css('display', 'none');
                        allElements.count_element('cat');
                        allElements.check_all_element();
                    }
                });
            })
        }
    });
})(jQuery);

export {createCat};