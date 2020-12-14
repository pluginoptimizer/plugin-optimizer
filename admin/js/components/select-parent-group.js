let selectParentGroup;
(function ($) {
    'use strict';

    $(document).ready(function () {


        /*  Select a group plugins for a new filter
        *   or select parent for a new group
        * */
        selectParentGroup = () => {
            $(`.block-group-plugin-wrapper .content`).click(function(){
                if($(this).text() !== 'None' && $(`.none_group`).hasClass('block')){
                    $(`.none_group`).removeClass('block');
                }
                if($(this).hasClass('block')){
                    $(this).removeClass('block');
                    if($(this).text() !== 'None'){
                        let countItem = 0;
                        $( `.block-group-plugin-wrapper .content` ).each(function( item ) {
                            if($(this).hasClass(`block`)){
                                countItem++;
                            }
                        });
                        if(countItem === 0){
                            $(`.none_group`).addClass(`block`);
                        }
                    }
                } else {
                    $(this).addClass('block');
                }
            })
        }
    });
})(jQuery);

export {selectParentGroup};