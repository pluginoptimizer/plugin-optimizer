import { getWorklist } from './worklist.js';
import { trashElements } from './trash-elements.js';
import { searchPages } from './search-pages.js';
import { searchFilters } from './search-filters.js';
import { searchElements } from './search-elements.js';
import { hiddenInfoFilter } from './hidden-info-filter.js';
import { deleteRestoreElement } from './delete-restore-element.js';
import { allElements } from './check-all-element.js';
import { createdFilters } from './created-filters.js';
import { createGroupPlugins } from './create-groups.js';
import { showAllElements } from './all-elements.js';
import { addCategory } from './add-category.js';
import { deleteCategory } from './delete-category.js';
import { changePlugins } from './change-plugins.js';
import { changeSettings } from './change-settings.js';
import { checkNameElements } from './check-name-elements.js';
import { createCat } from './create-category.js';
import { createPopup } from './create-popup.js';
import { selectParentCategory } from './select-parent-category.js';
import { selectParentGroup } from './select-parent-group.js';
import { selectAllPlugins } from './select-all-plugins.js';
import { choicePlugins } from './choice-plugin.js';
import { choiceCategories } from './choice-category.js';
import { choiceLinks } from './choice-link.js';
import { addCategoryFilter } from './add-category-filter.js';
import { changePluginsGroup } from './change-plugins-group.js';
import { showHiddenOverview } from './show-hidden-overview.js';
import { changeFilterToCategory } from './change-filter-to-category.js';
import { showPluginsSettings } from './show-plugins-to-settings.js';

let transitionElements;
(function ($) {
    'use strict';

    $(document).ready(function () {

        transitionElements = () => {



            transitionElements = () => {
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
                            $(`#${selfId}`).css(`background-color`, `#d7b70a`);
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
                        }
                    });
                })
            }

            transitionElements();

        }
    });
})(jQuery);

export {transitionElements};