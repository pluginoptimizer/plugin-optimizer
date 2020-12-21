let transitionElements;
(function ($) {
    'use strict';

    $(document).ready(function () {

        transitionElements = () => {



            transitionElements = () => {
                $(`#window_filters, #window_categories, #window_groups, #window_worklist`).click(function(){
                    const selfId = $(this).attr(`id`);
                    $.ajax({
                        url: plugin_optimizer_groups.ajax_url,
                        type: `POST`,
                        data: {
                            action: `sos_transition_viewed`,
                            selfId: selfId
                        },
                        success: function (response) {
                            $(`.wrap`).html(response.data);
                            allFunction();
                            transitionElements();
                        }
                    });
                })
            }

            transitionElements();

        }
    });
})(jQuery);

export {transitionElements};