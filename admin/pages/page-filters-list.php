<?php
$filters = get_posts( array(
	'post_type'   => 'sos_filter',
	'post_status' => [ 'publish', 'trash' ],
	'numberposts' => - 1,
) );
?>

<div class="sos-wrap">

    <?php PO_Admin_Helper::content_part__header("Filters", "filters"); ?>
    
    <div class="sos-content">
        <div class="row justify-content-between global-information">
        
            <div class="col-3">
                <a href="/wp-admin/admin.php?page=plugin_optimizer_add_filters">
                    <button class="po_green_button" id="add_elements">Add New Filter</button>
                </a>
            </div>
            
            <?php PO_Admin_Helper::content_part__bulk_actions( $filters ); ?>
            
            <div class="col-3 quantity">
                <span id="all_elements" class="filtered">Published</span> (<span id="count_all_elements"><?= wp_count_posts( 'sos_filter' )->publish; ?></span>)
                |
                <span id="trash_elements">Trashed</span> (<span id="count_trash_elements"><?= wp_count_posts( 'sos_filter' )->trash; ?></span>)
            </div>
            
        </div>
        
        <div class="row col-12">
            <div class="col-12">
                <table class="sos_table">
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
                        <?php PO_Admin_Helper::list_content__filters( $filters ); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
