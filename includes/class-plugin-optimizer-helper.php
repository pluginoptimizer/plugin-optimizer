<?php

/**
 * Class Plugin_Optimizer_Helper
 */
class Plugin_Optimizer_Helper {

	public static function get_plugins_with_status() {
        
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		
		$plugins = [];
        
        if( empty( po_mu_plugin()->all_plugins ) ){
            
            $all_plugins    = get_plugins();
            $active_plugins = get_option( 'active_plugins' );

        } else {

            $all_plugins    = po_mu_plugin()->all_plugins;
            $active_plugins = po_mu_plugin()->original_active_plugins;
            
        }
        
        // po_mu_plugin()->write_log( $active_plugins, "get_plugins_with_status-active_plugins" );
        // po_mu_plugin()->write_log( $all_plugins,    "get_plugins_with_status-all_plugins" );

		foreach ( $active_plugins as $active_plugin_file ) {
			$plugins[] = [
				'name'      => $all_plugins[ $active_plugin_file ][ 'Name' ],
				'file'      => $active_plugin_file,
				'is_active' => 1,
			];
			unset( $all_plugins[ $active_plugin_file ] );
		}

		foreach ( $all_plugins as $file => $plugin_data ) {
			$plugins[] = [
				'name'      => $plugin_data[ 'Name' ],
				'file'      => $file,
				'is_active' => 0,
			];
		}
        
		return $plugins;

	}

	public static function create_category( $post ) {
        
		if ( $post && ! is_numeric( $post ) ) {
			$id_filter = $post->ID;
		} elseif ( is_numeric( $post ) ) {
			$id_filter = $post;
		} else {
			$id_filter     = htmlspecialchars( $_POST['id_filter'] );
			$name_category = htmlspecialchars( $_POST['name_category'] );

			wp_set_object_terms( $id_filter, $name_category, '?ategories_filters' );
		}


		ob_start();

		$categories = get_categories( [
			'taxonomy'   => '?ategories_filters',
			'type'       => 'sos_filter',
			'hide_empty' => 0,
		] );

		if ( $categories ){
			foreach ( $categories as $cat ){
				?>
                <div class="content filter-category <?= ( has_term( $cat->cat_name, '?ategories_filters', $id_filter ) ) ? 'block' : ''; ?>">
                    <span><?= $cat->cat_name; ?></span>
                    <span class="close" id="<?= $cat->cat_ID; ?>">×</span>
                </div>
			<?php
			}
		}
        
		?><input type="text" placeholder="Name category"><?php
        ?><button class="add-filter add-permalink add-category" id="post-<?= $id_filter; ?>">
            <span class="pluse">+</span> Category
        </button><?php
        
		if ( $post && ! is_numeric( $post ) ) {
			echo ob_get_clean();
		} else {
			wp_send_json_success( ob_get_clean() );
		}

	}

