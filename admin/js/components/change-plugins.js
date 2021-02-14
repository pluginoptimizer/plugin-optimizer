let changePlugins;

(function ($) {
    'use strict';

    $(document).ready(function () {
        /*
        * Change plugins to filter
        * @const      text filter_id ID filter that changes the presence of plugins
        * @const      text plugin_name name plugin chose
        * @const      text plugin_link link plugin chose
        * @const      text change_plugins ('+' or '×') add or delete to filter
        * */
        changePlugins = () => {
            $('body').on('click', '.plugin-wrapper:not(.group-wrapper) > .content', function () {
                
                console.log( "change-plugins.js" );
                
                let   $close = $(this).find('span.close');
                
                if( $close.length < 1 ){
                    return;
                }
                
                const filter_id      = $close.attr('value');
                const plugin_name    = $close.attr('id');
                const plugin_link    = $close.attr('link');
                const change_plugins = $close.text();

                const block_plugins      = $('#block_plugins');
                const block_link_plugins = $('#block_link_plugins');

                if(change_plugins === '+'){
                    /* Change appearance */
                    $(this).addClass('block');
                    $close.text('×');
                    /* Record data of selected plugins */
                    block_plugins.val() ? block_plugins.val(`${block_plugins.val()}, ${plugin_name}`) : block_plugins.val(plugin_name);

                    /* Record data of selected link plugins */
                    block_link_plugins.val() ? block_link_plugins.val(`${block_link_plugins.val()}, ${plugin_link}`) : block_link_plugins.val(plugin_link);
                } else {
                    /* Change appearance */
                    $(this).removeClass('block');
                    $close.text('+');
                    /* Delete data of selected plugins */
                    block_plugins.val(block_plugins.val().split(', ').filter(item => item !== plugin_name).join(', '))

                    /* Delete data of selected plugins */
                    block_link_plugins.val(block_link_plugins.val().split(', ').filter(item => item !== plugin_link).join(', '))
                }

                /*$.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_change_plugins_to_filter',
                        'filter_id': filter_id,
                        'plugin_name': plugin_name,
                        'plugin_link': plugin_link,
                        'change_plugins': change_plugins,
                    },
                    success: function (response) {
                        /!* Change the content of the block plugins *!/
                        $(`tr#filter-${response.data.filter_id}`).next('.hidden_info').children().children().children('.content-plugins').html(response.data.return);
                        /!* Added the ability change plugins *!/
                        changePlugins();
                    }
                });*/
            })
        }
    });
})(jQuery);

export {changePlugins};