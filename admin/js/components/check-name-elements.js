let checkNameElements;

(function ($) {
    'use strict';

    $(document).ready(function () {
        //change plugins
        checkNameElements = () => {
            $('#set_title').change(function () {
                const name_element = $(this).val();
                const type_element = $(`#name_page`).attr(`class`);
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_check_name_elements',
                        'name_element': name_element,
                        'type_element': type_element,
                    },
                    success: function (response) {
                        if( response.data === true && response.data !== `nothing` ){
                            $(`#set_title`).css(`border`, `1px solid red`);
                            $('#set_title').val(``);
                            $('#set_title').focus();
                        } else {
                            $(`#set_title`).css(`border-top`, `0`);
                            $(`#set_title`).css(`border-right`, `0`);
                            $(`#set_title`).css(`border-left`, `0`);
                            $(`#set_title`).css(`border-bottom`, `1px solid #000`);
                        }
                    }
                });
            })

            $(`#group_name_error .popup-close`).click(function () {
                $(`#set_title`).css(`border`, `1px solid red`);
                $('#set_title').val(``);
                $('#set_title').focus();
            })
        }
    });
})(jQuery);

export {checkNameElements};