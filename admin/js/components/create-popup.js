let createPopup;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // create plugins
        createPopup = () => {
            $(`#add_group`).click(function(){
                $('#create_elements').css('display', 'block');
            })
            $(`#create_elements .popup-close`).click(function(){
                $('#create_elements').css('display', 'none');
            })
        }
    });
})(jQuery);

export { createPopup };