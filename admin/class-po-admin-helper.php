<?php
/**
 * Class PO_Admin_Helper
 */

class PO_Admin_Helper {

	static function get_filter_endpoints( $filter, $add_home = false ) {
        
        $endpoints = get_post_meta( $filter->ID, "endpoints", true );
        
        if( ! empty( $endpoints ) && is_array( $endpoints ) ){
            
            foreach( $endpoints as $key => $endpoint ){
                
                if( $add_home ){
                    if( strpos( $endpoint, home_url() ) === false ){
                        $endpoints[ $key ] = home_url() . $endpoint;
                    } else {
                        $endpoints[ $key ] = $endpoint;
                    }
                } else {
                    $endpoints[ $key ] = str_replace( home_url(), "", $endpoint );
                }
                
            }
            
        }
        
        return $endpoints;
    }
    
    
	static function content_part__header( $page_title, $class = "default" ) {
        
        $tabs = [
            "Filters",
            "Categories",
            "Groups",
            // "Worklist",
            "Settings",
        ];
        
        $tabs_html = '';
        
        foreach( $tabs as $tab_name ){
            
            $tab_name_low = strtolower( $tab_name );
            
            $tabs_html .= '<div id="window_' . $tab_name_low . '"    class="tabs' . ( $class == $tab_name_low ? " current" : "" ) . '">' . $tab_name . '</div>';
            
        }
        
        echo <<<EOF
        
        <div id="main_title">
            <h1>Plugin Optimizer</h1>
            <h2 id="name_page" class="$class">$page_title</h2>
        </div>

        <div id="main_tab_navigation" class="wrap-tabs">
            $tabs_html
        </div>
        
EOF;
        
    }
    
	static function content_part__bulk_actions( $posts ) {
        
        $months           = [];
        $months_html      = '';
        $filter_types     = [];
        $filter_type_html = '';
        
        foreach( $posts as $post ){
            
            $date = $post->post_date;
            
            $date_value = date("Ym",  strtotime( $date ) );// 202109
            $date_label = date("F Y", strtotime( $date ) );// September 2021
            
            $months[ $date_value ] = $date_label;
            
            if( $post->post_type == "sos_filter" && ! in_array( $post->filter_type, array_keys( $filter_types ) ) ){
                
                if( $post->filter_type == "_endpoint" ){
                    
                    $filter_types["_endpoint"] = "Endpoint";
                    
                } else {
                    
                    $filter_types["_edit_screen"] = "Post type";
                    
                }
                
            }
            // po_mu_plugin()->write_log( $post->filter_type, "content_part__bulk_actions-post-filter_type" );
            // break;
        }
        
        ksort( $months );
        
        foreach( $months as $value => $label ){
            
            $months_html .= '<option value="' . $value . '">' . $label . '</option>';
        }
        
        
        if( count( $filter_types ) >= 2 ){
            
            $filter_type_html .= '<div>';
            $filter_type_html .= '<select id="filter_by_type">';
            $filter_type_html .= '    <option value="default">All types</option>';
            
            foreach( $filter_types as $filter_type => $filter_type_label ){
                
                $filter_type_html .= '<option value="' . $filter_type . '">' . $filter_type_label . '</option>';
            }
            
            $filter_type_html .= '</select>';
            $filter_type_html .= '<button id="btn_type_filter" class="po_secondary_button">Filter</button>';
            $filter_type_html .= '</div>';
            
        }
        
        echo <<<EOF
        
                <div id="bulk_actions" class="col-6">
                    <div>
                        <select id="check_all_elements">
                            <option value="default">Bulk actions</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button id="btn_apply" class="po_secondary_button">Apply</button>
                    </div>
                    <div>
                        <select id="filter_all_elements">
                            <option value="default">All dates</option>
                            $months_html
                        </select>
                        <button id="btn_date_filter" class="po_secondary_button">Filter</button>
                    </div>
                    $filter_type_html
                </div>
            
EOF;
        
    }
    
