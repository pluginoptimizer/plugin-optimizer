<?php
$categories = get_categories( [
	'taxonomy'   => 'сategories_filters',
	'type'       => 'sos_filter',
	'parent'     => 0,
	'hide_empty' => 0,
] );

?>

<div class="sos-wrap">

    <?php PO_Admin_Helper::content_part__header("Filter categories", "categories"); ?>
    
    <div class="sos-content">
        <div class="row justify-content-between global-information">
        
            <div class="col-3">
                <a href="<?= admin_url('admin.php?page=plugin_optimizer_add_categories') ?>">
                    <button class="po_green_button" id="add_elements">Create Category</button>
                </a>
            </div>
            
            <div id="bulk_actions" class="col-6">
                <div>
                    <select id="check_all_elements">
                        <option value="default">Bulk actions</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button id="btn_apply" class="po_secondary_button">Apply</button>
                </div>
            </div>
        
            <div class="col-3 quantity">
                <span>All</span> (<span id="count_all_elements"><?= wp_count_terms( 'сategories_filters' ); ?></span>)
            </div>
        </div>
        
        <div class="row col-12">
            <div class="col-12">
                <table id="categories_table" class="sos_table">
                    <thead>
                        <tr>
                            <th class="cat_checkbox"><input type="checkbox" id="check_all"></th>
                            <th class="cat_edit"></th>
                            <th class="cat_title">Title</th>
                            <th class="cat_description">Description</th>
                            <th class="cat_filters">Filters</th>
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
