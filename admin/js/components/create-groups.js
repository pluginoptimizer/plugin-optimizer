import {allElements} from "./check-all-element.js";
import {hiddenInfoFilter} from "./hidden-info-filter.js";
import {selectParentGroup} from './select-parent-group.js';


let createGroupPlugins;
(function ($) {
    'use strict';

    $(document).ready(function () {
        // create plugins
        createGroupPlugins = () => {
            $('.save-group').click(function () {
                let rv = true;
                $(`.content-new-element input`).toArray().some(function (item) {
                    if ($(item).val().trim() === "" && rv) {
                        $(item).focus();
                        return rv = false;
                    }
                })
                if (!rv) {
                    return false;
                }
                if (!$(`.block-plugin-wrapper .content`).hasClass(`block`)) {
                    $(`.block-plugin-wrapper .content`).toArray().map(item => $(item).css('box-shadow', 'rgba(255, 255, 255, 0.2) 0px 0px 0px 1px inset, rgba(0, 0, 0, 0.9) 0px 0px 0px 1px'))
                    return false;
                } else {
                    $.ajax({
                        url: plugin_optimizer_groups.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'sos_add_group_plugins',
                            'title_group': $('#set_title').val(),
                            'type_group': $('#set_type').val(),
                            'group_parents': $(`.block-group-plugin-wrapper .block span`).toArray().map(item => $(item).text()).join(', '),
                            'group_plugins': $(`.block-plugin-wrapper .block span`).toArray().map(item => $(item).text()).join(', '),
                        },
                        success: function (response) {
                            $('#the-list').html(response.data);
                            $('.content-new-element').css('display', 'none');
                            $(`#set_title`).val(``);
                            $(`#set_type`).val(``);
                            $(`.block-plugin-wrapper .block`).toArray().map(item => $(item).removeClass(`block`));
                            allElements.count_element('sos_group');
                            allElements.check_all_element();
                            hiddenInfoFilter();
                            $.ajax({
                                url: plugin_optimizer_groups.ajax_url,
                                type: 'POST',
                                data: {
                                    action: 'sos_get_parent_group',
                                },
                                success: function ({data}) {
                                    $('.block-group-plugin-wrapper').children().html(data);
                                    selectParentGroup();
                                }
                            })
                        }
                    })
                }


            });
        }
    });
})(jQuery);

export {createGroupPlugins};