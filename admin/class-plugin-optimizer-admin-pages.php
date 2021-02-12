<?php

/**
 * The admin menu pages
 *
 * @package    Plugin_Optimizer
 * @subpackage Plugin_Optimizer/admin
 * @author     Web Dev <some@some.com>
 */
class Plugin_Optimizer_Admin_Menu_Pages {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power the plugin.
	 *
	 * @access   protected
	 * @var      Plugin_Optimizer_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	function __construct() {
        
		$this->loader = new Plugin_Optimizer_Loader();

        $this->loader->add_action( 'admin_menu', $this, 'add_menu_pages' );
	}

	/**
	 * Add Menu Pages
	 */
	function add_menu_pages() {

		add_menu_page( 'Plugin Optimizer', 'Plugin Optimizer', 'manage_options', 'plugin_optimizer', array( $this, 'render_overview_page' ), 'dashicons-sos' );
        
		add_submenu_page( 'plugin_optimizer', 'Overview',           'Overview',             'manage_options', 'plugin_optimizer',                       array( $this, 'render_overview_page'            ) );
		add_submenu_page( 'plugin_optimizer', 'Filters',            'Filters',              'manage_options', 'plugin_optimizer_filters',               array( $this, 'render_filters_page'             ) );
		add_submenu_page( 'plugin_optimizer', 'Add Filters',        'Add Filters',          'manage_options', 'plugin_optimizer_add_filters',           array( $this, 'render_add_filters_page'         ) );
		add_submenu_page( 'plugin_optimizer', 'Filters Categories', 'Filters Categories',   'manage_options', 'plugin_optimizer_filters_categories',    array( $this, 'render_filters_categories_page'  ) );
		add_submenu_page( 'plugin_optimizer', 'Groups plugin',      'Groups plugin',        'manage_options', 'plugin_optimizer_groups',                array( $this, 'render_groups_page'              ) );
		add_submenu_page( 'plugin_optimizer', 'Add groups plugin',  'Add groups plugin',    'manage_options', 'plugin_optimizer_add_groups',            array( $this, 'render_add_groups_page'          ) );
		add_submenu_page( 'plugin_optimizer', 'Worklist',           'Worklist',             'manage_options', 'plugin_optimizer_worklist',              array( $this, 'render_worklist_page'            ) );
		add_submenu_page( 'plugin_optimizer', 'Settings',           'Settings',             'manage_options', 'plugin_optimizer_settings',              array( $this, 'render_settings_page'            ) );
		add_submenu_page( 'plugin_optimizer', 'Support',            'Support',              'manage_options', 'plugin_optimizer_support',               array( $this, 'render_support_page'             ) );

	}


	function render_overview_page() {
		include 'pages/page-overview.php';
	}

	function render_filters_page() {
		include 'pages/page-filters.php';
	}

	function render_add_filters_page() {
		include 'pages/page-add-filters.php';
	}

	function render_filters_categories_page() {
		include 'pages/page-categories.php';
	}

	function render_groups_page() {
		include 'pages/page-groups.php';
	}

	function render_add_groups_page() {
		include 'pages/page-add-groups.php';
	}

	function render_settings_page() {
		include 'pages/page-settings-template.php';
	}

	function render_worklist_page() {
		include 'pages/page-worklist.php';
	}

	function render_support_page() {
		include 'pages/page-support.php';
	}

}

