import {deleteCategory} from "./delete-category.js";
import {addCategory} from "./add-category.js";

let addCategoryFilter;
(function ($) {
    'use strict';

    $(document).ready(function () {
        /*
        * Add or delete category that already exists for filters on filters page
        * @const      object self button with name category in categories block
        * */
        addCategoryFilter = () => {
            $('body').on('click', '.filter-category', function() {

                console.log( "add-category-filter.js" );
                
                const name_category = $(this).children('span:nth-child(1)').text();
                const category_filter = $('#category_filter');


                if(!$(this).hasClass('block')){
                    /* Change appearance */
                    $(this).addClass('block');

                    /* Record data of name category */
                    category_filter.val() ? category_filter.val(`${category_filter.val()}, ${name_category}`) : category_filter.val(name_category);

                } else {
                    /* Change appearance */
                    $(this).removeClass('block');
                    /* Delete data of name category */
                    category_filter.val(category_filter.val().split(', ').filter(item => item !== name_category).join(', '))
                }

                let self = this;
                
                console.log( "aAjax: add-category-filter.js" );
                
                $.ajax({
                    url: plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sos_add_category_to_filter',
                        /* ID category that have chosen */
                        'id_category': $(this).children('span.close').attr('id'),
                        /* ID filter for which add or delete a category */
                        'id_filter': $(this).parent().children('button').attr('id').substr(5),
                        /* Depending on whether the filter belongs to this category, we remove or add it */
                        'trigger': $(this).hasClass('block') ? 'delete' : 'add',
                        'page': $('#name_page').attr('class'),
                    },
                    success: function (response) {
                        /* Change the content of the block of categories */
                        // $(self).parent().html(response.data);
                        /* Added the ability to delete categories */
                        /*deleteCategory();
                        /!* Added the ability to add new categories *!/
                        addCategory();
                        /!* Added the ability to appropriation categories *!/
                        addCategoryFilter();*/
                    }
                });
            })
        }
    });
})(jQuery);

export {addCategoryFilter};