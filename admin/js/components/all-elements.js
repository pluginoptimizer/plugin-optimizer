import {allElements} from './check-all-element.js';
import {hiddenInfoFilter} from './hidden-info-filter.js';

let showAllElements;
(function ($) {
    'use strict';

    $(document).ready(function () {
        /*
        * Show the available items on their pages after updating the data
        * @let      text name_post_type depending on which page we are on now we understand what type of data is needed
        * */
        showAllElements = () => {
            $('#all_elements').click(function () {
                let name_post_type;
                if($('#name_page').attr("class") === 'worklist'){
                    name_post_type = 'sos_work';
                } else if($('#name_page').attr("class") === 'filters'){
                    name_post_type = 'sos_filter';
                } else if($('#name_page').attr("class") === 'groups'){
                    name_post_type = 'sos_group';
                }
                /* Remove the ability to recover if we are out of the cart */
                $('select#check_all_elements option[value="restore"]').remove();
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_all_elements',
                        'name_post_type': name_post_type,
                    },
                    success: function (response) {
                        /* change the weight of the font to a larger one for the "ALL" link */
                        $('#all_elements').css('font-weight', '700');
                        /* change the font weight to a smaller one for the "TRASH" link */
                        $('#trash_elements').css('font-weight', '400');
                        /* Change the content of the page */
                        $('#the-list').html(response.data);

                        /* Added the ability check elements */
                        allElements.check_all_element();
                        /* Added the ability to hidden info filter */
                        hiddenInfoFilter();
                    }
                });
            });
        }
    });
})(jQuery);

export {showAllElements};