let createPopup;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // create plugins
        createPopup = () => {
            $(`#add_elements`).click(function(){
                $('#create_elements').css('display', 'block');
                if($('.content-new-element').css('display') === 'block'){
                    $('.content-new-element').css('display', 'none');
                } else {
                    $('.content-new-element').css('display', 'block');
                }
            })
            $(`.wrapper_create-elements > .popup-close`).click(function(){
                $('#create_elements').css('display', 'none');
            })
        }
    });
})(jQuery);

export { createPopup };