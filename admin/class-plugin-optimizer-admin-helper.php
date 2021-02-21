<?php
/**
 * Class Plugin_Optimizer_Admin_Helper
 */

class Plugin_Optimizer_Admin_Helper {

	public static function content_part__plugins( $args = [] ) {
        
        $defaults = [
			'plugins'   => [],
            'blocked'   => [],
            'inactive'  => [],
        ];
        
        $data = wp_parse_args( $args, $defaults );
        
		if( $data["plugins"] ){
            
            echo '<div class="plugin-wrapper">';
            
			foreach( $data["plugins"] as $plugin_id => $plugin_name ){
                
                $class = "single_plugin";
                
                if( ! empty( $data["blocked"] ) ){
                    $class .= " blocked";
                }
                
                if( ! empty( $data["inactive"] ) ){
                    $class .= " inactive";
                }
                
                echo '<div class="' . $class . '" data-id="' . $plugin_id . '" data-name="' . $plugin_name . '">';
                echo    '<span value="' . $plugin_id . '">' . $plugin_name . '</span>';
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
