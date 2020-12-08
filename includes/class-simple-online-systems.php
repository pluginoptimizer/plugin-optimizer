<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Simple_Online_Systems
 * @subpackage Simple_Online_Systems/includes
 * @author     Web Dev <some@some.com>
 */
class Simple_Online_Systems {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access   protected
	 * @var      Simple_Online_Systems_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 */
	public function __construct() {
		if ( defined( 'SIMPLE_ONLINE_SYSTEMS_VERSION' ) ) {
			$this->version = SIMPLE_ONLINE_SYSTEMS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'simple-online-systems';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Simple_Online_Systems_Loader. Orchestrates the hooks of the plugin.
	 * - Simple_Online_Systems_i18n. Defines internationalization functionality.
	 * - Simple_Online_Systems_Admin. Defines all hooks for the admin area.
	 * - Simple_Online_Systems_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-online-systems-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-online-systems-i18n.php';

		/**
		 * The class responsible for defining helper functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-online-systems-helper.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-simple-online-systems-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-simple-online-systems-public.php';

		$this->loader = new Simple_Online_Systems_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Simple_Online_Systems_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Simple_Online_Systems_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Simple_Online_Systems_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'script_loader_tag', $plugin_admin, 'add_type_attribute', 10, 3 );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_pages' );

		$this->loader->add_action( 'init', $plugin_admin, 'register_post_types' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_taxonomies' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'register_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_filter_options' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_group_options' );
		$this->loader->add_action( 'save_post_page', $plugin_admin, 'add_item_to_worklist' );
		$this->loader->add_action( 'save_post_post', $plugin_admin, 'add_item_to_worklist' );
		$this->loader->add_action( 'activated_plugin', $plugin_admin, 'add_item_to_worklist_active_plugins', 10, 1 );
		$this->loader->add_action( 'admin_bar_menu', $plugin_admin, 'add_plugin_in_admin_bar', 100 );


		$this->loader->add_action( 'wp_ajax_sos_add_plugin_to_filter', $plugin_admin, 'ajax_add_plugin_to_filter' );
		$this->loader->add_action( 'wp_ajax_sos_search_pages', $plugin_admin, 'ajax_search_pages' );
		$this->loader->add_action( 'wp_ajax_sos_search_filters', $plugin_admin, 'ajax_search_filters' );
		$this->loader->add_action( 'wp_ajax_sos_search_elements', $plugin_admin, 'ajax_search_elements' );
		$this->loader->add_action( 'wp_ajax_sos_all_elements', $plugin_admin, 'ajax_all_elements' );
		$this->loader->add_action( 'wp_ajax_sos_trash_elements', $plugin_admin, 'ajax_trash_elements' );
		$this->loader->add_action( 'wp_ajax_sos_delete_elements', $plugin_admin, 'ajax_delete_elements' );
		$this->loader->add_action( 'wp_ajax_sos_publish_elements', $plugin_admin, 'ajax_publish_elements' );
		$this->loader->add_action( 'wp_ajax_sos_count_elements', $plugin_admin, 'ajax_count_elements' );
		$this->loader->add_action( 'wp_ajax_sos_add_group_plugins', $plugin_admin, 'ajax_add_group_plugins' );
		$this->loader->add_action( 'wp_ajax_sos_create_category', $plugin_admin, 'ajax_create_category' );
		$this->loader->add_action( 'wp_ajax_sos_create_cat_subcat', $plugin_admin, 'ajax_create_cat_subcat' );
		$this->loader->add_action( 'wp_ajax_sos_delete_category', $plugin_admin, 'ajax_delete_category' );
		$this->loader->add_action( 'wp_ajax_sos_check_name_group', $plugin_admin, 'ajax_check_name_group' );
		$this->loader->add_action( 'wp_ajax_sos_change_plugins_to_filter', $plugin_admin, 'ajax_change_plugins_to_filter' );
		$this->loader->add_action( 'wp_ajax_sos_add_category_to_filter', $plugin_admin, 'ajax_add_category_to_filter' );
		$this->loader->add_action( 'wp_ajax_sos_transition_viewed', $plugin_admin, 'ajax_transition_viewed' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Simple_Online_Systems_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Simple_Online_Systems_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

}
