let searchPages;
(function ($) {
    'use strict';

    $(document).ready(function () {


        // search pages
        searchPages = () => {
            $('.popup-close').click(function () {
                $('#result').css( 'display', 'none' )
            });

            $('#search_pages').on('input', function () {
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_search_pages',
                        keyword: $('#search_pages').val()
                    },
                    success: function(response) {
                        $('#result').css( 'display', 'block' );
                        $('#result_search').html( response.data );
                        if($('#search_pages').val() === '') $('#result').css( 'display', 'none' );

                        $('.link_search_page').click(function(e){
                            e.preventDefault();

                            let select_link = $('#search_pages').val();

                            if(select_link.includes('http')){
                                $('#search_pages').val(select_link + ', ' + $(this).attr('href'));
                                $('#search_pages').focus();
                            } else {
                                $('#search_pages').val($(this).attr('href'));
                            }

                        });
                    }
                });
            });
        }
    });
})(jQuery);

export {searchPages};