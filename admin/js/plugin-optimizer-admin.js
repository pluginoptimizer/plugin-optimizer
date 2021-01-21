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
import { selectAllPlugins } from './components/select-all-plugins.js';
import { choicePlugins } from './components/choice-plugin.js';
import { choiceCategories } from './components/choice-category.js';
import { choiceLinks } from './components/choice-link.js';
import { addCategoryFilter } from './components/add-category-filter.js';
import { changePluginsGroup } from './components/change-plugins-group.js';
import { showHiddenOverview } from './components/show-hidden-overview.js';
import { changeFilterToCategory } from './components/change-filter-to-category.js';
import { showPluginsSettings } from './components/show-plugins-to-settings.js';
import { transitionElements } from './components/transition-elements.js';
import { changePermalink } from './components/change-permalink.js';
import { changeType } from './components/change-type.js';
import { changeGroups } from './components/change-groups.js';
import { changeDataCategory } from './components/change-data-categories.js';

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
        changePlugins();
        changeSettings();
        checkNameElements();
        createCat();
        createPopup();
        selectParentCategory();
        selectParentGroup();
        selectAllPlugins();
        choicePlugins();
        choiceCategories();
        choiceLinks();
        transitionElements();
        addCategoryFilter();
        changePluginsGroup();
        showHiddenOverview();
        changeFilterToCategory();
        showPluginsSettings();
        changePermalink();
        changeType();
        changeGroups();
        changeDataCategory();


        let namePage = $(`#name_page`).attr(`class`);
        namePage = namePage === `filters_categories` ? `categories` : namePage;
        $(`#window_${namePage}`).css(`background-color`, `#d7b70a`);


        $(`.change_content_data`).change(function (){
            if($(this).children(`option:selected`).val() === `type`){
                $(`.content-type`).css(`display`, `block`);
                $(`.content-permalinks`).css(`display`, `none`);
            } else {
                $(`.content-permalinks`).css(`display`, `block`);
                $(`.content-type`).css(`display`, `none`);
            }
        })





    });
})(jQuery);
