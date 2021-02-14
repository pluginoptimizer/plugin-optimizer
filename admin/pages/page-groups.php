<?php
$posts = get_posts( array(
	'post_type'   => 'sos_group',
	'numberposts' => - 1,
) );
?>
<div class="wrap wrapper-filter">


    <div class="sos-wrap container">
            <div class="row col-12">
                <h1>Plugin Optimizer</h1>
            </div>
            <div class="row col-12">
                <h2 id="name_page" class="groups">groups</h2>
            </div>


            <div class="row col-12 justify-content-between wrap-tabs">
                <div class="col-10 row">
                    <div id="window_filters" class="tabs col-2">filters</div>
                    <div id="window_categories" class="tabs col-2">categories</div>
                    <div id="window_groups" class="tabs col-2">groups</div>
                    <div id="window_worklist" class="tabs col-2">worklist</div>
                    <div id="window_settings" class="tabs col-2">settings</div>
                </div>
                <div class="row col-2">
                    <input class="search" type="search" id="search_elements" name="s" value=""
                           placeholder="Search groups">
                </div>
            </div>
            <div class="row sos-content">
                <div class="row col-12 justify-content-between global-information">
                    <div class="col-3">
                        <button class="add-filter" id="add_elements"><span class="pluse">+</span> add new group</button>
                    </div>
                    <div class="col-8 quantity">
                        <span id="all_elements">all</span> (<span
                                id="count_all_elements"><?= wp_count_posts( 'sos_group' )->publish; ?></span>) | <span
                                id="trash_elements">TRASH</span> (<span
                                id="count_trash_elements"><?= wp_count_posts( 'sos_group' )->trash; ?></span>)
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
                                <th>Plugins</th>
                                <th>Count</th>
                            </tr>
                            </thead>
                            <tbody id="the-list">
							<?php
							Plugin_Optimizer_Helper::content_groups( $posts );
							?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>