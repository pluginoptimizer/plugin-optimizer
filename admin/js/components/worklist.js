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
                $('input#set_title').val(`Optimization for ${params['work_title'].replace('_', ' ')}`);
                params['work_link'].includes('http') ? $('#search_pages').val(params['work_link']) : $(`span[value="${params['work_link']}"]`).parent().addClass(`block`);
                $('.content-new-element').css('display', 'block');
            } else if(params['filter_title']) {
                $(`tr.block_info > td:nth-child(2):contains(${params['filter_title'].replace('+', ' ')})`).parent().next('.hidden_info').css('display', 'table-row');
                $('html').animate({ scrollTop: $(`tr.block_info > td:nth-child(2):contains(${params['filter_title'].replace('+', ' ')})`).parent().next('.hidden_info').children().children().children('.block-plugin-wrapper').children().children('.plugin-wrapper').offset().top - 100 }, 1000);
            }
        };

    });
})(jQuery);

export {getWorklist};