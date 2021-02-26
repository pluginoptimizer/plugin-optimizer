<?php
/**
 * Class PO_Admin_Helper
 */

class PO_Admin_Helper {

	static function get_filter_endpoints( $filter ) {
        
        $endpoints = get_post_meta( $filter->ID, "selected_page", true );
        
        $endpoints = explode( ",", $endpoints );
        
        foreach( $endpoints as $index => $value ){
            
            $endpoints[ $index ] = trim( $value );
        }
        
        return $endpoints;
    }
    
	static function content_part__header( $page_title, $class = "default" ) {
        
        echo <<<EOF
        
        <div class="row col-12">
            <h1>Plugin Optimizer</h1>
        </div>
        <div class="row col-12">
            <h2 id="name_page" class="$class">$page_title</h2>
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
                <input class="search" type="search" id="search_elements" name="s" value="" placeholder="Search groups">
            </div>
        </div>
        
EOF;
        
    }
    
	static function content_part__bulk_actions( $posts ) {
        
        $months      = [];
        $months_html = "";
        
        foreach( $posts as $post ){
            
            $date = $post->post_date;
            
            $date_value = date("Ym",  strtotime( $date ) );// 202109
            $date_label = date("F Y", strtotime( $date ) );// September 2021
            
            $months[ $date_value ] = $date_label;
            
            // po_mu_plugin()->write_log( $post, "content_part__bulk_actions-post" );
            // break;
        }
        
        ksort( $months );
        
        foreach( $months as $value => $label ){
            
            $months_html .= '<option value="' . $value . '">' . $label . '</option>';
        }
        
        echo <<<EOF
        
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
                        $months_html
                    </select>
                    <button id="btn_date_filter">Filter</button>
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
                echo    '<span value="' . $plugin_id . '">' . $plugin_name . '</span>';
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
                
                $data_type        = get_post_meta( $filter->ID, 'filter_type',      true );
                $data_endpoints   = get_post_meta( $filter->ID, 'endpoints',        true );
                $blocking_plugins = get_post_meta( $filter->ID, 'plugins_to_block', true );
                
                sort( $blocking_plugins );
                
                if( empty( $data_type ) || $data_type == "_endpoint" || $data_type == "none" ){
                    $trigger = implode( ',<br>', $data_endpoints );
                } else {
                    $trigger = "Backend editing of custom post type: <b>" . $data_type . "</b>";
                }
                
                $categories = implode( ',<br>', get_post_meta( $filter->ID, 'categories', true ) );
                
                $date = date("Ym",  strtotime( $filter->post_date ) );// 202109
                
				?>
                <tr class="block_info" id="filter-<?=  $filter->ID ?>" data-status="<?= $filter->post_status ?>" data-date="<?= $date ?>">
                    <td><input type="checkbox" id="<?= $filter->ID ?>"></td>
                    <td class="align-left normal-text"><?= $filter->post_title ?></td>
                    <td class="align-left normal-text"><?= $categories ?></td>
                    <td class="data-trigger align-left normal-text"><?= $trigger ?></td>
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

	public static function list_content__groups( $groups ) {
        
		if ( $groups ){
			foreach ( $groups as $group ){
				$group_plugins = get_post_meta( $group->ID, 'group_plugins', true );

                $date = date("Ym",  strtotime( $group->post_date ) );// 202109
                
				?>
                <tr class="block_info" id="group_<?= $group->ID; ?>" data-status="<?= $group->post_status ?>" data-date="<?= $date ?>">
                    <td><input type="checkbox" id="<?= $group->ID; ?>"></td>
                    <td class="align-left normal-text"><?= $group->post_title; ?></td>
                    <td><?= $group_plugins; ?></td>
                    <td><?= $group_plugins ? count( explode( ',', $group_plugins ) ) : 0; ?></td>
                </tr>
				<?php
				if ( $group->post_status === 'publish' ) {
					$posts_chidren = get_posts( array(
						'post_type'   => 'sos_group',
						'numberposts' => - 1,
						'meta_query'  => array(
							array(
								'key'   => 'group_parents',
								'value' => $group->post_title,
							)
						),
					) );
				} else if ( $group->post_status === 'trash' ) {
					$posts_chidren = get_posts( array(
						'post_type'   => 'sos_group',
						'numberposts' => - 1,
						'post_status' => 'trash',
						'meta_query'  => array(
							array(
								'key'   => 'group_parents',
								'value' => $group->post_title,
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

	public static function list_content__works( $work_items ) {
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
                <tr data-status="<?= $work_item->post_status ?>" data-date="<?= $date ?>">
                    <td><input type="checkbox" id="<?= $work_item->ID ?>"></td>
                    <td class="align-left normal-text"><?= $work_item->post_title ?></td>
                    <td class="align-left normal-text"><?= get_post_meta( $work_item->ID, 'post_link', true ) ?></td>
                    <td><?= substr( str_replace( '-', '/', str_replace( " ", " at ", $work_item->post_date ) ), 0, - 3 ) . ' pm' ?></td>
                    <td>
                        <a class="row-title" href="<?= $create_link ?>">
                            <button class="po_green_button"><span class="pluse">+</span> Create Filter</button>
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

}
