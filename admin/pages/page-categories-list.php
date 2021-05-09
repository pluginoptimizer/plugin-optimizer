<?php
$categories = get_categories( [
	'taxonomy'   => 'plgnoptmzr_categories',
	'type'       => 'plgnoptmzr_filter',
	'parent'     => 0,
	'hide_empty' => 0,
] );

if( $categories ){
    
    usort( $categories, "SOSPO_Admin_Helper::sort__by_cat_name" );
}

?>

<div class="sos-wrap">

    <?php SOSPO_Admin_Helper::content_part__header("Filter categories", "categories"); ?>
    
    <div class="sos-content">
        <div class="justify-content-between global-information">
        
            <div class="col-9 left_information">
                <a href="<?php echo admin_url('admin.php?page=plugin_optimizer_add_categories') ?>">
                    <button class="po_green_button" id="add_elements">Create Category</button>
                </a>
                
                <?php SOSPO_Admin_Helper::content_part__bulk_actions( false, false ); ?>
                
                <?php SOSPO_Admin_Helper::content_part__manipulate_filter_options(); ?>
                
            </div>
            
            <div class="col-3 quantity">
                <span>All</span> (<span id="count_all_elements"><?php echo wp_count_terms( 'plgnoptmzr_categories' ); ?></span>)
            </div>
        </div>
        
        <div id="filter_options" class="toggle_filter_options" style="padding: 0;"></div>
        <script>jQuery('#filter_options').hide();</script>
        
        <div class="row col-12">
            <div class="col-12">
                <table id="categories_table" class="po_table">
                    <thead>
                    
                    <?php if( sospo_mu_plugin()->has_agent ){ ?>
                    <?php } ?>
                        <tr id="search_boxes" class="toggle_filter_options hidden">
                            <th data-label="checkbox"></th>
                            <th class="cat_edit"></th>
                            <th data-label="title" class="align-left"><input type="text" placeholder="Search Title..." class="search_filter"/></th>
                            <th data-label="description" class="align-left"><input type="text" placeholder="Search Description..." class="search_filter"/></th>
                            <th></th>
                        </tr>
                    
                        <tr id="po_table_header">
                            <th data-label="checkbox" class="cat_checkbox"><input type="checkbox" id="check_all"></th>
                            <th class="cat_edit"></th>
                            <th data-label="title" class="cat_title sort_able sort_active">Title</th>
                            <th data-label="description" class="cat_description">Description</th>
                            <th data-label="count" class="cat_filters sort_able">Filters</th>
                        </tr>
                        
                    </thead>
                    <tbody id="the-list" class="filter_on__status_publish">
                        <?php SOSPO_Admin_Helper::list_content__categories( $categories ); ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
