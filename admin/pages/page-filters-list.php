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
        <div class="row justify-content-between global-information">
        
            <div class="col-3">
                <a href="<?php echo admin_url('admin.php?page=plugin_optimizer_add_filters') ?>">
                    <button class="po_green_button" id="add_elements">Create Filter</button>
                </a>
            </div>
            
            <?php SOSPO_Admin_Helper::content_part__bulk_actions( $filters ); ?>
            
            <div class="col-3 quantity">
                <span id="all_elements" class="filtered">Published</span> (<span id="count_all_elements"><?php echo wp_count_posts( 'plgnoptmzr_filter' )->publish; ?></span>)
                |
                <span id="trash_elements">Trashed</span> (<span id="count_trash_elements"><?php echo wp_count_posts( 'plgnoptmzr_filter' )->trash; ?></span>)
            </div>
            
        </div>
        
        <div class="row col-12">
            <div class="col-12">
                <table class="po_table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="check_all"></th>
                            <th class="left-10 align-left">Title</th>
                            <th class="left-10 align-left">Categories</th>
                            <th>Triggers</th>
                            <th>Blocked plugins</th>
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
