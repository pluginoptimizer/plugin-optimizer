import {allElements} from './check-all-element.js';
import {hiddenInfoFilter} from './hidden-info-filter.js';

let showAllElements;
(function ($) {
    'use strict';

    $(document).ready(function () {
        //all elements
        showAllElements = () => {
            $('#all_elements').click(function () {
                let name_post_type;
                if($('#name_page').text() === 'worklist'){
                    name_post_type = 'sos_work';
                } else if($('#name_page').text() === 'filters'){
                    name_post_type = 'sos_filter';
                } else if($('#name_page').text() === 'groups'){
                    name_post_type = 'sos_group';
                }
                $('select#check_all_elements option[value="restore"]').remove();
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_all_elements',
                        'name_post_type': name_post_type,
                    },
                    success: function (response) {
                        $('#all_elements').css('font-weight', '700');
                        $('#trash_elements').css('font-weight', '400');
                        $('#the-list').html(response.data);

                        allElements.check_all_element();
                        hiddenInfoFilter();
                    }
                });
            });
        }
    });
})(jQuery);

export {showAllElements};