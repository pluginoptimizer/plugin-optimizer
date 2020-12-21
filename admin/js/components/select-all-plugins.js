let selectAllPlugins;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*  Select all plugins for a new filter
        * */
        selectAllPlugins = () => {
            $(`.all-check`).click(function(){
                if($(this).text() === `All disable`){
                    $(this).text(`All enable`);
                    $(`.block-plugin-wrapper .content`).addClass(`block`);
                } else {
                    $(this).text(`All disable`);
                    $(`.block-plugin-wrapper .content`).removeClass(`block`);
                }
            })

        }
    });
})(jQuery);

export {selectAllPlugins};