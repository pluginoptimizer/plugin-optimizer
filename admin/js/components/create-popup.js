let createPopup;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // create plugins
        createPopup = () => {
            $('#add_elements').click(function(){
                if($('#name_page').hasClass('filters')){
                    location.href='/wp-admin/admin.php?page=plugin_optimizer_add_filters';
                } else if($('#name_page').hasClass('groups')){
                    location.href='/wp-admin/admin.php?page=plugin_optimizer_add_groups';
                } else {
                    $('#create_elements').css('display', 'block');
                    if($('.content-new-element').css('display') === 'block'){
                        $('.content-new-element').css('display', 'none');
                    } else {
                        $('.content-new-element').css('display', 'block');
                    }
                }
            })
            $('.wrapper_create-elements > .popup-close').click(function(){
                $('#create_elements').css('display', 'none');
            })
        }
    });
})(jQuery);

export { createPopup };