	static function content_part__plugins( $args = [] ) {
        
        $defaults = [
			'plugins'   => [],
            'blocked'   => [],
            'inactive'  => [],
        ];
        
        $data = wp_parse_args( $args, $defaults );
        
        // po_mu_plugin()->write_log( $data, "content_part__plugins-data" );
        
		if( $data["plugins"] ){
            
            echo '<div class="plugin-wrapper">';
            
			foreach( $data["plugins"] as $plugin_id => $plugin_name ){
                
                $class   = "single_plugin";
                $checked = '';
                
                if( ! empty( $data["blocked"][ $plugin_id ] ) || in_array( $plugin_id, $data["blocked"] ) ){
                    $class  .= " blocked";
                    $checked = ' checked="checked"';
                }
                
                if( ! empty( $data["inactive"][ $plugin_id ] ) || in_array( $plugin_id, $data["inactive"] ) ){
                    $class  .= " inactive";
                }
                
                echo '<div class="' . $class . '" data-id="' . $plugin_id . '" data-name="' . $plugin_name . '">';
                echo    '<input class="noeyes" type="checkbox" name="PO_filter_data[plugins_to_block][' . $plugin_id . ']" value="' . $plugin_name . '"' . $checked . '/>';
                echo    '<span class="plugin_name" value="' . $plugin_id . '">' . $plugin_name . '</span>';
                echo    '<span class="group_name">' . '</span>';
                echo '</div>';
                
			}
            
            echo '</div>';
            
        } else {
            
            echo '<div>';
            echo     '<span>No plugins found</span>';
            echo '</div>';
            
		}
	}


