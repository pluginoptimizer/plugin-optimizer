let selectParentCategory;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*  Select a parent for a new category   */
        selectParentCategory = () => {
            $(`.select_parent_to_category`).click(function(){
                const selfText = $(this).text();
                if($(this).hasClass('block')){
                    $(this).removeClass('block');
                    $(`.none_parent`).addClass('block');
                } else {
                    $( `.select_parent_to_category` ).each(function( item ) {
                        if($(this).hasClass(`block`) && selfText !== $(this).text()){
                            $(this).removeClass(`block`);
                        }
                    });
                    $(`.none_parent`).removeClass('block');
                    $(this).addClass('block');
                }

            })
        }
    });
})(jQuery);

export {selectParentCategory};