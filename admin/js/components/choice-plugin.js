let choicePlugins;
(function ($) {
    'use strict';

    $(document).ready(function () {


        // Select a plugins for a new filter or select plugins for a new group
        choicePlugins = () => {
            $('body').on('click', '.block-plugin-wrapper .content', function(){

                console.log( "choice-plugin.js" );
                
                if($(this).hasClass('block')){
                    $(this).removeClass('block');
                } else {
                    $(this).addClass('block');
                }
            })

        }
    });
})(jQuery);

export {choicePlugins};