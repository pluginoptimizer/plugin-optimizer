let choiceCategories;
(function ($) {
    'use strict';

    $(document).ready(function () {


        // Select a category for a new filter
        choiceCategories = () => {
            $('body').on('click', '.category-wrapper .content', function(){

                console.log( "choice-category.js" );
                
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