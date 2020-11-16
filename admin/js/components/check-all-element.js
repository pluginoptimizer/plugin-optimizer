let allElements;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // check all element
        allElements = {
            check_all_element : function(){
                $('#check_all').change(function () {
                    if($(this).is( ":checked" )){
                        $('tbody input:checkbox').prop('checked', true);
                    } else {
                        $('tbody input:checkbox').prop('checked', false);
                    }
                });
                $('tbody input:checkbox').change(function(){
                    if($('#check_all').is( ":checked" )){
                        $('#check_all').prop('checked', false);
                    }
                    if($('tbody input:checkbox').length === $('tbody input:checkbox:checked').length){
                        $('#check_all').prop('checked', true);
                    }
                });
            },
            count_element : function(name_post_type){
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_count_elements',
                        'name_post_type': name_post_type,
                    },
                    success: function (response) {
                        try {
                            $('#count_all_elements').text(response.data.all);
                            $('#count_trash_elements').text(response.data.trash);
                        } catch (err) {
                            console.error(err.message);
                        }
                    },
                });
            },
        };
    });
})(jQuery);

export {allElements};