let choiceLinks;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*     Show the selected links    */
        choiceLinks = () => {

            function get_hostname(url) {
                let m = url.match(/^http:\/\/[^/]+/);

                if(m){
                    return m[0];
                }
                m = url.match(/^https:\/\/[^/]+/);
                return m ? m[0] : null;
            }

            $('body').on('keypress', '#search_pages', function(e){
                if (e.keyCode == 13) {
                    $('.add-permalink').click();
                }
            });

            $('.add-permalink').click(function () {
                let linkClient = $('#search_pages').val();
                linkClient = get_hostname(linkClient) ? linkClient.replace(get_hostname(linkClient), '') : linkClient;
                linkClient = linkClient.indexOf('/') === 0 ? linkClient.replace('/', '') : linkClient;
                linkClient = linkClient.includes(location.hostname) ? linkClient : `${location.hostname}/${linkClient}`;
                linkClient = linkClient.includes('https://') ? linkClient : `https://${linkClient}`;


                $(this).parent().after(`
            <div class="link">
                <span class="data-interaction text_link" contenteditable>${linkClient}</span>
                <span class="close-selected-link">×</span>
            </div>
            `);
                $('#search_pages').val('');
                $('#search_pages').focus();
                $('.close-selected-link').click(function (){
                    $(this).parent().remove();
                })
            })


        }
    });
})(jQuery);

export {choiceLinks};