	static function list_content__filters( $filters ) {
        
		if( $filters ){
            
			foreach( $filters as $filter ){
                
                $data_endpoints   = self::get_filter_endpoints( $filter, true );
                $data_type        = get_post_meta( $filter->ID, 'filter_type',      true );
                $blocking_plugins = get_post_meta( $filter->ID, 'plugins_to_block', true );
                $turned_off       = get_post_meta( $filter->ID, 'turned_off',       true );
                
                sort( $blocking_plugins );
                
                if( empty( $data_type ) || $data_type == "_endpoint" || $data_type == "none" ){
                    $trigger = implode( ',<br>', $data_endpoints );
                    $type    = "_endpoint";
                } else {
                    $trigger = "Editing post type: <b>" . $data_type . "</b>";
                    $type    = $data_type;
                    $type    = "_edit_screen";
                }
                
                $categories = implode( ',<br>', get_post_meta( $filter->ID, 'categories', true ) );
                
                $date = date("Ym",  strtotime( $filter->post_date ) );// 202109
                
                $turned_on_checked = $turned_off !== "1" ? ' checked="checked"' : '';
                
				?>
                <tr class="block_info" id="filter-<?php echo  $filter->ID ?>" data-status="<?php echo $filter->post_status ?>" data-date="<?php echo $date ?>" data-type="<?php echo $type ?>">
                    <td><input type="checkbox" id="<?php echo $filter->ID ?>"></td>
                    <td class="align-left normal-text"><?php echo $filter->post_title ?><br/><a class="edit_item" href="<?php echo admin_url('admin.php?page=plugin_optimizer_add_filters&filter_id=' . $filter->ID ) ?>">Edit</a><br/></td>
                    <td class="align-left normal-text"><?php echo $categories ?></td>
                    <td class="data-trigger align-left normal-text"><?php echo $trigger ?></td>
                    <td class="expandable list_of_plugins"><span class="no_hover"><?php echo count( $blocking_plugins ) ?></span><span class="yes_hover"><?php echo implode( ',<br/>', $blocking_plugins ); ?></span></td>
                    <td class="toggle_filter">
                        <label>
                            <span class="switch">
                                <input class="turn_off_filter" data-id="<?php echo $filter->ID ?>" type="checkbox"<?php echo $turned_on_checked ?>/>
                                <span class="slider round"></span>
                            </span>
                        </label>
                    </td>
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

	static function list_content__groups( $groups ) {
        
		if ( $groups ){
			foreach ( $groups as $group ){
				$group_plugins = get_post_meta( $group->ID, 'group_plugins', true );

                $date = date("Ym",  strtotime( $group->post_date ) );// 202109
                
				?>
                <tr class="block_info" id="group_<?php echo $group->ID; ?>" data-status="<?php echo $group->post_status ?>" data-date="<?php echo $date ?>">
                    <td><input type="checkbox" id="<?php echo $group->ID; ?>"></td>
                    <td class="align-left normal-text"><?php echo $group->post_title; ?><br/><a class="edit_item" href="<?php echo admin_url('admin.php?page=plugin_optimizer_add_groups&group_id=' . $group->ID ) ?>">Edit</a><br/></td>
                    <td><?php echo implode( '<br/>', $group_plugins ) ?></td>
                    <td><?php echo $group_plugins ? count( $group_plugins ) : 0 ?></td>
                </tr>
			<?php
            }
		} else {
			?>
            <tr>
                <td colspan="5">No Groups found</td>
            </tr>
		<?php
		}
	}

	static function list_content__works( $work_items ) {
		if ( $work_items ){
			foreach ( $work_items as $work_item ){
                
                $date = date("Ym",  strtotime( $work_item->post_date ) );// 202109
                
                $relative_url  = 'admin.php?page=plugin_optimizer_add_filters';
                $relative_url .= '&work_title=';
                $relative_url .= urlencode( str_replace( ' ', '_', str_replace( 'Add filter to ', '', $work_item->post_title ) ) );
                $relative_url .= '&work_link=';
                $relative_url .= urlencode( get_post_meta( $work_item->ID, 'post_link', true ) );
                
                $create_link = get_admin_url( null, $relative_url );
                
                ?>
                <tr class="block_info" data-status="<?php echo $work_item->post_status ?>" data-date="<?php echo $date ?>">
                    <td><input type="checkbox" id="<?php echo $work_item->ID ?>"></td>
                    <td class="align-left normal-text"><?php echo $work_item->post_title ?></td>
                    <td class="align-left normal-text"><?php echo get_post_meta( $work_item->ID, 'post_link', true ) ?></td>
                    <td><?php echo substr( str_replace( '-', '/', str_replace( " ", " at ", $work_item->post_date ) ), 0, - 3 ) . ' pm' ?></td>
                    <td>
                        <a class="row-title" href="<?php echo $create_link ?>">
                            <button class="po_green_button">Create Filter</button>
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

	static function list_content__categories( $categories ) {
		if ( $categories ){
			foreach ( $categories as $cat ){
                ?>
                <tr class="block_info" id="cat-<?php echo $cat->term_id ?>" data-status="publish">
                    <td class="cat_checkbox"><input type="checkbox" id="<?php echo $cat->term_id ?>"></td>
                    <td class="cat_edit"><a href="<?php echo admin_url('admin.php?page=plugin_optimizer_add_categories&cat_id=' . $cat->term_id ) ?>">Edit</a></td>
                    <td class="cat_title"><?php echo $cat->cat_name ?></td>
                    <td class="cat_description"><?php echo $cat->description ? $cat->description : "-" ?></td>
                    <?php // TODO add a link to filter the filters by category ?>
                    <td class="cat_filters"><?php echo $cat->count ?></td>
                </tr>
			<?php }
        } else {
		?>
            <tr>
                <td colspan="5">No categories</td>
            </tr>
		<?php
        }
	}


    // if $all == false -> return array( "active" => [], "inactive" => [] )
    // if $all == true  -> return []
	static function get_plugins_with_status( $all = false, $remove_po = true ){
        
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

}
