<?php
/**
 * Plugin Name:       Plugin Optimizer MU
 * Plugin URI:        plugin-uri.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Web Dev
 * Author URI:        author-uri.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

class Plugin_Optimizer_MU {
    
    protected static $instance      = null;
    
    protected $po_pages             = [];
    protected $po_post_types        = [];
    
    public $po_default_page         = false;
    public $should_be_in_worklist   = null;
    
    public $all_plugins             = [];
    public $original_active_plugins = [];
    public $filtered_active_plugins = [];
    public $plugins_to_block        = [];
    public $blocked_plugins         = [];
    public $filters_in_use          = [];

	private function __construct() {
        
        if( wp_doing_ajax() ){
            return;
        }
        
        $this->should_be_in_worklist = true;
        
        $this->po_pages = [
            "/wp-admin/admin.php?page=plugin_optimizer",
            "/wp-admin/admin.php?page=plugin_optimizer_add_filters",
            "/wp-admin/admin.php?page=plugin_optimizer_filters",
            "/wp-admin/admin.php?page=plugin_optimizer_filters_categories",
            "/wp-admin/admin.php?page=plugin_optimizer_groups",
            "/wp-admin/admin.php?page=plugin_optimizer_add_groups",
            "/wp-admin/admin.php?page=plugin_optimizer_worklist",
            "/wp-admin/admin.php?page=plugin_optimizer_settings",
            "/wp-admin/admin.php?page=plugin_optimizer_support",
        ];
        $this->po_post_types = [
            "sos_filter",
        ];
        
		$this->set_hooks();
        
	}
    
    static function get_instance() {

        if( self::$instance == null ){
            self::$instance = new self();
        }
     
        return self::$instance;

	}


	function set_hooks() {

		add_filter( 'option_active_plugins', [ $this, 'filter_active_plugins_option_value' ], 5 );
        
		add_action( 'plugins_loaded',        [ $this, 'complete_action_once_plugins_are_loaded' ], 5 );

	}

	function complete_action_once_plugins_are_loaded(){

		remove_filter('option_active_plugins', [ $this, 'disable_filtered_plugins_for_current_url' ], 5 );

	}


	function filter_active_plugins_option_value( $active_plugins ) {
        
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

        $this->all_plugins = get_plugins();
        
        // $this->write_log( $this->all_plugins, "adsgashgwrest5h" );

        
        
        $this->original_active_plugins  = $active_plugins;

		$this->plugins_to_block         = $this->get_plugins_to_block_for_current_url();
        
        $this->filtered_active_plugins  = array_diff( $this->original_active_plugins, $this->plugins_to_block );
        
        $this->blocked_plugins          = array_intersect( $this->original_active_plugins, $this->plugins_to_block );

		return $this->filtered_active_plugins;

	}

	function get_plugins_to_block_for_current_url() {
        
		$relative_url  = trim( $_SERVER["REQUEST_URI"] );
		$current_url   = get_home_url() . $relative_url;
        
        // $this->write_log( $this->original_active_plugins, "ngosifugnsodifgn-original_active_plugins" );
        // $this->write_log( $relative_url, "ngosifugnsodifgn-relative_url" );
        
        $editing_post_type = $this->are_editing_post_type( $relative_url );
        
        // --- are we on any of the PO pages?
        
        if( in_array( $relative_url, $this->po_pages ) || in_array( $editing_post_type, $this->po_post_types ) ){
            
            $this->po_default_page          = true;
            $this->should_be_in_worklist    = false;
            $this->plugins_to_block         = array_diff( $this->original_active_plugins, [ "plugin-optimizer/plugin-optimizer.php" ] );
            
            return $this->plugins_to_block;
        }
        
        // --- Get plugins to block from all the filters
        
		$filters = get_posts([
			'post_type'   => 'sos_filter',
			'numberposts' => - 1,
		]);
        
		foreach( $filters as $filter ){
            
            // If we're on the edit post screen, filter by post type
			if( $filter->type_filter !== 'none' && $editing_post_type && $editing_post_type == $filter->type_filter ){
                
				$this->use_filter( $filter );
			}

            // Filter by URL
			if( is_array( $filter->selected_page ) ){
                
                if( in_array( $current_url, $filter->selected_page ) ){
                    
                    $this->use_filter( $filter );
                }
                
			} elseif( $filter->selected_page == $current_url ){

				$this->use_filter( $filter );
			}

		}
        
		return array_unique( $this->plugins_to_block );
	}

    function use_filter( $filter ){
        
        $this->should_be_in_worklist = false;
        
        $this->plugins_to_block = array_merge( $this->plugins_to_block, $filter->block_value_plugins );
        
        $this->filters_in_use[ $filter->ID ] = $filter->post_title;
        
        return $filter->block_value_plugins;
        
    }
    

	function are_editing_post_type( $url ){
        
        $post_id   = $this->url_to_postid( $url );
        $post_type = false;
        
        if( $post_id !== 0 && strpos( $url, "post.php" ) !== false && strpos( $url, "action=edit" ) !== false ){
            
            $post_type = get_post_type( $post_id );
        }
        
        return $post_type;
        
	}
    
	function url_to_postid( $url ){
        
        parse_str( parse_url( $url, PHP_URL_QUERY ), $query_vars);
        
        $post_id =                   ! empty( $query_vars["post"] )    ? $query_vars["post"]    : 0;
        $post_id = $post_id === 0 && ! empty( $query_vars["post_id"] ) ? $query_vars["post_id"] : $post_id;
        
        return $post_id;
        
	}
    
    function write_log( $log, $text = "write_log: ", $file_name = "debug.log" )  {
        
        $file = WP_CONTENT_DIR . '/' . $file_name;
        
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( $text . PHP_EOL . print_r( $log, true ) . PHP_EOL, 3, $file );
        } else {
            error_log( $text . PHP_EOL . $log . PHP_EOL . PHP_EOL, 3, $file );
        }
        
    }

    function get_names_list( $array_name, $key = "Name" ){
        
        $list = [];
        
        foreach( $this->$array_name as $plugin_id ){
            
            $list[ $plugin_id ] = $this->all_plugins[ $plugin_id ][ $key ];
            
        }
        
        // $list = array_map( function( $plugin_id ) use ( $key ){
            
            // return $this->all_plugins[ $plugin_id ][ $key ];
            
        // }, $this->$array_name );
        
        natcasesort( $list );
        
        return $list;
        
    }
    
}

function po_mu_plugin(){
     return Plugin_Optimizer_MU::get_instance();
}
po_mu_plugin();
