let choiceLinks;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*     Show the selected links    */
        choiceLinks = () => {



            $(`.add-permalink`).click(function () {
                $(this).prev().before(`
            <div class="link">
                <span class="text_link">${$(`#search_pages`).val()}</span>
            </div>
            `);
                $('#search_pages').val('');
                $('#search_pages').focus();
            })

        }
    });
})(jQuery);

export {choiceLinks};