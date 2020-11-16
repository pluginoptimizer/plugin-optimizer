import {allElements} from "./check-all-element.js";
import {hiddenInfoFilter} from './hidden-info-filter.js';

let trashElements;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // trash elements
        trashElements = () => {
            $('#trash_elements').click(function () {
                let name_post_type;
                if($('#name_page').text() === 'worklist'){
                    name_post_type = 'sos_work';
                } else if($('#name_page').text() === 'filters'){
                    name_post_type = 'sos_filter';
                }
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_trash_elements',
                        'name_post_type': name_post_type,
                    },
                    success: function (response) {
                        $('#trash_elements').css('font-weight', '700');
                        $('#all_elements').css('font-weight', '400');
                        $('#the-list').html(response.data);
                        $('select#check_all_elements option[value="delete"]').before('<option value="restore">Restore</option>');

                        allElements.check_all_element();
                        hiddenInfoFilter();
                    }
                });
            });
        }
    });
})(jQuery);

export {trashElements};