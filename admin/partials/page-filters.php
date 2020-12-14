<div class="wrap wrapper-filter">

	<?php
	$posts = get_posts( array(
		'post_type'   => 'sos_filter',
		'numberposts' => - 1,
	) );
	?>

    <div class="sos-wrap container">
        <div class="row col-12">
            <h1>Plugin Optimizer</h1>
        </div>
        <div class="row col-12">
            <h2 id="name_page" class="filters">filters</h2>
        </div>


        <div class="row col-12 justify-content-between wrap-tabs">
            <div class="col-10 row">
                <div id="window_filters" class="tabs col-2">filters</div>
                <div id="window_categories" class="tabs col-2">categories</div>
                <div id="window_groups" class="tabs col-2">groups</div>
                <div id="window_worklist" class="tabs col-2">worklist</div>
            </div>
            <div class="row col-2">
                <input class="search" type="search" id="search_elements" name="s" value=""
                       placeholder="Search filters">
            </div>
        </div>
        <div class="row sos-content">
            <div class="row col-12 justify-content-between global-information">
                <div class="col-3">
                    <button class="add-filter" id="add_elements"><span class="pluse">+</span> add new filter
                    </button>
                </div>
                <div class="col-2 quantity">
                    <span id="all_elements">all</span> (<span
                            id="count_all_elements"><?= wp_count_posts( 'sos_filter' )->publish; ?></span>) | <span
                            id="trash_elements">TRASH</span> (<span
                            id="count_trash_elements"><?= wp_count_posts( 'sos_filter' )->trash; ?></span>)
                </div>
            </div>
            <div class="row content-new-element">
                <div class="col-12">
                    <table>
                        <tr>
                            <td colspan="6">
                                <div class="content-filter">
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="header">Title</div>
                                            <div>
                                                <div class="content">
                                                    <span><input class="content-text" id="set_title" type="text"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="header">Type</div>
                                            <div>
                                                <div class="content">
                                                    <span><input class="content-text" id="set_type" type="text"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="header">Permalinks</div>
                                            <div class="content-permalinks">
                                                <div class="set_link">
                                                    <input id="search_pages" type="text">
                                                    <div id="result">
                                                        <p class="popup-close">×</p>
                                                        <div id="result_search"></div>
                                                    </div>
                                                </div>
                                                <button class="add-filter add-permalink"><span class="pluse">+</span> Permalink
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row block-plugin-wrapper">
                                        <div class="col-12">
                                            <div class="header">
                                                <?php
                                                $all_plugins        = Plugin_Optimizer_Helper::get_plugins_with_status();
                                                $activate_plugins   = array();
                                                $deactivate_plugins = array();
                                                foreach ( $all_plugins as $plugin ) {
	                                                foreach ( $plugin as $key => $value ) {
		                                                if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
			                                                if ( $value ) {
				                                                $activate_plugins[ $plugin['name'] ] = $plugin['file'];
			                                                } else {
				                                                $deactivate_plugins[ $plugin['name'] ] = $plugin['file'];
			                                                }
		                                                }
	                                                }
                                                }
                                                ?>
                                                <div class="title">
                                                    Plugins  <span
                                                            class="disabled">- <?= count( $all_plugins ) - 1 ; ?></span>

                                                </div>
                                                <span class="all-check">All disable</span>
                                                <span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
                                            </div>
							                <?php
							                if ( $activate_plugins ):
								                ?>
                                                <div class="plugin-wrapper">
									                <?php
									                foreach ( $activate_plugins as $activate_plugin => $activate_plugin_link ):
										                ?>
                                                        <div class="content">
                                                            <span value="<?= $activate_plugin_link ?>"><?= $activate_plugin; ?></span>
                                                        </div>
									                <?php
									                endforeach;
									                foreach ( $deactivate_plugins as $deactivate_plugin => $deactivate_plugin_link ):
										                ?>
                                                        <div class="content deactivate-plugin">
                                                            <span value="<?= $deactivate_plugin_link ?>"><?= $deactivate_plugin; ?></span>
                                                        </div>
									                <?php
									                endforeach;
									                ?>
                                                </div>
							                <?php
							                else:
								                ?>
                                                <div class="plugin-wrapper no-plugins">
                                                    <div class="content">
                                                        <span>No activate plugins for blocking</span>
                                                    </div>
                                                </div>
							                <?php
							                endif;
							                ?>
                                        </div>
                                    </div>
                                    <div class="row block-group-plugin-wrapper">
                                        <div class="col-12">
                                            <div class="header">
                                                <div class="title">
									                <?php
									                $groups         = get_posts( array(
										                'post_type'   => 'sos_group',
										                'numberposts' => - 1,
									                ) );
									                ?>
                                                    groups <span
                                                            class="disabled">- <?= count( $groups ); ?></span>
                                                </div>
                                            </div>
                                            <div class="plugin-wrapper">
                                                <div class="content none_group block">
                                                    <span>None</span>
                                                </div>
								                <?php
								                if ( $groups ) :
									                foreach ( $groups as $group ) :
										                ?>
                                                        <div class="content">
                                                            <span><?= $group->post_title; ?></span>
                                                        </div>
									                <?php
									                endforeach;
								                endif;
								                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row category-wrapper">
                                        <div class="col-12">
                                            <div class="header">
                                                <div class="title">
                                                    categories
                                                </div>
                                            </div>
                                            <div class="plugin-wrapper">
								                <?php
								                $categories = get_categories( [
									                'taxonomy'   => 'сategories_filters',
									                'type'       => 'sos_filter',
									                'hide_empty' => 0,
								                ] );

								                if ( $categories ):
									                foreach ( $categories as $cat ):
										                ?>
                                                        <div class="content filter-category">
                                                            <span value="<?= $cat->cat_ID; ?>"><?= $cat->cat_name; ?></span>
                                                        </div>
									                <?php
									                endforeach;
								                endif;
								                ?>
                                                <input type="text" placeholder="Name category">
                                                <button class="add-filter add-permalink add-category" id="post-<?= $id_filter; ?>">
                                                    <span class="pluse">+</span> Category
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <button class="add-filter save save-filter" id="add_elements"><span class="pluse">+</span> save new filter
                                    </button>
                                </div>

                            </td>
                        </tr>
                    </table>
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
                            <th>type</th>
                            <th>permalinks</th>
                            <th>Block plugins</th>
                        </tr>
                        </thead>
                        <tbody id="the-list">
						<?php
						$this->content_filters( $posts );
						?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

