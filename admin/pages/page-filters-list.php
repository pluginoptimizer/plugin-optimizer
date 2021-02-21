<?php
$posts = get_posts( array(
	'post_type'   => 'sos_filter',
	'numberposts' => - 1,
) );
?>
<div class="wrap wrapper-filter">

    <div class="sos-wrap container">
    
        <?php Plugin_Optimizer_Admin_Helper::content_part__header("Filters"); ?>
        
        <div class="row sos-content">
            <div class="row col-12 justify-content-between global-information">
                <div class="col-3">
                    <button class="add-filter" id="add_elements"><span class="pluse">+</span> Add New Filter
                    </button>
                </div>
                <div class="col-2 quantity">
                    <span id="all_elements">all</span> (<span
                            id="count_all_elements"><?= wp_count_posts( 'sos_filter' )->publish; ?></span>) | <span
                            id="trash_elements">TRASH</span> (<span
                            id="count_trash_elements"><?= wp_count_posts( 'sos_filter' )->trash; ?></span>)
                </div>
            </div>
            <div class="row col-12 ">
                <div class="col-3">
                    <select id="check_all_elements">
                        <option value="default">Bulk actions</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button id="btn_apply">Apply</button>
                </div>
                <div class="col-3">
                    <select id="filter_all_elements">
                        <option value="default">All dates</option>
                        <option value="delete">November</option>
                    </select>
                    <button id="btn_filter">Filter</button>
                </div>
            </div>
            <div class="row col-12">
                <div class="col-12">
                    <table>
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="check_all"></th>
                            <th>TITLE</th>
                            <th>cATEGORIES</th>
                            <th>Type</th>
                            <th>Permalinks</th>
                            <th>Blocked plugins</th>
                        </tr>
                        </thead>
                        <tbody id="the-list">
						<?php Plugin_Optimizer_Helper::content__filters( $posts ); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

