let changeFilterToCategory;
(function ($) {
    'use strict';

    $(document).ready(function () {
        changeFilterToCategory = () => {
            $('body').on('click', '.wrapper_filter_to_category .content', function () {
                
                console.log( "change-filter-to-category.js" );
                
                let self = this;
                
                $.ajax({
                    url : plugin_optimizer_groups.ajax_url,
                    type: 'POST',
                    data: {
                        action          : 'sos_add_category_to_filter',
                        'id_category'   : $(this).attr('cat_id').substr(4),
                        'id_filter'     : $(this).attr('id'),
                        'trigger'       : $(this).hasClass('block') ? 'delete' : 'add',
                        'page'          : $('#name_page').attr('class'),
                    },
                    success: function (response) {
                        $(self).parent().parent().parent().html(response.data);
                        changeFilterToCategory();
                    }
                });
            })
        }
    });
})(jQuery);

export {changeFilterToCategory};