import { getWorklist } from './components/worklist.js';
import { trashElements } from './components/trash-elements.js';
import { searchPages } from './components/search-pages.js';
import { searchFilters } from './components/search-filters.js';
import { searchElements } from './components/search-elements.js';
import { hiddenInfoFilter } from './components/hidden-info-filter.js';
import { deleteRestoreElement } from './components/delete-restore-element.js';
import { allElements } from './components/check-all-element.js';
import { createdFilters } from './components/created-filters.js';
import { createGroupPlugins } from './components/create-plugins.js';
import { showAllElements } from './components/all-elements.js';
import { addCategory } from './components/add-category.js';
import { deleteCategory } from './components/delete-category.js';


(function ($) {
    'use strict';

    $(document).ready(function () {

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

        (function () {
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
        })();

    });
})(jQuery);