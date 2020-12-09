import { getWorklist } from './components/worklist.js';
import { trashElements } from './components/trash-elements.js';
import { searchPages } from './components/search-pages.js';
import { searchFilters } from './components/search-filters.js';
import { searchElements } from './components/search-elements.js';
import { hiddenInfoFilter } from './components/hidden-info-filter.js';
import { deleteRestoreElement } from './components/delete-restore-element.js';
import { allElements } from './components/check-all-element.js';
import { createdFilters } from './components/created-filters.js';
import { createGroupPlugins } from './components/create-groups.js';
import { showAllElements } from './components/all-elements.js';
import { addCategory } from './components/add-category.js';
import { deleteCategory } from './components/delete-category.js';
import { changePlugins } from './components/change-plugins.js';
import { changeSettings } from './components/change-settings.js';
import { checkNameGroup } from './components/check-name-group.js';
import { createCat } from './components/create-category.js';
import { createPopup } from './components/create-popup.js';


(function ($) {
    'use strict';

    $(document).ready(function () {

        const allFunction = () => {
            getWorklist();
            trashElements();
            searchPages();
            searchFilters();
            searchElements();
            hiddenInfoFilter();
            deleteRestoreElement();
            allElements.check_all_element();
            createdFilters();
            createGroupPlugins();
            showAllElements();
            addCategory();
            deleteCategory();
            changePlugins();
            changeSettings();
            checkNameGroup();
            createCat();
            createPopup();
        }

        allFunction();


        const addCategoryFilter = () => {
            $('.filter-category').click(function () {
                let self = this;
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_add_category_to_filter',
                        'id_category': $(this).children('span.close').attr('id'),
                        'id_filter': $(this).parent().children('button').attr('id').substr(5),
                    },
                    success: function (response) {
                        $(self).parent().html(response.data);
                        deleteCategory();
                        addCategory();
                        addCategoryFilter();
                    }
                });
            })
        }

        addCategoryFilter();



        const transitionElements = () => {
            $(`#window_filters, #window_categories, #window_groups, #window_worklist`).click(function(){
                const selfId = $(this).attr(`id`);
                $.ajax({
                    url: simple_online_systems_groups.ajax_url,
                    type: `POST`,
                    data: {
                        action: `sos_transition_viewed`,
                        selfId: selfId
                    },
                    success: function (response) {
                        $(`.wrap`).html(response.data);
                        allFunction();
                        transitionElements();
                    }
                });
            })
        }

        transitionElements();

        $(`.select_groups_to_filter`).click(function(){
            if($(this).text() !== 'None' && $(`.none_group`).hasClass('block')){
                $(`.none_group`).removeClass('block');
                $(`select[name="block_group_plugins"] option[value="none"]`).prop('selected', false);
            }
            if($(this).hasClass('block')){
                $(this).removeClass('block');
                $(`select[name="block_group_plugins"] option:contains(${$(this).text()})`).prop('selected', false);
                if($(this).text() !== 'None'){
                    let countItem = 0;
                    $( `.select_groups_to_filter` ).each(function( item ) {
                        if($(this).hasClass(`block`)){
                            countItem++;
                        }
                    });
                    if(countItem === 0){
                        $(`select[name="block_group_plugins"] option[value="none"]`).prop('selected', true);
                        $(`.none_group`).addClass(`block`);
                    }
                }
            } else {
                $(this).addClass('block');
                $(`select[name="block_group_plugins"] option:contains(${$(this).text()})`).prop('selected', true);
            }
        })



        $(`.select_plugins_to_filter`).click(function(){
            if($(this).hasClass('block')){
                $(this).removeClass('block');
                $(`select[name="block_plugins"] option:contains(${$(this).text()})`).prop('selected', false);
            } else {
                $(this).addClass('block');
                $(`select[name="block_plugins"] option:contains(${$(this).text()})`).prop('selected', true);
            }
        })

        $(`.select_post_to_filter`).click(function(){
            if($(this).hasClass('block')){
                $(this).removeClass('block');
                $(`select[name="post_type"] option:contains(${$(this).text()})`).prop('selected', false);
            } else {
                $(this).addClass('block');
                $(`select[name="post_type"] option:contains(${$(this).text()})`).prop('selected', true);
            }
        })

        $(`.select_category_to_filter`).click(function(){
            const selfText = $(this).text();
            if($(this).hasClass('block')){
                $(this).removeClass('block');
                $(`select[name="category_filter"] option:contains(${$(this).text()})`).prop('selected', false);
            } else {
                $( `.select_category_to_filter` ).each(function( item ) {
                    if($(this).hasClass(`block`) && selfText !== $(this).text()){
                        $(this).removeClass(`block`);
                    }
                });
                $(this).addClass('block');
                $(`select[name="category_filter"] option:contains(${$(this).text()})`).prop('selected', true);
            }
        })


        $(`.select_parent_to_category`).click(function(){
            const selfText = $(this).text();
            if($(this).hasClass('block')){
                $(this).removeClass('block');
                $(`.none_parent`).addClass('block');
            } else {
                $( `.select_parent_to_category` ).each(function( item ) {
                    if($(this).hasClass(`block`) && selfText !== $(this).text()){
                        $(this).removeClass(`block`);
                    }
                });
                $(this).addClass('block');
            }
        })

        $(`.select_parent_to_group`).click(function(){
            const selfText = $(this).text();
            if($(this).hasClass('block')){
                $(this).removeClass('block');
                $(`select[name="group_parents"] option:contains(${$(this).text()})`).prop('selected', false);
            } else {
                $( `.select_parent_to_group` ).each(function( item ) {
                    if($(this).hasClass(`block`) && selfText !== $(this).text()){
                        $(this).removeClass(`block`);
                    }
                });
                $(this).addClass('block');
                $(`select[name="group_parents"] option:contains(${$(this).text()})`).prop('selected', true);
            }
        })

        $(`.select_plugin_to_group`).click(function(){
            if($(this).hasClass('block')){
                $(this).removeClass('block');
                $(`select[name="group_plugins"] option:contains(${$(this).text()})`).prop('selected', false);
            } else {
                $(this).addClass('block');
                $(`select[name="group_plugins"] option:contains(${$(this).text()})`).prop('selected', true);
            }
        })


        /*     Show the selected links    */
        $(`.add-permalink`).click(function () {
            $(this).prev().before(`
            <div class="link">
                <span class="text_link">${$(`#search_pages`).val()}</span>
            </div>
            `);
            $('#search_pages').val('');
            $('#search_pages').focus();
        })



        /*$(`.content-new-filter .content`).click(function () {
            $(this).addClass(`block`);
        })*/

        /*  Select a category for a new filter   */
        $(`.category-wrapper .content`).click(function(){
            const selfText = $(this).text();
            if($(this).hasClass('block')){
                $(this).removeClass('block');
            } else {
                $( `.category-wrapper .content` ).each(function( item ) {
                    if($(this).hasClass(`block`) && selfText !== $(this).text()){
                        $(this).removeClass(`block`);
                    }
                });
                $(this).addClass('block');
            }
        })

        /*  Select a group plugins for a new filter   */
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

        /*  Select a plugins for a new filter   */
        $(`.block-plugin-wrapper .content`).click(function(){
            if($(this).hasClass('block')){
                $(this).removeClass('block');
            } else {
                $(this).addClass('block');
            }
        })



    });
})(jQuery);