<?php
$posts = get_posts( array(
	'post_type'   => 'sos_group',
	'numberposts' => - 1,
	'meta_query'  => array(
		array(
			'key'   => 'group_parents',
			'value' => 'None'
		)
	),
) );
?>
<div class="wrap wrapper-filter">
    <div id="create_elements">
        <div class="wrapper_create-elements">
            <p class="popup-close">×</p>
            <form action="" class="created-groups">
                <p>Group Title</p>
                <div id="group_name_error">
                    <div class="wrapper_group_name_error">
                        <p class="popup-close">×</p>
                        <div id="result_search">This name is already in use</div>
                    </div>
                </div>
                <input type="text" placeholder="Enter filter title" name="title_group" id="title_group">
                <p>Set Type</p>
                <input type="text" placeholder="Enter type" name="type_group">
                <p>Select parent</p>
                <select name="group_parents">
                    <option value="none">None</option>
					<?php
					foreach ( $posts as $post ): ?>
                        <option value="<?= str_replace( ' ', "_", $post->post_title ); ?>"><?= $post->post_title; ?></option>
					<?php endforeach; ?>
                </select>
                <p>Select plugins</p>
                <select name="group_plugins" multiple>
					<?php
					$plugins = Simple_Online_Systems_Helper::get_plugins_with_status();
					foreach ( $plugins as $plugin => $value ): ?>
                        <option value="<?= str_replace( ' ', "_", $value['name'] ); ?>"><?= $value['name']; ?></option>
					<?php endforeach; ?>
                </select>
                <br><br>
                <input type="submit" value="Create new group">
            </form>
        </div>

    </div>


    <div class="sos-wrap container">
        <div class="row">
            <div class="col-12">
                <h1>Plugin Optimizer</h1>
            </div>
            <div class="col-12">
                <h2 id="name_page" class="groups">groups</h2>
            </div>


            <div class="row col-12 justify-content-between wrap-tabs">
                <div class="col-10 row">
                    <div id="window_filters" class="tabs col-2">filters</div>
                    <div id="window_categories" class="tabs col-2">categories</div>
                    <div id="window_groups" class="tabs col-2">groups</div>
                    <div id="window_worklist" class="tabs col-2">worklist</div>
                </div>
                <div class="col-2">
                    <input class="search" type="search" id="search_elements" name="s" value=""
                           placeholder="Search filters">
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
                                <th>type</th>
                                <th>Plugins</th>
                                <th>Count</th>
                            </tr>
                            </thead>
                            <tbody id="the-list">
							<?php
							$this->content_groups( $posts );
							?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



