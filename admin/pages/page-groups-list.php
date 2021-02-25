<?php
$groups = get_posts( array(
	'post_type'   => 'sos_group',
	'post_status' => [ 'publish', 'trash' ],
	'numberposts' => - 1,
) );
?>
<div class="wrap wrapper-filter">

    <div class="sos-wrap container">
    
        <?php PO_Admin_Helper::content_part__header("Groups", "groups"); ?>
        
        <div class="row sos-content">
            <div class="row col-12 justify-content-between global-information">
            
                <div class="col-3">
                    <button class="po_green_button" id="add_elements"><span class="pluse">+</span> Add New Group</button>
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
                    <table>
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check_all"></th>
                                <th class="left-10 align-left">Title</th>
                                <th>Plugins</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody id="the-list">
                            <?php PO_Admin_Helper::list_content__groups( $groups ); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
