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
                    $(this).parent().parent().children(`.plugin-wrapper`).children(`.content`).addClass(`block`);
                } else {
                    $(this).text(`All disable`);
                    $(this).parent().parent().children(`.plugin-wrapper`).children(`.content`).removeClass(`block`);
                }
            })

        }
    });
})(jQuery);

export {selectAllPlugins};