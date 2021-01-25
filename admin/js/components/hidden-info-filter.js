let hiddenInfoFilter;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // hidden info filter
        hiddenInfoFilter = () =>{
            $('.block_info > td:not(:nth-child(1))').click(function () {
                const element_id = $('.block_info > td:nth-child(1)  > input').attr('id');

                if($('#name_page').attr("class") === 'filters_categories'){
                    $.ajax({
                        url: plugin_optimizer_groups.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'sos_get_category_link',
                            cat_id: element_id,
                        },
                        success: function ({data}) {
                            // console.log(data);
                            location.href=`${data}`;
                        }
                    });
                } else {
                    location.href=`/wp-admin/post.php?post=${element_id}&action=edit`;
                }

                /*if($(this).parent().next('.hidden_info').css('display') === 'none'){
                    $(this).parent().next('.hidden_info').css('display', 'table-row');
                } else{
                    $(this).parent().next('.hidden_info').css('display', 'none');
                }*/
            })
        }

    });

})(jQuery);

export {hiddenInfoFilter};