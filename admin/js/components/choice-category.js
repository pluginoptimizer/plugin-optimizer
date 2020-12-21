let choiceCategories;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*  Select a category for a new filter   */
        choiceCategories = () => {


            $(`.category-wrapper .content`).click(function(){
                if($(this).hasClass('block')){
                    $(this).removeClass('block');
                } else {
                    $(this).addClass('block');
                }
            })

        }
    });
})(jQuery);

export {choiceCategories};