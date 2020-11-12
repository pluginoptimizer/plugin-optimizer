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
            <h2 id="name_page">worklist</h2>
        </div>


        <div class="row col-12 justify-content-between wrap-tabs">
            <div class="col-10 row">
                <div class="tabs col-2">filters</div>
                <div class="tabs col-2">categories</div>
                <div class="tabs col-2">groups</div>
                <div class="tabs col-2">worklist</div>
            </div>
            <div class="col-2">
                <input class="search" type="search" id="search_elements" name="s" value="" placeholder="Search work">
            </div>
        </div>
        <div class="row sos-content">
            <div class="row col-12 justify-content-end">
                <div class="col-2 quantity">
                    <span id="all_elements">all</span> (<span id="count_all_elements"><?= wp_count_posts('sos_work')->publish; ?></span>) | <span id="trash_elements">TRASH</span> (<span id="count_trash_elements"><?= wp_count_posts('sos_work')->trash; ?></span>)
                </div>
            </div>
            <div class="row col-12">
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
