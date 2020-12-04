let getWorklist;
(function ($) {
    // 'use strict';

    $(document).ready(function () {
        //worklist

        getWorklist = () => {
            const params = window
                .location
                .search
                .replace('?','')
                .split('&')
                .reduce(
                    function(p,e){
                        const a = e.split('=');
                        p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                        return p;
                    },
                    {}
                );
            if(params['work_title']){
                $('input[name="title_filter"]').val(`Page acceleration ${params['work_title'].replace('_', ' ')}`);
                $('#search_pages').val(params['work_link']);
                $(`#create_elements`).css(`display`, `block`);
            } else if(params['filter_title']) {
                $(`tr.block_info > td:nth-child(2):contains(${params['filter_title'].replace('+', ' ')})`).parent().next('.hidden_info').css('display', 'table-row');
                $('html').animate({ scrollTop: $(`tr.block_info > td:nth-child(2):contains(${params['filter_title'].replace('+', ' ')})`).parent().next('.hidden_info').children().children().children('.block-plugin-wrapper').children().children('.plugin-wrapper').offset().top - 100 }, 1000);
            }
        };

    });
})(jQuery);

export {getWorklist};