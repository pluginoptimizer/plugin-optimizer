let searchFilters;
(function ($) {
    'use strict';

    $(document).ready(function () {
        //search filters
        searchFilters = ()=>{
            $('#search_filters').keyup(function () {
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_search_filters',
                        keyword: $('#search_filters').val()
                    },
                    success: function (response) {
                        $('#the-list').html(response.data);
                    }
                });
            });
        }
    });

})(jQuery);

export  {searchFilters};