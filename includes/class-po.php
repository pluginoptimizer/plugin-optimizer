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
 * @package    Plugin_Optimizer
 * @subpackage Plugin_Optimizer/includes
 * @author     Web Dev <some@some.com>
 */

class Plugin_Optimizer {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access   protected
	 * @var      Plugin_Optimizer_Loader $loader Maintains and registers all hooks for the plugin.
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
		$this->plugin_name = 'plugin-optimizer';

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
	 * - Plugin_Optimizer_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Optimizer_i18n. Defines internationalization functionality.
	 * - Plugin_Optimizer_Admin. Defines all hooks for the admin area.
	 * - Plugin_Optimizer_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function load_dependencies() {

		
		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-po-loader.php';

		
		// The class responsible for defining internationalization functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-po-i18n.php';

		
		// The class responsible for defining helper functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-po-helper.php';

		
		// The class responsible for defining helper functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-po-admin-helper.php';

		
		// The class responsible for defining all actions that occur in the admin area.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-po-admin.php';

		
		// The class responsible for menu pages of the admin area.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-po-admin-pages.php';

		
		// The class responsible for defining all Ajax actions that occur in the admin area.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-po-admin-ajax.php';

		
		// The class responsible for defining all actions that occur in the public-facing side of the site.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-po-public.php';

		$this->loader = new Plugin_Optimizer_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Optimizer_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Plugin_Optimizer_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Plugin_Optimizer_Admin( $this->get_plugin_name(), $this->get_version() );
		$admin_ajax   = new Plugin_Optimizer_Ajax();
		$menu_pages   = new Plugin_Optimizer_Admin_Menu_Pages();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        
		$this->loader->add_action( 'script_loader_tag',     $plugin_admin, 'add_type_attribute', 10, 3 );
        
		$this->loader->add_action( 'init',                  $plugin_admin, 'register_post_types' );
		$this->loader->add_action( 'init',                  $plugin_admin, 'register_taxonomies' );
		$this->loader->add_action( 'add_meta_boxes',        $plugin_admin, 'register_meta_boxes' );
        
		$this->loader->add_action( 'save_post_sos_filter',  $plugin_admin, 'save_filter_options' );
		$this->loader->add_action( 'save_post_sos_group',   $plugin_admin, 'save_group_options' );
        
		$this->loader->add_action( 'save_post_page',        $plugin_admin, 'add_item_to_worklist' );
		$this->loader->add_action( 'save_post_post',        $plugin_admin, 'add_item_to_worklist' );
		$this->loader->add_action( 'admin_bar_menu',        $plugin_admin, 'add_plugin_in_admin_bar', 100 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Plugin_Optimizer_Public( $this->get_plugin_name(), $this->get_version() );

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
	 * @return    Plugin_Optimizer_Loader    Orchestrates the hooks of the plugin.
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
