import {allElements} from "./check-all-element.js";
import {hiddenInfoFilter} from "./hidden-info-filter.js";

let createCat;

(function ($) {
    'use strict';

    $(document).ready(function () {
        //change plugins
        createCat = () => {
            $(`.save-category`).click(function () {
                console.log(`click`);
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_create_cat_subcat',
                        'name_category': $(`#set_title`).val(),
                        'parent_category':  $('.category-wrapper .block span').toArray().map(item => $(item).attr('value')).join(', '),
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                        $('.content-new-element').css('display', 'none');
                        allElements.count_element('cat');
                        allElements.check_all_element();
                        hiddenInfoFilter();
                    }
                });
            })
        }
    });
})(jQuery);

export {createCat};