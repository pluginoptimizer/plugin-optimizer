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
            }
        };

    });
})(jQuery);

export {getWorklist};