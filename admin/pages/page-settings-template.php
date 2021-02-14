<div class="wrap">

	<?php
	$posts              = get_posts( array(
		'post_type'   => 'sos_work',
		'numberposts' => - 1,
	) );
	$all_plugins        = Plugin_Optimizer_Helper::get_plugins_with_status();
	$activate_plugins   = array();
	$deactivate_plugins = array();
	foreach ( $all_plugins as $plugin ) {
		foreach ( $plugin as $key => $value ) {
			if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
				if ( $value ) {
					array_push( $activate_plugins, $plugin['name'] );
				} else {
					array_push( $deactivate_plugins, $plugin['name'] );
				}
			}
		}
	}
	?>

    <div class="sos-wrap container">
            <div class="row col-12">
                <h1>Plugin Optimizer</h1>
            </div>
            <div class="row col-12">
                <h2 id="name_page" class="settings">settings</h2>
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
                    <input class="search" type="search" id="search_elements" name="s" value="" placeholder="Search">
                </div>
            </div>
            <div class="row sos-content">
                <div class="row col-12 justify-content-end">
                    <div class="col-6 quantity">
                        <span id="show_settings_general">General</span> | <span
                                id="show_settings_plugins">Plugin Search</span>
                        | <span id="show_settings_debug">Debug SOS</span>
                    </div>
                </div>
                <div id="settings_general" class="row col-12">
                    <div class="col-1">
                        <!-- Rounded switch -->
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="col-11">
                        <span>Display Debug Messages</span>
                    </div>
                </div>
                <div id="settings_plugins" class="col-12">
                    <div class="row col-12 justify-content-end">
                        <div class="col-8 quantity">
                            <span class="active-plugin" id="activate_plugins">Active</span> (<span
                                    class="count-active-plugin"><?= count( $activate_plugins ); ?>)</span> | <span
                                    class="inactive-plugin" id="deactivate_plugins">Inactive</span> (<span
                                    class="count-trash-plugin"><?= count( $deactivate_plugins ); ?></span>)
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
                                    <th>Name Plugin</th>
                                </tr>
                                </thead>
                                <tbody id="the-list">
								<?php
								Plugin_Optimizer_Helper::content_activate_plugins_to_settings( $activate_plugins );
								?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div id="settings_debug" class="row col-12">
                    <div class="col-1">
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="col-11">
                        <span>Debug: SOS will need to provide additional details on this item</span>
                    </div>
                </div>
            </div>
    </div>

</div>
