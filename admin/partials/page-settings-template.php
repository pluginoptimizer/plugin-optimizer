<div class="wrap">
    <h1>General Settings</h1>
    <pre>
        <?php

//                print_r(Simple_Online_Systems_Helper::get_plugins_with_status());
//        echo count($deactivate_plugins);
//                print_r(get_plugins());
        //        echo plugins_url();

        ?>
    </pre>
</div>


<?php
$posts = get_posts( array(
	'post_type'   => 'sos_work',
	'numberposts' => -1,
) );
$all_plugins = Simple_Online_Systems_Helper::get_plugins_with_status();
$activate_plugins = array();
$deactivate_plugins = array();
foreach ($all_plugins as $plugin) {
	foreach ($plugin as $key => $value) {
		if($key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer'){
			if($value){
				array_push($activate_plugins, $plugin['name']);
			} else{
				array_push($deactivate_plugins, $plugin['name']);
			}
		}
	}
}
?>

<div class="sos-wrap container">
    <div class="row">
        <div class="col-12">
            <h1>Plugin Optimizer</h1>
        </div>
        <div class="col-12">
            <h2 id="name_page">settings</h2>
        </div>


        <div class="row col-12 justify-content-between wrap-tabs">
            <div class="col-10 row">
                <div class="tabs col-2">filters</div>
                <div class="tabs col-2">categories</div>
                <div class="tabs col-2">groups</div>
                <div class="tabs col-2">worklist</div>
            </div>
            <div class="col-2">
                <input class="search" type="search" id="search_elements" name="s" value="" placeholder="Search">
            </div>
        </div>
        <div class="row sos-content">
            <div class="row col-12 justify-content-end">
                <div class="col-6 quantity">
                    <span id="show_settings_general">General</span> | <span id="show_settings_plugins">Plugin Search</span> | <span id="show_settings_premium">Premium</span> | <span id="show_settings_debug">Debug SOS</span>
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
                    <span>Display Debug Messages Toggle</span>
                </div>
            </div>
            <div id="settings_plugins" class="col-12">
                <div class="row col-12 justify-content-end">
                    <div class="col-8 quantity">
                        <span class="active-plugin">Active</span> (<span class="count-active-plugin"><?= count($activate_plugins);?>)</span> | <span class="inactive-plugin">Inactive</span> (<span class="count-trash-plugin"><?= count($deactivate_plugins);?></span>)
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
							if($activate_plugins):
								?>
								<?php
								foreach ($activate_plugins as $activate_plugin):
									?>
                                    <tr class="block_info">
                                        <td><input type="checkbox"></td>
                                        <td><?= $activate_plugin; ?></td>
                                    </tr>
									<?php
									$posts = get_posts( array(
										'post_type'   => 'sos_filter',
										'numberposts' => -1,
									) );
									?>
                                    <tr class="hidden_info">
                                        <td colspan="6">
                                            <div class="content-filter">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="header">
                                                            <div class="title">
                                                                Filters
                                                            </div>
                                                            <span class="count-plugin">( All: <?= wp_count_posts('sos_filter')->publish;?>   |   Trash: <?= wp_count_posts('sos_filter')->trash; ?> )</span>
                                                        </div>
														<?php
														if($posts):
															?>
                                                            <div class="plugin-wrapper">
																<?php
																foreach ($posts as $post):
																	$group_plugins = implode( ', ', get_metadata( 'post', $post->ID, 'block_plugins' )) . ', ' . implode( ', ', get_metadata( 'post', $post->ID, 'block_group_plugins' ));
																	?>
                                                                <a href="<?= esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_filters&filter_title=' . urlencode( $post->post_title ))); ?>">
                                                                    <div class="content
                                             <?php
																	if(substr_count($group_plugins, $activate_plugin)){
																		echo 'block';
																	}
																	?>
                                             ">
                                                                        <span><?= $post->post_title; ?></span>
                                                                    </div>
                                                                </a>
																<?php
																endforeach;
																?>
                                                            </div>
														<?php
														else:
															?>
                                                            <div class="plugin-wrapper no-plugins">
                                                                <div class="content">
                                                                    <span>No activate plugins</span>
                                                                </div>
                                                            </div>
														<?php
														endif;
														?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
								<?php
								endforeach;
								?>
							<?php
							else:
								?>
                                <tr class="plugin-wrapper no-plugins">
                                    <div class="content">
                                        <span>No activate plugins for blocking</span>
                                    </div>
                                </tr>
							<?php
							endif;
							?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div id="settings_premium" class="row col-12">
                <div class="col-6">
                    <!-- Rounded switch -->
                    <input type="text" id="premium_key">
                </div>
                <div class="col-6">
                    <span>enter a purchase key to restore their premium features</span>
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
