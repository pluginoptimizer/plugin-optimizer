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


    });
})(jQuery);