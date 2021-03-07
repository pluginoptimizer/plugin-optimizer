<?php
$groups = get_posts( array(
	'post_type'   => 'sos_group',
	'post_status' => [ 'publish', 'trash' ],
	'numberposts' => - 1,
) );
?>

<div class="sos-wrap">

    <?php PO_Admin_Helper::content_part__header("Groups", "groups"); ?>
    
    <div class="sos-content">
        <div class="row justify-content-between global-information">
        
            <div class="col-3">
                <a href="/wp-admin/admin.php?page=plugin_optimizer_add_groups">
                    <button class="po_green_button" id="add_elements">Add New Group</button>
                </a>
            </div>
            
            <?php PO_Admin_Helper::content_part__bulk_actions( $groups ); ?>
            
            <div class="col-3 quantity">
                <span id="all_elements" class="filtered">Published</span> (<span id="count_all_elements"><?= wp_count_posts( 'sos_group' )->publish; ?></span>)
                |
                <span id="trash_elements">Trashed</span> (<span id="count_trash_elements"><?= wp_count_posts( 'sos_group' )->trash; ?></span>)
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
                        <?php PO_Admin_Helper::list_content__groups( $groups ); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
