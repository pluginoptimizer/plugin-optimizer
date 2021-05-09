<?php
$groups = get_posts( array(
	'post_type'   => 'plgnoptmzr_group',
	'post_status' => [ 'publish', 'trash' ],
	'numberposts' => - 1,
) );

if( $groups ){
    
    usort( $groups, "SOSPO_Admin_Helper::sort__by_post_title" );
}

?>

<div class="sos-wrap">

    <?php SOSPO_Admin_Helper::content_part__header("Groups", "groups"); ?>
    
    <div class="sos-content">
        <div class="justify-content-between global-information">
        
            <div class="col-9 left_information">
                <a href="<?php echo admin_url('admin.php?page=plugin_optimizer_add_groups') ?>">
                    <button class="po_green_button" id="add_elements">Create Group</button>
                </a>
                
                <?php SOSPO_Admin_Helper::content_part__bulk_actions(); ?>
                
                <?php SOSPO_Admin_Helper::content_part__manipulate_filter_options(); ?>
                
            </div>
            
            <div class="col-3 quantity">
                <span id="all_elements" class="filtered">Published</span> (<span id="count_all_elements"><?php echo wp_count_posts( 'plgnoptmzr_group' )->publish; ?></span>)
                |
                <span id="trash_elements">Trashed</span> (<span id="count_trash_elements"><?php echo wp_count_posts( 'plgnoptmzr_group' )->trash; ?></span>)
            </div>
            
        </div>
        
        <?php SOSPO_Admin_Helper::content_part__filter_options( $groups ); ?>
        
        <div class="row col-12">
            <div class="col-12">
                <table class="po_table">
                    <thead>
                    
                    <?php if( sospo_mu_plugin()->has_agent ){ ?>
                    <?php } ?>
                        <tr id="search_boxes" class="toggle_filter_options hidden">
                            <th data-label="checkbox"></th>
                            <th data-label="title" class="left-10 align-left"><input type="text" placeholder="Search Title..." class="search_filter"/></th>
                            <th data-label="plugins"><input type="text" placeholder="Search Plugins..." class="search_filter"/></th>
                            <th></th>
                        </tr>
                    
                        <tr id="po_table_header">
                            <th data-label="checkbox"><input type="checkbox" id="check_all"></th>
                            <th data-label="title" class="left-10 align-left sort_able sort_active">Title</th>
                            <th data-label="plugins">Plugins</th>
                            <th data-label="count" class="sort_able">Count</th>
                        </tr>
                        
                    </thead>
                    <tbody id="the-list" class="filter_on__status_publish">
                        <?php SOSPO_Admin_Helper::list_content__groups( $groups ); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
