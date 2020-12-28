let showHiddenOverview;
(function ($) {
    // 'use strict';

    $(document).ready(function () {
        //showHiddenOverview

        showHiddenOverview = () => {
            $(`.tab-overview`).click(function (){
                if($(this).next(`.hidden-info_overview`).css(`display`) !== `block`){
                    $(this).next(`.hidden-info_overview`).css(`display`, `block`);
                    $(this).children(`.trigger`).removeClass(`trigger_exit`);
                    $(this).children(`.trigger`).addClass(`trigger_open`);
                    $(this).children().children(`.info-passage`).addClass(`done`);
                } else{
                    $(this).children(`.trigger`).addClass(`trigger_exit`);
                    $(this).children(`.trigger`).removeClass(`trigger_open`);
                    $(this).next(`.hidden-info_overview`).css(`display`, `none`);
                }
            })
        };

    });
})(jQuery);

export {showHiddenOverview};