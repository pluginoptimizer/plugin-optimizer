<?php
$groups = get_posts( array(
	'post_type'   => 'sos_group',
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
        <div class="row justify-content-between global-information">
        
            <div class="col-3">
                <a href="<?php echo admin_url('admin.php?page=plugin_optimizer_add_groups') ?>">
                    <button class="po_green_button" id="add_elements">Create Group</button>
                </a>
            </div>
            
            <?php SOSPO_Admin_Helper::content_part__bulk_actions( $groups ); ?>
            
            <div class="col-3 quantity">
                <span id="all_elements" class="filtered">Published</span> (<span id="count_all_elements"><?php echo wp_count_posts( 'sos_group' )->publish; ?></span>)
                |
                <span id="trash_elements">Trashed</span> (<span id="count_trash_elements"><?php echo wp_count_posts( 'sos_group' )->trash; ?></span>)
            </div>
            
        </div>
        
        <div class="row col-12">
            <div class="col-12">
                <table class="sos_table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="check_all"></th>
                            <th class="left-10 align-left">Title</th>
                            <th>Plugins</th>
                            <th>Count</th>
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
