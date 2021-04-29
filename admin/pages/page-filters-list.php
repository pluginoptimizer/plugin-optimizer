<?php
$filters = get_posts( array(
	'post_type'   => 'plgnoptmzr_filter',
	'post_status' => [ 'publish', 'trash' ],
	'numberposts' => - 1,
) );

if( $filters ){
    
    usort( $filters, "SOSPO_Admin_Helper::sort__by_post_title" );
}

?>

<div class="sos-wrap">

    <?php SOSPO_Admin_Helper::content_part__header("Filters", "filters"); ?>
    
    <div class="sos-content">
        <div class="justify-content-between global-information">
        
            <div class="col-9 left_information">
                <a href="<?php echo admin_url('admin.php?page=plugin_optimizer_add_filters') ?>">
                    <button class="po_green_button" id="add_elements">Create Filter</button>
                </a>
                
                <?php SOSPO_Admin_Helper::content_part__bulk_actions( true ); ?>
                
                <?php SOSPO_Admin_Helper::content_part__manipulate_filter_options(); ?>
                
            </div>
            
            <div class="col-3 quantity">
                <span id="all_elements" class="filtered">Published</span> (<span id="count_all_elements"><?php echo wp_count_posts( 'plgnoptmzr_filter' )->publish; ?></span>)
                |
                <span id="trash_elements">Trashed</span> (<span id="count_trash_elements"><?php echo wp_count_posts( 'plgnoptmzr_filter' )->trash; ?></span>)
            </div>
            
        </div>
        
        <?php SOSPO_Admin_Helper::content_part__filter_options( $filters ); ?>
        
        <div class="row col-12">
            <div class="col-12">
                <table class="po_table">
                    <thead>
                    
                    <?php if( sospo_mu_plugin()->has_agent ){ ?>
                    <?php } ?>
                        <tr id="search_boxes" class="toggle_filter_options hidden">
                            <th></th>
                            <th data-label="title" class="align-left"><input type="text" placeholder="Search Title..." class="search_filter"/></th>
                            <th data-label="categories" class="align-left"><input type="text" placeholder="Search Categories..." class="search_filter"/></th>
                            <th data-label="triggers"><input type="text" placeholder="Search Triggers..." class="search_filter"/></th>
                            <th data-label="plugins_tooltip"><input type="text" placeholder="Search Plugins..." class="search_filter"/></th>
                            <th></th>
                            <th></th>
                            <th class="toggle_filter"></th>
                        </tr>
                    
                        <tr id="po_table_header">
                            <th><input type="checkbox" id="check_all"></th>
                            <th data-label="title" class="left-10 align-left sort_able sort_active">Title</th>
                            <th class="left-10 align-left">Categories</th>
                            <th>Triggers</th>
                            <th data-label="plugins_tooltip" class="sort_able">Blocked plugins</th>
                            <th data-label="created" class="sort_able">Created</th>
                            <th data-label="modified" class="sort_able">Modified</th>
                            <th class="toggle_filter">Turned On</th>
                        </tr>
                        
                    </thead>
                    <tbody id="the-list" class="filter_on__status_publish">
                        <?php SOSPO_Admin_Helper::list_content__filters( $filters ); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
