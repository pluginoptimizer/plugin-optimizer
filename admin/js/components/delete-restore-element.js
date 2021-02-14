import {allElements} from './check-all-element.js';
import {hiddenInfoFilter} from "./hidden-info-filter.js";

let deleteRestoreElement;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // delete or restore element
        deleteRestoreElement = () =>{
            $('#btn_apply').click(function () {
                let name_post_type;
                if($('#name_page').attr("class") === 'worklist'){
                    name_post_type = 'sos_work';
                } else if($('#name_page').attr("class") === 'filters'){
                    name_post_type = 'sos_filter';
                } else if($('#name_page').attr("class") === 'groups'){
                    name_post_type = 'sos_group';
                } else if($('#name_page').attr("class") === 'filters_categories'){
                    name_post_type = 'cat';
                }

                if($('#check_all_elements option:selected').text() === 'Delete'){
                    let type_elements;
                    if($('#all_elements').css('font-weight') === '700'){
                        type_elements = 'all';
                    } else {
                        type_elements = 'trash';
                    }
                    $.ajax({
                        url: plugin_optimizer_groups.ajax_url,
                        type: 'POST',
                        data: {
                            action          : 'sos_delete_elements',
                            'name_post_type': name_post_type,
                            'type_elements' : type_elements,
                            'id_elements'   : $('input:checked').toArray().map(item => item.id).join(','),
                        },
                        success: function (response) {
                            $('#the-list').html(response.data);
                            if($('#check_all').is( ":checked" )){
                                $('#check_all').prop('checked', false);
                            }
                            allElements.count_element(name_post_type);
                            allElements.check_all_element();
                            hiddenInfoFilter();
                        }
                    });
                } else if($('#check_all_elements option:selected').text() === 'Restore'){
                    $.ajax({
                        url: plugin_optimizer_groups.ajax_url,
                        type: 'POST',
                        data: {
                            action          : 'sos_publish_elements',
                            'name_post_type': name_post_type,
                            'id_elements'   : $('input:checked').toArray().map(item => item.id).join(','),
                        },
                        success: function (response) {
                            $('#the-list').html(response.data);
                            if($('#check_all').is( ":checked" )){
                                $('#check_all').prop('checked', false);
                            }
                            allElements.count_element(name_post_type);
                            allElements.check_all_element();
                            hiddenInfoFilter();
                        }
                    });
                }

            });
        }
    });
})(jQuery);

export {deleteRestoreElement};