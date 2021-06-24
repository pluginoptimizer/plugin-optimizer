<?php
$filters = get_posts( array(
	'post_type'   => 'plgnoptmzr_filter',
	'post_status' => [ 'publish', 'trash' ],
	'numberposts' => - 1,
) );

if( $filters ){
    
    usort( $filters, "SOSPO_Admin_Helper::sort__by_post_title" );
}

// $relevant_filters = sospo_dictionary()->get_relevant_filters();
// $pending_filters  = sospo_dictionary()->get_pending_filters();
// $approved_filters = sospo_dictionary()->get_approved_filters();
// $test = sospo_dictionary()->retrieve();

// sospo_mu_plugin()->write_log( $relevant_filters, "page_filters_list-relevant_filters" );
// sospo_mu_plugin()->write_log( $pending_filters,  "page_filters_list-pending_filters" );
// sospo_mu_plugin()->write_log( $approved_filters, "page_filters_list-approved_filters" );
// sospo_mu_plugin()->write_log( $test, "page_filters_list-test" );

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
                
                <?php SOSPO_Admin_Helper::content_part__manipulate_toggle_columns(); ?>
                
            </div>
            
            <div class="col-3 quantity">
                <span id="all_elements" class="filtered">Published</span> (<span id="count_all_elements"><?php echo wp_count_posts( 'plgnoptmzr_filter' )->publish; ?></span>)
                |
                <span id="trash_elements">Trashed</span> (<span id="count_trash_elements"><?php echo wp_count_posts( 'plgnoptmzr_filter' )->trash; ?></span>)
            </div>
            
        </div>
        
        <?php SOSPO_Admin_Helper::content_part__filter_options( $filters ); ?>
        
        <?php SOSPO_Admin_Helper::content_part__toggle_columns_options(); ?>

        <div>
            <table class="po_table">
                <thead>
                
                    <tr id="search_boxes" class="toggle_filter_options hidden">
                        <th data-label="checkbox"></th>
                    <?php if( sospo_mu_plugin()->has_agent ){ ?>
                        <th data-label="status"></th>
                    <?php } ?>
                        <th data-label="title" class="align-left"><input type="text" placeholder="Search Title..." class="search_filter"/></th>
                        <th data-label="categories" class="align-left"><input type="text" placeholder="Search Categories..." class="search_filter"/></th>
                        <th data-label="triggers"><input type="text" placeholder="Search Triggers..." class="search_filter"/></th>
                    <?php if( sospo_mu_plugin()->has_agent ){ ?>
                        <th data-label="belongs_to"><input type="text" placeholder="Search Belongs To..." class="search_filter"/></th>
                    <?php } ?>
                        <th data-label="plugins_tooltip"><input type="text" placeholder="Search Plugins..." class="search_filter"/></th>
                    <?php if( sospo_mu_plugin()->has_agent ){ ?>
                        <th data-label="created"></th>
                        <th data-label="modified"></th>
                    <?php } ?>
                        <th class="toggle_filter"></th>
                    </tr>
                
                    <tr id="po_table_header">
                        <th data-label="checkbox"><input type="checkbox" id="check_all"></th>
                    <?php if( sospo_mu_plugin()->has_agent ){ ?>
                        <th data-label="status">Status</th>
                    <?php } ?>
                        <th data-label="title" class="left-10 align-left sort_able sort_active">Title</th>
                        <th data-label="categories"class="left-10 align-left">Categories</th>
                        <th data-label="triggers">Triggers</th>
                    <?php if( sospo_mu_plugin()->has_agent ){ ?>
                        <th data-label="belongs_to">Belongs to</th>
                    <?php } ?>
                        <th data-label="plugins_tooltip" class="sort_able">Blocking</th>
                    <?php if( sospo_mu_plugin()->has_agent ){ ?>
                        <th data-label="created" class="sort_able">Created</th>
                        <th data-label="modified" class="sort_able">Modified</th>
                    <?php } ?>
                        <th class="toggle_filter">Turned On</th>
                    </tr>
                    
                </thead>
                <tbody id="the-list" class="filter_on__status_publish">
                    <?php SOSPO_Admin_Helper::list_content__filters( $filters ); ?>
                </tbody>
                <script>
                    jQuery('table.po_table [data-label]:not([data-label="checkbox"]):not([data-label="title"])').hide();
                </script>
            </table>
        </div>
    </div>
</div>
