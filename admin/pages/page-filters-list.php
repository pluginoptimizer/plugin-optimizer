<?php
$filters = get_posts( array(
	'post_type'   => 'sos_filter',
	'numberposts' => - 1,
) );
?>
<div class="wrap wrapper-filter">

    <div class="sos-wrap container">
    
        <?php PO_Admin_Helper::content_part__header("Filters", "filters"); ?>
        
        <div class="row sos-content">
            <div class="row col-12 justify-content-between global-information">
                <div class="col-3">
                    <button class="po_green_button" id="add_elements"><span class="pluse">+</span> Add New Filter</button>
                </div>
                <div class="col-2 quantity">
                    <span id="all_elements">all</span> (<span
                            id="count_all_elements"><?= wp_count_posts( 'sos_filter' )->publish; ?></span>) | <span
                            id="trash_elements">TRASH</span> (<span
                            id="count_trash_elements"><?= wp_count_posts( 'sos_filter' )->trash; ?></span>)
                </div>
            </div>
            
            <?php PO_Admin_Helper::content_part__bulk_actions( $filters ); ?>
            
            <div class="row col-12">
                <div class="col-12">
                    <table>
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="check_all"></th>
                            <th class="data-title">Title</th>
                            <th class="data-categories">Categories</th>
                            <th>Triggers</th>
                            <th>Blocked plugins</th>
                        </tr>
                        </thead>
                        <tbody id="the-list">
						<?php PO_Helper::content__filters( $filters ); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

