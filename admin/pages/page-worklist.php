<?php
$worklists = get_posts( array(
	'post_type'   => 'sos_work',
	'numberposts' => - 1,
) );
?>
<div class="wrap wrapper-filter">
	
    <div class="sos-wrap container">
    
        <?php PO_Admin_Helper::content_part__header("Worklist", "worklist"); ?>
        
        <div class="row sos-content">
            <div class="row col-12 justify-content-end">
                <div class="quantity">
                    <span id="all_elements">All</span> (<span
                          id="count_all_elements"><?= wp_count_posts( 'sos_work' )->publish; ?></span>) | <span
                          id="trash_elements">TRASH</span> (<span
                          id="count_trash_elements"><?= wp_count_posts( 'sos_work' )->trash; ?></span>)
                </div>
            </div>
            
            <?php PO_Admin_Helper::content_part__bulk_actions( $worklists ); ?>
            
            <div class="row col-12">
                <div class="col-12">
                    <table>
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="check_all"></th>
                            <th>PAGE TITLE</th>
                            <th>Permalink</th>
                            <th>Date Created</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="the-list">
						<?php PO_Helper::content_works( $worklists ); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>