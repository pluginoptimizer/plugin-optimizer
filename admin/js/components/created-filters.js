import {allElements} from "./check-all-element.js";
import {hiddenInfoFilter} from "./hidden-info-filter.js";
import {changePlugins} from "./change-plugins.js";

let createdFilters;
(function ($) {
    'use strict';

    $(document).ready(function () {
        //created filters
        createdFilters = () => {
            $('.save-filter').click(function () {
                let result = true;
                $(`.content-new-element input#set_title`).toArray().some(function (item) {
                    if ($(item).val().trim() === "" && result) {
                        $(item).focus();
                        return result = false;
                    }
                    /*if ($(item).val().trim() === "" && result && item.id !== `search_pages`) {
                        $(item).focus();
                        return result = false;
                    } else if ($(item).val().trim() === "" && result && item.id === `search_pages` && !$(`span`).is(`.text_link`)) {
                        $(item).focus();
                        return result = false;
                    } else if ($(item).val().trim() !== "" && item.id === `search_pages` && !$(`span`).is(`.text_link`)) {
                        $(item).focus();
                        return result = false;
                    }*/
                })
                if (!result) {
                    return false;
                }
                if (!$(`.block-plugin-wrapper .content`).hasClass(`block`)) {
                    $(`.block-plugin-wrapper .content`).toArray().map(item => $(item).css('box-shadow', 'rgba(255, 255, 255, 0.2) 0px 0px 0px 1px inset, rgba(0, 0, 0, 0.9) 0px 0px 0px 1px'))
                    return false;
                } else if (!$(`.category-wrapper .content`).hasClass(`block`)) {
                    $(`.category-wrapper .content`).toArray().map(item => $(item).css('box-shadow', 'rgba(255, 255, 255, 0.2) 0px 0px 0px 1px inset, rgba(0, 0, 0, 0.9) 0px 0px 0px 1px'))
                    return false;
                } else {
                    $.ajax({
                        url: plugin_optimizer_groups.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'sos_add_plugin_to_filter',
                            'block_plugins': $('.block-plugin-wrapper .block span').toArray().map(item => $(item).text()).join(', '),
                            'block_value_plugins': $('.block-plugin-wrapper .block span').toArray().map(item => $(item).attr('value')).join(', '),
                            'block_group_plugins': $(`.block-group-plugin-wrapper .block>span`).toArray().map(item => $(item).text()).join(', ') ? $(`.block-group-plugin-wrapper .block>span`).toArray().map(item => $(item).text()).join(', ') : `None`,
                            'pages': $(`.content-permalinks .link span.text_link`).toArray().map(item => $(item).text()).join(', '),
                            'title_filter': $('input#set_title').val(),
                            'type_filter': $('#set_type').val(),
                            'category_filter': $('.category-wrapper .block span').toArray().map(item => $(item).text()).join(', '),
                            'category_id_filter': $('.category-wrapper .block span').toArray().map(item => $(item).attr('value')).join(', '),
                        },
                        success: function (response) {
                            $('#the-list').html(response.data);
                            // $('.content-new-element').css('display', 'none');
                            allElements.count_element('sos_filter');
                            allElements.check_all_element();
                            hiddenInfoFilter();
                            changePlugins();

                            if($(`.content-new-element *`).is(`.block`)){
                                $(`.content-new-element *`).removeClass(`block`);
                            }

                            $(`#set_title`).val(``);
                            $(`#search_pages`).val(``);
                            $(`.link`).remove();
                            $('.change_content_data option:first').prop('selected', true);
                            $('#set_type option:first').prop('selected', true);
                        }
                    })
                }
            });
        }
    });
})(jQuery);

export {createdFilters};