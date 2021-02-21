<?php

/**
 * Class Plugin_Optimizer_Helper
 */
class Plugin_Optimizer_Helper {

    // if $all == false -> return array( "active" => [], "inactive" => [] )
    // if $all == true  -> return []
	public static function get_plugins_with_status( $all = false, $remove_po = true ){
        
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		
		$plugins             = [];
		$plugins_simple_list = [ "active" => [], "inactive" => [], "all" => [] ];
        
        if( empty( po_mu_plugin()->all_plugins ) ){
            
            $all_plugins    = get_plugins();
            $active_plugins = get_option( 'active_plugins' );

        } else {

            $all_plugins    = po_mu_plugin()->all_plugins;
            $active_plugins = po_mu_plugin()->original_active_plugins;
            
        }
        
        // po_mu_plugin()->write_log( $active_plugins, "get_plugins_with_status-active_plugins" );
        // po_mu_plugin()->write_log( $all_plugins,    "get_plugins_with_status-all_plugins" );

		foreach ( $active_plugins as $plugin_id ) {
            
            if( $plugin_id != "plugin-optimizer/plugin-optimizer.php" || ! $remove_po ){
                $plugins_simple_list["active"][ $plugin_id ] = $all_plugins[ $plugin_id ][ 'Name' ];
                $plugins_simple_list["all"][ $plugin_id ]    = $all_plugins[ $plugin_id ][ 'Name' ];
            }
            
			$plugins[] = [
				'name'      => $all_plugins[ $plugin_id ][ 'Name' ],
				'file'      => $plugin_id,
				'is_active' => 1,
			];
            
			unset( $all_plugins[ $plugin_id ] );
		}

		foreach ( $all_plugins as $plugin_id => $plugin_data ) {
            
            if( $plugin_id != "plugin-optimizer/plugin-optimizer.php" || ! $remove_po ){
                $plugins_simple_list["inactive"][ $plugin_id ] = $all_plugins[ $plugin_id ][ 'Name' ];
                $plugins_simple_list["all"][ $plugin_id ]      = $all_plugins[ $plugin_id ][ 'Name' ];
            }
            
			$plugins[] = [
				'name'      => $plugin_data[ 'Name' ],
				'file'      => $plugin_id,
				'is_active' => 0,
			];
		}
        
		return $all ? $plugins : $plugins_simple_list;

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
        
		$plugins       = self::get_plugins_with_status();
		$block_plugins = get_post_meta( $post->ID, 'block_plugins', true );
        $count_plugins = 0;
		
        if ( $plugins["active"] ) {
			foreach ( $plugins["active"] as $plugin_name ) {
				if ( in_array( $plugin_name, $block_plugins ) ) {
					$count_plugins ++;
				}
			}
		}
		
        ?><div class="col-12">
            <div class="header">
                <div class="title">
                    Plugins <span class="disabled">- Disabled: <?= $count_plugins; ?>/<?= count( $plugins["active"] ); ?></span>
                </div>
                <span class="count-plugin">( Active: <?= count( $plugins["active"] ); ?>   |   Inactive: <?= count( $plugins["inactive"] ); ?> )</span>
            </div>
			<?php
			if ( $plugins["active"] ):
				?>
                <div class="header attribute-plugin">Active plugins</div>
                <div class="plugin-wrapper">
					<?php
					foreach ( $plugins["active"] as $plugin_id => $plugin_name ):
						?>
                        <div class="content<?= ( in_array( $plugin_name, $block_plugins ) ) ? ' block' : '' ?>">
							<?php
							if ( in_array( $plugin_name, $block_plugins ) ):
								?>
                                <span class="close" id="<?= $plugin_name; ?>" value="<?= $post->ID; ?>"
                                      link="<?= $plugin_id; ?>">×</span>
							<?php
							else:
								?>
                                <span class="close pluse_plugin" id="<?= $plugin_name; ?>"
                                      value="<?= $post->ID; ?>" link="<?= $plugin_id; ?>">+</span>
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
										if ( in_array( $group->post_title, $groups_plugins ) && in_array( $plugin_name, explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) ) ) ):

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
                            <span><?= $plugin_name; ?></span>
                        </div>
					<?php
					endforeach;
					?>
                </div>
                <div class="header attribute-plugin">Inactive plugins</div>
                <div class="plugin-wrapper">
					<?php
					foreach ( $plugins["inactive"] as $plugin_id => $plugin_name ):
						?>
                        <div class="content deactivate-plugin<?= in_array( $plugin_name, $block_plugins ) ? ' block' : ''; ?>">
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
										if ( in_array( $group->post_title, $groups_plugins ) && in_array( $plugin_name, explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) ) ) ):

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
                            <span><?= $plugin_name; ?></span>
							<?php
							if ( in_array( $plugin_name, $block_plugins ) ):
								?>
                                <span class="close" id="<?= $plugin_name; ?>" value="<?= $post->ID; ?>"
                                      link="<?= $plugin_id; ?>">×</span>
							<?php
							else:
								?>
                                <span class="close pluse_plugin" id="<?= $plugin_name; ?>"
                                      value="<?= $post->ID; ?>" link="<?= $plugin_id; ?>">+</span>
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
                        <span>No active plugins for blocking</span>
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
                    
                    <?php /* TODO Check next line because we got rid of work_title */ ?>
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

	public static function content__filters( $args = [] ) {
        
        $defaults = [
			'post_type'   => 'sos_filter',
			'numberposts' => -1,
        ];
        
        $args = wp_parse_args( $args, $defaults );
        
		$filters = get_posts( $args );

        
		if( $filters ){
            
			foreach( $filters as $filter ){
                
                $blocking_plugins = get_post_meta( $filter->ID, 'block_plugins', true );
                
                sort( $blocking_plugins );
                
                $data_type      = get_post_meta( $filter->ID, 'type_filter',   true );
                $data_endpoints = get_post_meta( $filter->ID, 'selected_page', true );
                
                if( empty( $data_type ) || $data_type == "none" ){
                    $trigger = implode( ',<br>', explode( ',', $data_endpoints ) );
                } else {
                    $trigger = "Backend editing of custom post type: <b>" . $data_type . "</b>";
                }
                
                $categories = implode( ',<br>', explode( ',', get_post_meta( $filter->ID, 'category_filter', true ) ) );
                
				?>
                <tr class="block_info" id="filter-<?=  $filter->ID ?>" data-status="<?= $filter->post_status ?>">
                    <td><input type="checkbox" id="<?= $filter->ID ?>"></td>
                    <td class="data-title"><?= $filter->post_title ?></td>
                    <td class="data-categories"><?= $categories ?></td>
                    <td class="data-trigger"><?= $trigger ?></td>
                    <td class="expandable list_of_plugins"><span class="no_hover"><?= count( $blocking_plugins ) ?></span><span class="yes_hover"><?= implode( ',<br/>', $blocking_plugins ); ?></span></td>
                </tr>
			<?php
			}
        } else {
			?>
            <tr>
                <td colspan="6">No filters found</td>
            </tr>
		<?php
		}
	}

}
