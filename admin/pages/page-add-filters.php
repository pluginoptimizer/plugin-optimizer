<?php
$posts = get_posts( array(
	'post_type'   => 'sos_filter',
	'numberposts' => - 1,
) );
?>
<div class="wrap wrapper-filter">

	<div class="sos-wrap container">
		<div class="row col-12">
			<h1>Plugin Optimizer</h1>
		</div>
		<div class="row col-12">
			<h2 id="name_page" class="add-filters">Create a new Filter</h2>
		</div>

		<div class="row col-12 justify-content-between wrap-tabs">
			<div class="col-10 row">
                <div id="window_filters"    class="tabs col-2">Filters</div>
                <div id="window_categories" class="tabs col-2">Categories</div>
                <div id="window_groups"     class="tabs col-2">Groups</div>
                <div id="window_worklist"   class="tabs col-2">Worklist</div>
                <div id="window_settings"   class="tabs col-2">Settings</div>
			</div>
			<div class="row col-2">
				<input class="search" type="search" id="search_elements" name="s" value="" placeholder="Search filters">
			</div>
		</div>
		<div class="row sos-content">

			<div class="row content-new-element">
				<div class="col-12">
					<table>
						<tr>
							<td colspan="6">
								<div class="content-filter">
									<div class="row">
                                    
										<div class="col-12">
											<div class="header">Title</div>
											<div>
												<div class="content enter-data">
													<span><input class="content-text" id="set_title" type="text"></span>
												</div>
											</div>
										</div>
                                        
									</div>
									<div class="row">
                                    
										<div class="col-3">
											<div class="header">Type</div>
											<div>
												<div class="content enter-data">
                                                    <span>
                                                        <select name="" id="set_type">
                                                        <option value="none">Choose type post</option>
                                                        <?php
                                                        $post_types         = get_post_types( [ 'publicly_queryable' => 1 ] );
                                                        $post_types['page'] = 'page';
                                                        // unset( $post_types[ 'attachment' ], $post_types[ 'sos_filter' ], $post_types[ 'sos_group' ], $post_types[ 'sos_work' ] );

                                                        foreach ( $post_types as $post_type ) {
	                                                        ?>
	                                                        <option value="<?= str_replace( ' ', "_", $post_type ); ?>"><?= $post_type; ?></option>
	                                                        <?php
                                                        }

                                                        ?>
                                                    </select>
                                                    </span>
												</div>
											</div>
										</div>
                                        
										<div class="col-9">
											<div class="header">Permalinks</div>
											<div class="content-permalinks">
												<div class="set_link">
													<input id="search_pages" type="text">
													<button class="add-filter add-permalink"><span
															class="pluse">+</span>
														Permalink
													</button>
												</div>
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
													Plugins <span
														class="disabled">- <?= count( $all_plugins ) - 1; ?></span>

												</div>
												<span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
												<span class="all-check">Disable All</span>
											</div>
											<?php
											if ( $activate_plugins ):
												?>
												<div class="header attribute-plugin">Active plugins</div>
												<div class="plugin-wrapper">
													<?php
													foreach ( $activate_plugins as $activate_plugin => $activate_plugin_link ):
														?>
														<div class="content">
															<span value="<?= $activate_plugin_link ?>"><?= $activate_plugin; ?></span>
														</div>
													<?php
													endforeach;
													?>
												</div>
												<div class="header attribute-plugin">Inactive plugins</div>
												<div class="plugin-wrapper">
													<?php
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
													$groups = get_posts( array(
														'post_type'   => 'sos_group',
														'numberposts' => - 1,
													) );
													?>
													groups <span
														class="disabled">- <?= count( $groups ); ?></span>
												</div>
												<span class="all-check">Disable All</span>
											</div>
											<div class="plugin-wrapper">
												<?php
												if ( $groups ) :
													foreach ( $groups as $group ) :
														?>
														<div class="content">
															<span><?= $group->post_title; ?></span>
															<?php $block_plugins_in_group = explode( ',', get_post_meta( $group->ID, 'group_plugins', true ) );
															foreach ( $block_plugins_in_group as $block_plugin_in_group ) :
																?>
																<div class="hidden_content content">
																	<span><?= $block_plugin_in_group; ?></span>
																</div>
															<?php
															endforeach;
															?>
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
														<div class="content">
															<span value="<?= $cat->cat_ID; ?>"><?= $cat->cat_name; ?></span>
														</div>
													<?php
													endforeach;
												endif;
												?>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<button class="add-filter save save-filter" id="add_elements"><span
											class="pluse">+</span> save new filter
									</button>
								</div>

							</td>
						</tr>
					</table>
				</div>


			</div>
		</div>
	</div>
</div>