	public static function content_plugin_to_filter( $post ) {
        
		$all_plugins        = self::get_plugins_with_status();
        
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
		$block_plugins = get_post_meta( $post->ID, 'block_plugins', true );
		?><div class="col-12">
            <div class="header">
                <div class="title">
					<?php $count_plugins = 0;
					if ( $activate_plugins ) {
						foreach ( $activate_plugins as $activate_plugin ) {
							if ( in_array( $activate_plugin, $block_plugins ) ) {
								$count_plugins ++;
							}
						}
					}
					?>
                    Plugins <span class="disabled">- Disabled: <?= $count_plugins; ?>/<?= count( $activate_plugins ); ?></span>
                </div>
                <span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
            </div>
			<?php
			if ( $activate_plugins ):
				?>
                <div class="header attribute-plugin">Active plugins</div>
                <div class="plugin-wrapper">
					<?php
					foreach ( $activate_plugins as $activate_plugin => $activate_plugin_link ):
						?>
                        <div class="content<?= ( in_array( $activate_plugin, $block_plugins ) ) ? ' block' : '' ?>">
							<?php
							if ( in_array( $activate_plugin, $block_plugins ) ):
								?>
                                <span class="close" id="<?= $activate_plugin; ?>" value="<?= $post->ID; ?>"
                                      link="<?= $activate_plugin_link; ?>">×</span>
							<?php
							else:
								?>
                                <span class="close pluse_plugin" id="<?= $activate_plugin; ?>"
                                      value="<?= $post->ID; ?>" link="<?= $activate_plugin_link; ?>">+</span>
							<?php
							endif;
							?>
							<?php
							$groups_plugins = get_post_meta( $post->ID, 'block_group_plugins', true );
                            
                            if( empty( $groups_plugins ) ){
                                $groups_plugins = [];
                            } elseif ( ! is_array( $groups_plugins ) ){
                                $groups_plugins = [ $groups_plugins ];
                            }

							$groups = get_posts( array(
								'post_type'   => 'sos_group',
								'numberposts' => - 1,
							) );
							if ( $groups ) :
								?>
                                <div class="groups-names">
									<?php
									foreach ( $groups as $group ):
										if ( in_array( $group->post_title, $groups_plugins ) && in_array( $activate_plugin, explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) ) ) ):

											?>
                                            <span><?= $group->post_title; ?></span>
										<?php
										endif;
									endforeach;
									?>
                                </div>
							<?php
							endif;
							?>
                            <span><?= $activate_plugin; ?></span>
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
                        <div class="content deactivate-plugin<?= in_array( $deactivate_plugin, $block_plugins ) ? ' block' : ''; ?>">
							<?php
							$groups_plugins = get_post_meta( $post->ID, 'block_group_plugins', true );

							$groups = get_posts( array(
								'post_type'   => 'sos_group',
								'numberposts' => - 1,
							) );
							if ( $groups ) :
								?>
                                <div class="groups-names">
									<?php
									foreach ( $groups as $group ):
										if ( in_array( $group->post_title, $groups_plugins ) && in_array( $deactivate_plugin, explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) ) ) ):

											?>
                                            <span class="group-name"><?= $group->post_title; ?></span>
										<?php
										endif;
									endforeach;
									?>
                                </div>
							<?php
							endif;
							?>
                            <span><?= $deactivate_plugin; ?></span>
							<?php
							if ( in_array( $deactivate_plugin, $block_plugins ) ):
								?>
                                <span class="close" id="<?= $deactivate_plugin; ?>" value="<?= $post->ID; ?>"
                                      link="<?= $deactivate_plugin_link; ?>">×</span>
							<?php
							else:
								?>
                                <span class="close pluse_plugin" id="<?= $deactivate_plugin; ?>"
                                      value="<?= $post->ID; ?>" link="<?= $deactivate_plugin_link; ?>">+</span>
							<?php
							endif;
							?>
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
        </div><?php
	}

	public static function content_list_plugins( $activate_plugins ) {

		if ( $activate_plugins ){
            foreach ( $activate_plugins as $activate_plugin ){
                ?>
                <tr class="block_info">
                    <td><input type="checkbox"></td>
                    <td><?= $activate_plugin; ?></td>
                </tr>
				<?php
                    $posts = get_posts( array(
                        'post_type'   => 'sos_filter',
                        'numberposts' => - 1,
                    ) );
				?>
                <tr class="hidden_info">
                    <td colspan="2">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            Filters
                                        </div>
                                        <span class="count-plugin">( All: <?= wp_count_posts( 'sos_filter' )->publish; ?>   |   Trash: <?= wp_count_posts( 'sos_filter' )->trash; ?> )</span>
                                    </div>
									<?php
									if ( $posts ):
										?>
                                        <div class="plugin-wrapper">
											<?php
											foreach ( $posts as $post ):
												$group_plugins = get_post_meta( $post->ID, 'block_plugins', true );
												?>
                                                <a href="<?= esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_filters&filter_title=' . urlencode( $post->post_title ) ) ); ?>">
                                                    <div class="content
                                             <?php
													if ( in_array( $activate_plugin, $group_plugins ) ) {
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
                                                <span>No plugins</span>
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
			<?php } ?>
		<?php } else { ?>
            <tr class="plugin-wrapper no-plugins">
                <td colspan="2">
                    <div class="content">
                        <span>No plugins on this list.</span>
                    </div>
                </td>
            </tr>
		<?php
		}
	}

	public static function content_works( $posts ) {
		if ( $posts ){
			foreach ( $posts as $post ){ ?>
                <tr>
                    <td><input type="checkbox" id="<?= $post->ID; ?>"></td>
                    <td><?= $post->post_title; ?></td>
                    <td><?= get_post_meta( $post->ID, 'post_link', true ); ?></td>
                    <td><?= substr( str_replace( '-', '/', str_replace( " ", " at ", $post->post_date ) ), 0, - 3 ) . ' pm'; ?></td>
                    <td>
                        <a class="row-title"
                           href="<?= get_admin_url( null, 'admin.php?page=plugin_optimizer_filters&work_title=' . urlencode( str_replace( ' ', '_', str_replace( 'Add filter to ', '', $post->post_title ) ) ) . '&work_link=' . urlencode( get_post_meta( $post->ID, 'post_link', true ) ) ); ?>">
                            <button class="add-filter"><span class="pluse">+</span> add new filter</button>
                        </a>
                    </td>
                </tr>
			<?php
			}
		} else {
			?>
            <tr>
                <td colspan="5">Great job your work is done</td>
            </tr>
		<?php
		}
	}

	public static function content_groups( $posts ) {
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
		if ( $posts ){
			foreach ( $posts as $post ) :
				$group_plugins = get_post_meta( $post->ID, 'group_plugins', true );
				?>

                <tr class="block_info" id="group_<?= $post->ID; ?>">
                    <td><input type="checkbox" id="<?= $post->ID; ?>"></td>
                    <td><?= $post->post_title; ?></td>
                    <td><?= $group_plugins; ?></td>
                    <td><?= $group_plugins ? count( explode( ',', $group_plugins ) ) : 0; ?></td>
                </tr>
                <tr class="hidden_info">
                    <td colspan="6">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
											<?php
											$count_block_plugins = 0;

											foreach ( $all_plugins as $plugin ) {
												foreach ( $plugin as $key => $value ) {
													if ( $key === 'name' ) {
														if ( substr_count( $group_plugins, $plugin['name'] ) ) {
															$count_block_plugins ++;
														}
													}
												}
											}
											?>
                                            Plugins <span
                                                    class="disabled">- Disabled: <?= $count_block_plugins ?>/<?= count( $activate_plugins ); ?></span>
                                        </div>
                                        <span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
                                    </div>
									<?php
									if ( $activate_plugins ):
										?>
                                        <div class="plugin-wrapper wrapper-group-plugins">
											<?php
											foreach ( $activate_plugins as $activate_plugin ):
												?>
                                                <div class="content<?= substr_count( $group_plugins, $activate_plugin ) ? ' block' : ''; ?>"
                                                     group_id="<?= $post->ID; ?>">
                                                    <span><?= $activate_plugin; ?></span>
                                                </div>
											<?php
											endforeach;
											foreach ( $deactivate_plugins as $deactivate_plugin ):
												?>
                                                <div class="content deactivate-plugin<?= substr_count( $group_plugins, $deactivate_plugin ) ? ' block' : ''; ?>">
                                                    <span><?= $deactivate_plugin; ?></span>
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
				if ( $post->post_status === 'publish' ) {
					$posts_chidren = get_posts( array(
						'post_type'   => 'sos_group',
						'numberposts' => - 1,
						'meta_query'  => array(
							array(
								'key'   => 'group_parents',
								'value' => $post->post_title,
							)
						),
					) );
				} else if ( $post->post_status === 'trash' ) {
					$posts_chidren = get_posts( array(
						'post_type'   => 'sos_group',
						'numberposts' => - 1,
						'post_status' => 'trash',
						'meta_query'  => array(
							array(
								'key'   => 'group_parents',
								'value' => $post->post_title,
							)
						),
					) );
				}


				if ( $posts_chidren ) :
					foreach ( $posts_chidren as $post_chidren ) :
						$children_group_plugins = get_post_meta( $post_chidren->ID, 'group_plugins', true );
						?>

                        <tr class="block_info block_children">
                            <td><input type="checkbox" id="<?= $post_chidren->ID; ?>"></td>
                            <td> — <?= $post_chidren->post_title; ?></td>
                            <td><?= get_post_meta( $post_chidren->ID, 'type_group', true ); ?></td>
                            <td><?= $children_group_plugins; ?></td>
                            <td><?= substr_count( $children_group_plugins, ',' ) + 1; ?></td>
                        </tr>
                        <tr class="hidden_info">
                            <td colspan="6">
                                <div class="content-filter">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="header">
                                                <div class="title">
													<?php
													$count_block_plugins = 0;
													foreach ( $activate_plugins as $activate_plugin ) {
														if ( substr_count( $children_group_plugins, $activate_plugin ) ) {
															$count_block_plugins ++;
														}
													}
													?>
                                                    Plugins <span
                                                            class="disabled">- Disabled: <?= $count_block_plugins ?>/<?= count( $activate_plugins ); ?></span>
                                                </div>
                                                <span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
                                            </div>
											<?php
											if ( $activate_plugins ):
												?>
                                                <div class="plugin-wrapper wrapper-group-plugins">
													<?php
													foreach ( $activate_plugins as $activate_plugin ):
														?>
                                                        <div class="content <?= substr_count( $children_group_plugins, $activate_plugin ) ? 'block' : ''; ?>">
                                                            <span><?= $activate_plugin; ?></span>
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
					<?php endforeach;
				endif;

				?>
			<?php endforeach;
		} else {
			?>
            <tr>
                <td colspan="5">No Groups</td>
            </tr>
		<?php
		}
	}

	public static function content_filters_categories( $categories ) {
		if ( $categories ){
			foreach ( $categories as $cat ){ ?>
                <tr class="block_info" id="cat-<?= $cat->cat_ID ?>">
                    <td><input type="checkbox" id="<?= $cat->cat_ID ?>"></td>
                    <td class="data-title-category"><?= $cat->cat_name; ?></td>
                </tr>
                <tr class="hidden_info">
                    <td colspan="2">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            Name
                                        </div>
                                    </div>
                                    <div class="content-description">
                                        <span class="data-interaction data-title-cat" cat_id="<?= $cat->cat_ID ?>"
                                              contenteditable>
                                            <?= $cat->cat_name; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row description">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            Description
                                        </div>
                                    </div>
                                    <div class="content-description">
                                        <span class="data-interaction data-description-cat" cat_id="<?= $cat->cat_ID ?>"
                                              contenteditable>
                                            <?= $cat->category_description ? $cat->category_description : 'None description'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
											<?php
											$count_filters = 0;
											$posts         = get_posts( array(
												'post_type'   => 'sos_filter',
												'numberposts' => - 1,
											) );
											if ( $posts ) {
												foreach ( $posts as $post ) {
													if ( has_term( $cat->cat_ID, '?ategories_filters', $post->ID ) ) {
														$count_filters ++;
													}
												}
											}
											?>
                                            Filters <span
                                                    class="disabled">- Used: <?= $count_filters; ?>/<?= wp_count_posts( 'sos_filter' )->publish; ?></span>
                                        </div>
                                    </div>
                                    <div class="plugin-wrapper wrapper_filter_to_category">
										<?php
										$posts = get_posts( array(
											'post_type'   => 'sos_filter',
											'numberposts' => - 1,
										) );
										if ( $posts ) :
											foreach ( $posts as $post ) :
												?>
                                                <div class="content <?= has_term( $cat->cat_ID, '?ategories_filters', $post->ID ) ? 'block' : ''; ?>"
                                                     id="<?= $post->ID; ?>" cat_id="cat_<?= $cat->cat_ID; ?>">
                                                    <span><?= $post->post_title; ?></span>
                                                </div>
											<?php
											endforeach;
										endif;
										?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
				<?php
				$difference    = $cat->cat_ID;
				$subcategories = get_categories( array(
					'parent'     => $difference,
					'taxonomy'   => '?ategories_filters',
					'type'       => 'sos_filter',
					'hide_empty' => 0,
				) );
				if ( $subcategories ) :
					foreach ( $subcategories as $subcategory ) :?>
                        <tr class="block_info block_children">
                            <td><input type="checkbox" id="<?= $subcategory->cat_ID ?>"></td>
                            <td> — <?= $subcategory->cat_name; ?></td>
                        </tr>
                        <tr class="hidden_info">
                            <td colspan="2">
                                <div class="content-filter">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="header">
                                                <div class="title">
													<?php
													$count_filters = 0;
													$posts         = get_posts( array(
														'post_type'   => 'sos_filter',
														'numberposts' => - 1,
													) );
													if ( $posts ) {
														foreach ( $posts as $post ) {
															if ( has_term( $subcategory->cat_ID, '?ategories_filters', $post->ID ) ) {
																$count_filters ++;
															}
														}
													}
													?>
                                                    Filters <span
                                                            class="disabled">- Used: <?= $count_filters; ?>/<?= wp_count_posts( 'sos_filter' )->publish; ?></span>
                                                </div>
                                            </div>
                                            <div class="plugin-wrapper wrapper_filter_to_category">
												<?php
												$posts = get_posts( array(
													'post_type'   => 'sos_filter',
													'numberposts' => - 1,
												) );
												if ( $posts ) :
													foreach ( $posts as $post ) :
														?>
                                                        <div class="content <?= has_term( $subcategory->cat_ID, '?ategories_filters', $post->ID ) ? 'block' : ''; ?>"
                                                             id="<?= $post->ID; ?>"
                                                             cat_id="cat_<?= $subcategory->cat_ID; ?>">
                                                            <span><?= $post->post_title; ?></span>
                                                        </div>
													<?php
													endforeach;
												endif;
												?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
					<?php endforeach;
				endif;
				?>
			<?php }
        } else {
		?>
            <tr>
                <td colspan="5">No categories</td>
            </tr>
		<?php
        }
	}

	public static function content_filters( $posts ) {
		if ( $posts ){
			foreach ( $posts as $post ){
                $blocking_plugins = get_post_meta( $post->ID, 'block_plugins', true );
                sort( $blocking_plugins );
				?>
                <tr class="block_info" id="filter-<?= $post->ID; ?>">
                    <td><input type="checkbox" id="<?= $post->ID; ?>"></td>
                    <td><?= $post->post_title; ?></td>
                    <td><?= get_post_meta( $post->ID, 'category_filter', true ); ?></td>
                    <td class="data-type-filter"><?= get_post_meta( $post->ID, 'type_filter', true ); ?></td>
                    <td class="data-link-filter"><?= get_post_meta( $post->ID, 'selected_page', true ); ?></td>
                    <td class="expandable"><span class="no_hover"><?= count( $blocking_plugins ) ?></span><span class="yes_hover"><?= implode( ',<br/>', $blocking_plugins ); ?></span></td>
                </tr>
                <tr class="hidden_info">
                    <td colspan="6">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
									<?php
									$type_filter = get_post_meta( $post->ID, 'type_filter', true );
									if ( $type_filter === 'none' ):
										?>
                                        <div class="header">Permalinks</div>
                                        <div class="content-permalinks">
                                            <div class="link">
                                            <span class="data-interaction data-link" filter_id="<?= $post->ID ?>"
                                                  contenteditable>
                                            <?= get_post_meta( $post->ID, 'selected_page', true ) ?>
                                            </span>
                                            </div>
                                        </div>
									<?php

									else:
										?>
                                        <div class="header">Type</div>
                                        <div>
                                            <div class="content">
                                            <span class="data-interaction data-type" filter_id="<?= $post->ID ?>"
                                                  contenteditable>
                                                <?= $type_filter ?>
                                            </span>
                                            </div>
                                        </div>

									<?php
									endif;
									?>
                                </div>

                            </div>
                            <div class="row content-plugins">
								<?php
								Plugin_Optimizer_Helper::content_plugin_to_filter( $post );
								?>
                            </div>
                            <div class="row group-wrapper">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
											<?php
											$groups_plugins = get_post_meta( $post->ID, 'block_group_plugins', true );
											$count_groups   = 0;
											$groups         = get_posts( array(
												'post_type'   => 'sos_group',
												'numberposts' => - 1,
											) );
											if ( $groups ) {
												foreach ( $groups as $group ) {
													if ( in_array( $group->post_title, $groups_plugins ) ) {
														$count_groups ++;
													}
												}
											}
											?>
                                            groups <span
                                                    class="disabled">- Disabled: <?= $count_groups; ?>/<?= count( $groups ); ?></span>
                                        </div>
                                    </div>
                                    <div class="plugin-wrapper group-wrapper">
										<?php
										$all_plugins     = Plugin_Optimizer_Helper::get_plugins_with_status();
										$content_plugins = array();
										foreach ( $all_plugins as $plugin ) {
											foreach ( $plugin as $key => $value ) {
												if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
													$content_plugins[ $plugin['name'] ] = $plugin['file'];
												}
											}
										}

										$groups = get_posts( array(
											'post_type'   => 'sos_group',
											'numberposts' => - 1,
										) );
										if ( $groups ) :
											foreach ( $groups as $group ) :
												?>
                                                <div id="<?= $group->ID; ?>" value="<?= $post->ID; ?>"
                                                     class="content <?= in_array( $group->post_title, $groups_plugins ) ? 'block' : ''; ?> ">
                                                    <span><?= $group->post_title; ?></span>
													<?php
													$block_plugins_in_group = explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) );
													foreach ( $block_plugins_in_group as $block_plugin_in_group ) :
														?>
                                                        <div class="hidden_content content">
                                                            <span value="<?= isset( $content_plugins[ $block_plugin_in_group ] ) ? $content_plugins[ $block_plugin_in_group ] : "" ?>"><?= $block_plugin_in_group; ?></span>
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            categories
                                        </div>
                                    </div>
                                    <div class="plugin-wrapper">
										<?php
										self::create_category( $post );
										?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
			<?php
			}
        } else {
			?>
            <tr>
                <td colspan="6">No filters</td>
            </tr>
		<?php
		}
	}

}
