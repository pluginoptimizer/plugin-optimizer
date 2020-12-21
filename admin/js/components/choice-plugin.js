let choicePlugins;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*  Select a plugins for a new filter
        *   or select plugins for a new group
        * */
        choicePlugins = () => {

            $(`.block-plugin-wrapper .content`).click(function(){
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