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
    
    protected $original_active_plugins;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access   protected
	 * @var      Plugin_Optimizer_Loader_MU $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Plugin_Optimizer_MU constructor.
	 */
	public function __construct() {

		$this->loader = new Plugin_Optimizer_Loader_MU();
		$this->define_mu_hooks();

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_mu_hooks() {

		$this->loader->add_filter( 'option_active_plugins', $this, 'disable_filtered_plugins_for_current_url', 5 );
		$this->loader->add_action('plugins_loaded', $this, 'clear_active_plugins_filters', 5);

	}

	/**
	 * Exclude blocked plugins
	 *
	 * @access   public
	 */
	public function clear_active_plugins_filters (){

		remove_filter('option_active_plugins', array($this, 'disable_filtered_plugins_for_current_url'), 5);

	}

	/**
	 * Filter the necessary plugins from unnecessary
	 *
	 * @access   public
	 */
	public function disable_filtered_plugins_for_current_url( $active_plugins ) {
        
        $this->original_active_plugins = $active_plugins;

		if ( wp_doing_ajax() ) {
			return $active_plugins;
		}

		if ( is_admin() ) {
			// return $active_plugins;
		}

		$disabled_plugins = $this->get_current_url_disabled_plugins();

		return array_diff( $active_plugins, $disabled_plugins );

	}

	/**
	 * Getting plugins to exclude
	 *
	 * @access   private
	 */
	private function get_current_url_disabled_plugins() {
        
		$block_plugins = array();
        
		$relative_url  = trim( $_SERVER["REQUEST_URI"] );
		$current_url   = get_home_url() . $relative_url;
        
        // write_log( $this->original_active_plugins, "ngosifugnsodifgn-original_active_plugins" );
        // write_log( $relative_url, "ngosifugnsodifgn-relative_url" );
        
        $editing_post_type = $this->are_editing_post_type( $relative_url );
        
        // --- are we on any of the PO pages?
        
        $po_pages = [
            // "/wp-admin/admin.php?page=plugin_optimizer_add_filters",
            "/wp-admin/admin.php?page=plugin_optimizer_overview",
            "/wp-admin/admin.php?page=plugin_optimizer_filters",
            "/wp-admin/admin.php?page=plugin_optimizer_filters_categories",
            "/wp-admin/admin.php?page=plugin_optimizer_groups",
            "/wp-admin/admin.php?page=plugin_optimizer_add_groups",
            "/wp-admin/admin.php?page=plugin_optimizer_worklist",
            "/wp-admin/admin.php?page=plugin_optimizer_settings",
            "/wp-admin/admin.php?page=plugin_optimizer_support",
        ];
        $po_post_types = [
            "sos_filter",
        ];
        
        if( in_array( $relative_url, $po_pages ) || in_array( $editing_post_type, $po_post_types ) ){
            
            $blocked_plugins = array_diff( $this->original_active_plugins, [ "sos_plugin_optimizer/plugin-optimizer.php" ] );
            
            return $blocked_plugins;
        }
        
        // ---
        
        
		$filters         = get_posts( array(
			'post_type'   => 'sos_filter',
			'numberposts' => - 1,
		) );
        
        // sos_plugin_optimizer/plugin-optimizer.php


		foreach ( $filters as $filter ) {

			$selected_pages = get_post_meta( $filter->ID, 'selected_page', true );
			$type_filter    = get_post_meta( $filter->ID, 'type_filter', true );

			if( $type_filter !== 'none' && $editing_post_type && $editing_post_type == $type_filter ){
                
				$block_plugins = array_merge( $block_plugins, get_post_meta( $filter->ID, 'block_value_plugins', true ) );
			}

			if ( is_array( $selected_pages ) ) {
				foreach ( $selected_pages as $selected_page ) {
					if ( $selected_page == $current_url ) {
						$block_plugins = array_merge( $block_plugins, get_post_meta( $filter->ID, 'block_value_plugins', true ) );
					}
				}
			} else {
				if ( $selected_pages == $current_url ) {
					$block_plugins = array_merge( $block_plugins, get_post_meta( $filter->ID, 'block_value_plugins', true ) );
				}
			}

		}

		return $block_plugins;
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
    
}


/**
 * Register all actions and filters for the plugin.
 */
class Plugin_Optimizer_Loader_MU {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @access   protected
	 * @var      array $actions The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @access   protected
	 * @var      array $filters The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @param string $hook The name of the WordPress action that is being registered.
	 * @param object $component A reference to the instance of the object on which the action is defined.
	 * @param string $callback The name of the function definition on the $component.
	 * @param int $priority Optional. The priority at which the function should be fired. Default is 10.
	 * @param int $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @param string $hook The name of the WordPress filter that is being registered.
	 * @param object $component A reference to the instance of the object on which the filter is defined.
	 * @param string $callback The name of the function definition on the $component.
	 * @param int $priority Optional. The priority at which the function should be fired. Default is 10.
	 * @param int $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @access   private
	 *
	 * @param array $hooks The collection of hooks that is being registered (that is, actions or filters).
	 * @param string $hook The name of the WordPress filter that is being registered.
	 * @param object $component A reference to the instance of the object on which the filter is defined.
	 * @param string $callback The name of the function definition on the $component.
	 * @param int $priority The priority at which the function should be fired.
	 * @param int $accepted_args The number of arguments that should be passed to the $callback.
	 *
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array(
				$hook['component'],
				$hook['callback']
			), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array(
				$hook['component'],
				$hook['callback']
			), $hook['priority'], $hook['accepted_args'] );
		}

	}

}

/**
 * Begins execution of the plugin.
 */
function run_plugin_optimizer_mu() {

	$plugin = new Plugin_Optimizer_MU();
	$plugin->run();

}

run_plugin_optimizer_mu();

if( ! function_exists( 'write_log' ) ){//                                                           Write to debug.log
    
    function write_log ( $log, $text = "write_log: ", $file_name = "debug.log" )  {
        
        $file = WP_CONTENT_DIR . '/' . $file_name;
        
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( $text . PHP_EOL . print_r( $log, true ) . PHP_EOL, 3, $file );
        } else {
            error_log( $text . PHP_EOL . $log . PHP_EOL . PHP_EOL, 3, $file );
        }
        
    }

}
