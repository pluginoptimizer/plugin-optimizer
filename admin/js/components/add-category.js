import {allElements} from './check-all-element.js';
import {hiddenInfoFilter} from './hidden-info-filter.js';

let addCategory;
(function ($) {
    'use strict';

    $(document).ready(function () {
        //all elements
        addCategory = () => {
            $('.add-category').click(function (e) {
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_create_category',
                        'name_category': $(this).prev().val(),
                        'id_filter': $(this).val().substr(5),
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                        allElements.check_all_element();
                        hiddenInfoFilter();
                    }
                });
            })
        }
    });
})(jQuery);

export {addCategory};