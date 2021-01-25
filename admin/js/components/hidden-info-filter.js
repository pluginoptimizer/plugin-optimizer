let hiddenInfoFilter;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // hidden info filter
        hiddenInfoFilter = () =>{
            $('.block_info > td:not(:nth-child(1))').click(function () {
                const element_id = $(this).parent().children('td:nth-child(1)').children().attr('id');

                if($('#name_page').attr("class") === 'filters_categories'){
                    location.href=`/wp-admin/term.php?taxonomy&tag_ID=${element_id}&post_type=sos_filter`;
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