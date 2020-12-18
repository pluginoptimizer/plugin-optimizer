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
import { checkNameElements } from './components/check-name-elements.js';
import { createCat } from './components/create-category.js';
import { createPopup } from './components/create-popup.js';
import { selectParentCategory } from './components/select-parent-category.js';
import { selectParentGroup } from './components/select-parent-group.js';


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
            checkNameElements();
            createCat();
            createPopup();
            selectParentCategory();
            selectParentGroup();
        }

        allFunction();


        const addCategoryFilter = () => {
            $('.filter-category').click(function () {
                let self = this;
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_add_category_to_filter',
                        'id_category': $(this).children('span.close').attr('id'),
                        'id_filter': $(this).parent().children('button').attr('id').substr(5),
                        'trigger': $(this).hasClass(`block`) ? `delete` : `add`,
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
                    url: plugin_optimizer_groups.ajax_url,
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


        /*  Select a category for a new filter   */
        $(`.category-wrapper .content`).click(function(){
            if($(this).hasClass('block')){
                $(this).removeClass('block');
            } else {
                $(this).addClass('block');
            }
        })



        /*  Select a plugins for a new filter
        *   or select plugins for a new group
        * */
        $(`.block-plugin-wrapper .content`).click(function(){
            if($(this).hasClass('block')){
                $(this).removeClass('block');
            } else {
                $(this).addClass('block');
            }
        })

        /*  Select all plugins for a new filter
        * */
        $(`.all-check`).click(function(){

            if($(this).text() === `All disable`){
                $(this).text(`All enable`);
                $(`.block-plugin-wrapper .content`).addClass(`block`);
            } else {
                $(this).text(`All disable`);
                $(`.block-plugin-wrapper .content`).removeClass(`block`);
            }
        })



    });
})(jQuery);