<?php

/**
 * Class PO_Helper
 */
class PO_Helper {

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
        ?><button class="po_green_button add_endpoint add-category" id="post-<?= $id_filter; ?>">
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
                                <span class="close" id="<?= $plugin_name; ?>"
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
                                <span class="close" id="<?= $plugin_name; ?>"
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

}
