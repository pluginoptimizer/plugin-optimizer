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

		if ( is_admin() ) {
			return $active_plugins;
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
		$current_url   = get_home_url() . trim( $_SERVER["REQUEST_URI"] );
		if(is_single()){
			print_r('Post');
		}
		$posts         = get_posts( array(
			'post_type'   => 'sos_filter',
			'numberposts' => - 1,
		) );

		foreach ( $posts as $post ) {

			$selected_pages = get_post_meta( $post->ID, 'selected_page', true );

			if ( is_array( $selected_pages ) ) {
				foreach ( $selected_pages as $selected_page ) {
					if ( $selected_page == $current_url ) {
						$block_plugins = array_merge( $block_plugins, get_post_meta( $post->ID, 'block_value_plugins', true ) );
					}
				}
			} else {
				if ( $selected_pages == $current_url ) {
					$block_plugins = array_merge( $block_plugins, get_post_meta( $post->ID, 'block_value_plugins', true ) );
				}
			}

		}

		return $block_plugins;
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