<?php
/**
 * Class PO_Admin_Helper
 */

class PO_Admin_Helper {

	public static function get_filter_endpoints( $filter ) {
        
        $endpoints = get_post_meta( $filter->ID, "selected_page", true );
        
        $endpoints = explode( ",", $endpoints );
        
        foreach( $endpoints as $index => $value ){
            
            $endpoints[ $index ] = trim( $value );
        }
        
        return $endpoints;
    }
    
	public static function content_part__header( $page_title, $class = "default" ) {
        
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
    
	public static function content_part__bulk_actions( $posts ) {
        
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
                        $months_html
                    </select>
                    <button id="btn_filter">Filter</button>
                </div>
            </div>
            
EOF;
        
    }
    
	public static function content_part__plugins( $args = [] ) {
        
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
                echo    '<input class="noeyes" type="checkbox" name="[PO_filter_data][plugins_to_block][' . $plugin_id . ']" value="' . $plugin_name . '"' . $checked . '/>';
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

}