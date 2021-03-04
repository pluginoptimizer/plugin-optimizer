<?php
$categories = get_categories( [
	'taxonomy'   => 'сategories_filters',
	'type'       => 'sos_filter',
	'parent'     => 0,
	'hide_empty' => 0,
] );

?>
<div class="wrap wrapper-filter">

    <div class="sos-wrap container">
    
        <?php PO_Admin_Helper::content_part__header("Filter categories", "filters_categories"); ?>
        
        <div class="row sos-content">
            <div class="row col-12 justify-content-between global-information">
            
                <div class="col-3">
                    <button class="po_green_button" id="add_elements"><span class="pluse">+</span> Add new Category</button>
                </div>
                
                <?php PO_Admin_Helper::content_part__bulk_actions( $categories ); ?>
                
                <div class="col-3 quantity">
                    <span id="all_elements">all</span> (<span id="count_all_elements"><?= wp_count_terms( 'сategories_filters' ); ?></span>)
                </div>
            </div>
            
            <div class="row col-12">
                <div class="col-12">
                    <table>
                        <thead>
                            <tr id="categories_table_header">
                                <th class="cat_checkbox"><input type="checkbox" id="check_all"></th>
                                <th class="cat_edit"></th>
                                <th class="cat_title">TITLE</th>
                                <th class="cat_description">Description</th>
                            </tr>
                        </thead>
                        <tbody id="the-list" class="filter_on__status_publish">
                            <?php PO_Admin_Helper::list_content__categories( $categories ); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>




