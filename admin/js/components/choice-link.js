let choiceLinks;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*     Show the selected links    */
        choiceLinks = () => {



            $(`.add-permalink`).click(function () {
                $(this).parent().after(`
            <div class="link">
                <span class="text_link">${$(`#search_pages`).val()}</span>
                <span class="close-selected-link">×</span>
            </div>
            `);
                $('#search_pages').val('');
                $('#search_pages').focus();
                $(`.close-selected-link`).click(function (){
                    $(this).parent().remove();
                })
            })


        }
    });
})(jQuery);

export {choiceLinks};