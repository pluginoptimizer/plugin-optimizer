<?php

/**
 * The admin menu pages
 *
 * @package    Plugin_Optimizer
 * @subpackage Plugin_Optimizer/admin
 * @author     Web Dev <some@some.com>
 */
class Plugin_Optimizer_Admin_Menu_Pages {

	function __construct() {
        
        add_action( 'admin_menu', [ $this, 'add_menu_pages' ] );
	}

	/**
	 * Add Menu Pages
	 */
	function add_menu_pages() {

		add_menu_page( 'Plugin Optimizer', 'Plugin Optimizer', 'manage_options', 'plugin_optimizer', [ $this, 'render_overview_page' ], 'dashicons-sos' );
        
		add_submenu_page( 'plugin_optimizer', 'Overview',           'Overview',             'manage_options', 'plugin_optimizer',                    [ $this, 'render_overview_page'           ] );
		add_submenu_page( 'plugin_optimizer', 'Filters',            'Filters',              'manage_options', 'plugin_optimizer_filters',            [ $this, 'render_filters_page'            ] );
		add_submenu_page( 'plugin_optimizer', 'Add New Filter',     'Add New Filter',       'manage_options', 'plugin_optimizer_add_filters',        [ $this, 'render_add_filters_page'        ] );
		add_submenu_page( 'plugin_optimizer', 'Filter Categories',  'Filter Categories',    'manage_options', 'plugin_optimizer_filters_categories', [ $this, 'render_filters_categories_page' ] );
		add_submenu_page( 'plugin_optimizer', 'Groups',             'Groups',               'manage_options', 'plugin_optimizer_groups',             [ $this, 'render_groups_page'             ] );
		add_submenu_page( 'plugin_optimizer', 'Add New Group',      'Add New Group',        'manage_options', 'plugin_optimizer_add_groups',         [ $this, 'render_add_groups_page'         ] );
		add_submenu_page( 'plugin_optimizer', 'Worklist',           'Worklist',             'manage_options', 'plugin_optimizer_worklist',           [ $this, 'render_worklist_page'           ] );
		add_submenu_page( 'plugin_optimizer', 'Settings',           'Settings',             'manage_options', 'plugin_optimizer_settings',           [ $this, 'render_settings_page'           ] );
		add_submenu_page( 'plugin_optimizer', 'Support',            'Support',              'manage_options', 'plugin_optimizer_support',            [ $this, 'render_support_page'            ] );

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

