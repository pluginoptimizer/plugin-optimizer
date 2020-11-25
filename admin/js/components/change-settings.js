let changeSettings;

(function ($) {
    'use strict';

    $(document).ready(function () {
        //change plugins
        changeSettings = () => {
            function hidden_settings(){
                if($(`#settings_plugins`).css(`display`) === 'block'){
                    $(`#settings_plugins`).css(`display`, `none`);
                    $(`#show_settings_plugins`).css('font-weight', 400);
                }
                switch (`flex`) {
                    case $(`#settings_general`).css(`display`):
                        $(`#settings_general`).css(`display`, `none`);
                        $(`#show_settings_general`).css('font-weight', 400);
                        break;
                    case $(`#settings_premium`).css(`display`):
                        $(`#settings_premium`).css(`display`, `none`);
                        $(`#show_settings_premium`).css('font-weight', 400);
                        break;
                    case $(`#settings_debug`).css(`display`):
                        $(`#settings_debug`).css(`display`, `none`);
                        $(`#show_settings_debug`).css('font-weight', 400);
                        break;
                }
            }

            $(`#show_settings_general`).click(function(){
                $(this).css('font-weight', 600);
                hidden_settings();
                $(`#settings_general`).css(`display`, `flex`);
            })

            $(`#show_settings_plugins`).click(function(){
                $(this).css('font-weight', 600);
                hidden_settings();
                $(`#settings_plugins`).css(`display`, `block`);
            })

            $(`#show_settings_premium`).click(function(){
                $(this).css('font-weight', 600);
                hidden_settings();
                $(`#settings_premium`).css(`display`, `flex`);
            })

            $(`#show_settings_debug`).click(function(){
                $(this).css('font-weight', 600);
                hidden_settings();
                $(`#settings_debug`).css(`display`, `flex`);
            })
        }
    });
})(jQuery);

export {changeSettings};