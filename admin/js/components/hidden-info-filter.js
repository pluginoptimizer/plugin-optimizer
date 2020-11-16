let hiddenInfoFilter;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // hidden info filter
        hiddenInfoFilter = () =>{
            $('.filter_block').click(function () {
                if($(this).next().css('display') === 'none'){
                    $(this).next().css('display', 'table-row');
                } else{
                    $(this).next().css('display', 'none');
                }
            })
        }

    });

})(jQuery);

export {hiddenInfoFilter};