let checkNameGroup;

(function ($) {
    'use strict';

    $(document).ready(function () {
        //change plugins
        checkNameGroup = () => {
            $('#title_group').change(function () {
                const name_group = $(this).val();
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_check_name_group',
                        'name_group': name_group,
                    },
                    success: function (response) {
                        if( response.data === true && response.data !== `nothing` ){
                            $('#group_name_error').css('display', 'block');
                        }
                    }
                });
            })

            $(`#group_name_error .popup-close`).click(function () {
                $(`#group_name_error`).css(`display`, `none`);
                $('#title_group').val(``);
                $('#title_group').focus();
            })
        }
    });
})(jQuery);

export {checkNameGroup};