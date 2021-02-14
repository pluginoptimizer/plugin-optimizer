import {allElements} from "./check-all-element.js";
import {hiddenInfoFilter} from "./hidden-info-filter.js";
import {selectParentCategory} from './select-parent-category.js';

let createCat;

(function ($) {
    'use strict';

    $(document).ready(function () {
        //change plugins
        createCat = () => {
            $('.save-category').click(function () {
                let result = true;
                $('.content-new-element input').toArray().some(function (item) {
                    if ($(item).val().trim() === "" && result) {
                        $(item).focus();
                        return result = false;
                    }
                })
                if (!result) {
                    return false;
                }

                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_create_cat_subcat',
                        'name_category': $('#set_title').val(),
                        'description_category': $('#set_description').val(),
                        'parent_category': $('.parent-category-wrapper .block span').toArray().map(item => $(item).attr('value')).join(', '),
                    },
                    success: function ({data}) {
                        $('#the-list').html(data);
                        $('.content-new-element').css('display', 'none');
                        $('#set_title').val('');
                        allElements.count_element('cat');
                        allElements.check_all_element();
                        hiddenInfoFilter();
                        $.ajax({
                            url: plugin_optimizer_groups.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'sos_get_parent_cat',
                            },
                            success: function ({data}) {
                                $('.content-new-element .plugin-wrapper').html(data);
                                selectParentCategory();
                            }
                        });
                    }
                });
            })
        }
    });
})(jQuery);

export {createCat};