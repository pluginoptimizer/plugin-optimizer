let selectAllPlugins;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*  Select all plugins for a new filter
        * */
        selectAllPlugins = () => {
            $('.all-check').click(function(){
                if($(this).text() === 'Disable All'){
                    $(this).text('Enable All');
                    $(this).parent().parent().children('.plugin-wrapper').children('.content').addClass('block');
                } else {
                    $(this).text('Disable All');
                    $(this).parent().parent().children('.plugin-wrapper').children('.content').removeClass('block');
                }
            })

        }
    });
})(jQuery);

export {selectAllPlugins};