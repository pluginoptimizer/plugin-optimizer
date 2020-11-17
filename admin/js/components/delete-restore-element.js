import {allElements} from './check-all-element.js';

let deleteRestoreElement;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // delete or restore element
        deleteRestoreElement = () =>{
            $('#btn_apply').click(function () {
                let name_post_type;
                if($('#name_page').text() === 'worklist'){
                    name_post_type = 'sos_work';
                } else if($('#name_page').text() === 'filters'){
                    name_post_type = 'sos_filter';
                } else if($('#name_page').text() === 'groups'){
                    name_post_type = 'sos_group';
                }

                if($('#check_all_elements option:selected').text() === 'Delete'){
                    let type_works;
                    if($('#all_elements').css('font-weight') === '700'){
                        type_works = 'all';
                    } else {
                        type_works = 'trash';
                    }
                    $.ajax({
                        url: simple_online_systems_groups.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'sos_delete_elements',
                            'name_post_type': name_post_type,
                            'type_works': type_works,
                            'id_works': $('input:checked').toArray().map(item => item.id).join(','),
                        },
                        success: function (response) {
                            $('#the-list').html(response.data);
                            if($('#check_all').is( ":checked" )){
                                $('#check_all').prop('checked', false);
                            }
                            allElements.count_element(name_post_type);
                            allElements.check_all_element();
                        }
                    });
                } else if($('#check_all_elements option:selected').text() === 'Restore'){
                    $.ajax({
                        url: simple_online_systems_groups.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'sos_publish_elements',
                            'name_post_type': name_post_type,
                            'id_works': $('input:checked').toArray().map(item => item.id).join(','),
                        },
                        success: function (response) {
                            $('#the-list').html(response.data);
                            if($('#check_all').is( ":checked" )){
                                $('#check_all').prop('checked', false);
                            }
                            allElements.count_element(name_post_type);
                            allElements.check_all_element();
                        }
                    });
                }

            });
        }
    });
})(jQuery);

export {deleteRestoreElement};