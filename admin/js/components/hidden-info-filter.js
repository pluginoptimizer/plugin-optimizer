let hiddenInfoFilter;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // hidden info filter
        hiddenInfoFilter = () =>{
            $('.block_info > td:not(:nth-child(1))').click(function () {
                if($(this).parent().next('.hidden_info').css('display') === 'none'){
                    $(this).parent().next('.hidden_info').css('display', 'table-row');
                } else{
                    $(this).parent().next('.hidden_info').css('display', 'none');
                }
            })
        }

    });

})(jQuery);

export {hiddenInfoFilter};