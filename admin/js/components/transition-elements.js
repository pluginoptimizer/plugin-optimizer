let transitionElements;
(function ($) {
    'use strict';

    $(document).ready(function () {

        transitionElements = () => {



            transitionElements = () => {
                $('#window_filters, #window_categories, #window_groups, #window_worklist, #window_settings').click(function(){
                    $('.tabs').css('background', '#1e4d7d');

                    const selfId = $(this).attr('id');

                    switch (selfId) {
                        case 'window_filters':
                            location.href='/wp-admin/admin.php?page=plugin_optimizer_filters';
                            break;
                        case 'window_categories':
                            location.href='/wp-admin/admin.php?page=plugin_optimizer_filters_categories';
                            break;
                        case 'window_groups':
                            location.href='/wp-admin/admin.php?page=plugin_optimizer_groups';
                            break;
                        case 'window_worklist':
                            location.href='/wp-admin/admin.php?page=plugin_optimizer_worklist';
                            break;
                        case 'window_settings':
                            location.href='/wp-admin/admin.php?page=plugin_optimizer_settings';
                            break;
                    }

                    $(`#${selfId}`).css('background-color', '#d7b70a');

                })
            }

            transitionElements();

        }
    });
})(jQuery);

export {transitionElements};