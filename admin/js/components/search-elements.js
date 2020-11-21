let searchElements;
(function ($) {
    'use strict';

    $(document).ready(function () {
        //search elements
        searchElements = () => {
            $('#search_elements').keyup(function () {
                let name_post_type;
                if($('#name_page').text() === 'worklist'){
                    name_post_type = 'sos_work';
                } else if($('#name_page').text() === 'filters'){
                    name_post_type = 'sos_filter';
                } else if($('#name_page').text() === 'groups'){
                    name_post_type = 'sos_group';
                } else if($('#name_page').text() === 'Filters categories'){
                    name_post_type = 'cat';
                }
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
                        action: 'sos_search_elements',
                        'name_post_type': name_post_type,
                        'type_works': type_works,
                        'keyword': $('#search_elements').val(),
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                    }
                });
            });
        }
    });
})(jQuery);

export {searchElements};