<?php
$posts = get_posts( array(
	'post_type'   => 'sos_work',
	'numberposts' => -1,
) );
?>

<div class="sos-wrap container">
    <div class="row">
        <div class="col-12">
            <h1>Plugin Optimizer</h1>
        </div>
        <div class="col-12">
            <h2>worklist</h2>
        </div>


        <div class="row col-12 justify-content-between wrap-tabs">
            <div class="col-10 row">
                <div class="tabs col-2">filters</div>
                <div class="tabs col-2">categories</div>
                <div class="tabs col-2">groups</div>
                <div class="tabs col-2">worklist</div>
            </div>
            <div class="col-2">
                <input class="search" type="search" id="search_works" name="s" value="" placeholder="Search work">
            </div>
        </div>
        <div class="row sos-content">
            <div class="row col-12 justify-content-end">
                <div class="col-2 quantity">
                    <span id="all_works">all</span> (<span id="count_all_works"><?= wp_count_posts('sos_work')->publish; ?></span>) | <span id="trash_works">TRASH</span> (<span id="count_trash_works"><?= wp_count_posts('sos_work')->trash; ?></span>)
                </div>
            </div>
            <div class="row col-12">
                <div class="col-3">
                    <select id="check_works">
                        <option value="default">Bulk actions</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button id="btn_apply">Apply</button>
                </div>
                <div class="col-3">
                    <select id="filter_works">
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
                            <th>PAGE TITLE</th>
                            <th>permalink</th>
                            <th>Date Created</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="the-list">
                        <?php
                            $this->content_works($posts);
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
