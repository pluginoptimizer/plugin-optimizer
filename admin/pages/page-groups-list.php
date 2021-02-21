<?php
$groups = get_posts( array(
	'post_type'   => 'sos_group',
	'numberposts' => - 1,
) );
?>
<div class="wrap wrapper-filter">

    <div class="sos-wrap container">
    
        <?php Plugin_Optimizer_Admin_Helper::content_part__header("Groups"); ?>
        
        <div class="row sos-content">
            <div class="row col-12 justify-content-between global-information">
                <div class="col-3">
                    <button class="add-filter" id="add_elements"><span class="pluse">+</span> Add New Group</button>
                </div>
                <div class="col-8 quantity">
                    <span id="all_elements">all</span> (<span
                            id="count_all_elements"><?= wp_count_posts( 'sos_group' )->publish; ?></span>) | <span
                            id="trash_elements">TRASH</span> (<span
                            id="count_trash_elements"><?= wp_count_posts( 'sos_group' )->trash; ?></span>)
                </div>
            </div>
            
            <?php Plugin_Optimizer_Admin_Helper::content_part__bulk_actions( $groups ); ?>
            
            <div class="row col-12">
                <div class="col-12">
                    <table>
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="check_all"></th>
                            <th>TITLE</th>
                            <th>Plugins</th>
                            <th>Count</th>
                        </tr>
                        </thead>
                        <tbody id="the-list">
						<?php Plugin_Optimizer_Helper::content_groups( $groups ); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
