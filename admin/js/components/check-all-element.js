let allElements;
(function ($) {
    'use strict';

    $(document).ready(function () {
        /*
        * Actions for all elements
        * */
        allElements = {
            /*
            * Actions checkbox for elements
            * */
            check_all_element : function(){
                /*
                * Select all elements
                * */
                $('#check_all').change(function () {
                    if($(this).is( ":checked" )){
                        $('tbody input:checkbox').prop('checked', true);
                    } else {
                        $('tbody input:checkbox').prop('checked', false);
                    }
                });
                /*
                * Change appearance checkbox all elements
                * */
                $('tbody input:checkbox').change(function(){
                    if($('#check_all').is( ":checked" )){
                        $('#check_all').prop('checked', false);
                    }
                    if($('tbody input:checkbox').length === $('tbody input:checkbox:checked').length){
                        $('#check_all').prop('checked', true);
                    }
                });
            },
            /*
            * Change count elements after deleting or adding
            * */
            count_element : function(name_post_type){
                
                console.log( "aAjax: check-all-element.js" );
                
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
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