<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    PluginOptimizer
 * @subpackage PluginOptimizer/admin
 * @author     Simple Online Systems <admin@simpleonlinesystems.com>
 */

class SOSPO_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
        
        $this->load_hooks();
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 *
	 * @access   private
	 */
	function load_hooks() {

		add_filter( 'admin_body_class',             [ $this, 'mark_admin_body_class'    ] );

		add_action( 'admin_enqueue_scripts',        [ $this, 'enqueue_styles'           ] );
		add_action( 'admin_enqueue_scripts',        [ $this, 'enqueue_scripts'          ] );
        
		add_action( 'init',                         [ $this, 'register_post_types'      ] );
		add_action( 'init',                         [ $this, 'register_taxonomies'      ] );
        
		add_action( 'in_admin_header',              [ $this, 'disable_all_notice_nags'  ] );
        
		add_action( 'save_post_page',               [ $this, 'add_item_to_worklist'     ] );
		add_action( 'save_post_post',               [ $this, 'add_item_to_worklist'     ] );
		add_action( 'admin_bar_menu',               [ $this, 'add_plugin_in_admin_bar'  ], 100 );

		add_action( 'upgrader_process_complete',    [ $this, 'do_after_plugin_update'   ], 10, 2 );

	}
    
    
	function do_after_plugin_update(  $wp_upgrader_object, $options ){
        
        // https://developer.wordpress.org/reference/hooks/upgrader_process_complete/
        
        // sospo_mu_plugin()->write_log( $wp_upgrader_object,  "do_afet_plugin_update-wp_upgrader_object"  );
        // sospo_mu_plugin()->write_log( $options,             "do_afet_plugin_update-options"             );
        
        // Array
        // (
            // [action] => update
            // [type] => plugin
            // [bulk] => 1
            // [plugins] => Array
                // (
                    // [0] => wordpress-beta-tester/wp-beta-tester.php
                // )
        // )
        
        // ----
        
        // check if we need to convert our post types from the old names
        
        global $wpdb;
        
        $table = $wpdb->prefix . "posts";
        
        $query = "
            SELECT      DISTINCT( post_type )
            FROM        $table
            WHERE       post_type IN ( 'sos_filter', 'sos_group', 'sos_work' )
        ";
        
        $old_post_types = $wpdb->get_col( $query );
        
        if( ! empty( $old_post_types ) ){
            
            $wpdb->query("UPDATE  " . $wpdb->prefix . "posts         SET post_type = 'plgnoptmzr_filter'     WHERE post_type = 'sos_filter';");
            $wpdb->query("UPDATE  " . $wpdb->prefix . "posts         SET post_type = 'plgnoptmzr_group'      WHERE post_type = 'sos_group';");
            $wpdb->query("UPDATE  " . $wpdb->prefix . "posts         SET post_type = 'plgnoptmzr_work'       WHERE post_type = 'sos_work';");
            $wpdb->query("UPDATE  " . $wpdb->prefix . "term_taxonomy SET taxonomy  = 'plgnoptmzr_categories' WHERE taxonomy LIKE '_ategories_filters' OR taxonomy LIKE 'plgnoptmzr%tegories';");
            
        }
        
        
        // put the MU plugin in place
        
        if( ! file_exists( WPMU_PLUGIN_DIR ) ){
            
            mkdir( WPMU_PLUGIN_DIR );
            chmod( WPMU_PLUGIN_DIR, 0755 );
        }

        copy( dirname( __DIR__ ) . '/includes/class-po-mu.php', WPMU_PLUGIN_DIR . '/class-po-mu.php' );
        
        
        // rewrite rules
        
        flush_rewrite_rules( false );// false = soft
        
	}

    
	function mark_admin_body_class( $classes ){
        
        if( function_exists("sospo_mu_plugin") && ( count( sospo_mu_plugin()->blocked_plugins ) >= 1 || get_option("po_should_alphabetize_menu") == "1" ) ){
            $classes .= ' po_is_blocking_plugins ';
        }
        
        if( ! empty( $_GET["po_original_menu"] ) && $_GET["po_original_menu"] == "get" ){
            $classes .= ' po_is_recreating_menu ';
        }
        
        return $classes;
	}


	/**
	 * Register the stylesheets for the admin area.
	 */
	function enqueue_styles() {
        
		wp_enqueue_style( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'css/po-admin-public.css', array(), $this->version, 'all' );
        
		if ( stripos( $_SERVER["QUERY_STRING"], "plugin_optimizer" ) ) {
            
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/po-admin.css', array(), $this->version, 'all' );
            wp_enqueue_style( $this->plugin_name . '-simplebar', plugin_dir_url( __FILE__ ) . 'css/simplebar.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	function enqueue_scripts() {
        
        $array = array(
            'admin_url' => admin_url(),
            'ajax_url'  => admin_url( 'admin-ajax.php' ),
            'home_url'  => home_url(),
            'user_id'   => get_current_user_id(),
        );
        
        if( function_exists("sospo_mu_plugin") && ( count( sospo_mu_plugin()->blocked_plugins ) >= 1 || get_option("po_should_alphabetize_menu") == "1" ) ){
            
            $original_menu = get_option("plgnoptmzr_original_menu");
            
            if( $original_menu ){
                
                $array["original_menu"] = $original_menu;
            }
            
        }
        
        if( function_exists("sospo_mu_plugin") && count( sospo_mu_plugin()->blocked_plugins ) >= 1 ){
            
            $topbar_menu   = get_option("plgnoptmzr_topbar_menu");
            $new_posts     = get_option("plgnoptmzr_new_posts");
            
            if( $topbar_menu ){
                
                $array["topbar_menu"] = $topbar_menu;
            }
            
            if( $new_posts ){
                
                $array["new_posts"] = $new_posts;
            }
            
        }
        
        // are we getting the original menu
        if( ! empty( $_GET["po_original_menu"] ) && $_GET["po_original_menu"] == "get" ){
            
            $version  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/po-admin-menu-get.js' ));
            wp_register_script( $this->plugin_name . "_menu_get", plugin_dir_url( __FILE__ ) . 'js/po-admin-menu-get.js', array( 'jquery' ), $version, false );
            wp_enqueue_script(  $this->plugin_name . "_menu_get" );
            
            if( ! empty( $_GET["redirect_to"] ) ){
                
                $array["redirect_to"] = esc_url_raw( $_GET["redirect_to"] );
            }
            
            if( get_option("po_should_alphabetize_menu") === "1" ){
                
                $array["alphabetize_menu"] = true;
            }
            
        // or are we fixing the current menu
        } else {
            
            $version  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/po-admin-menu-fix.js' ));
            wp_register_script( $this->plugin_name . "_menu_fix", plugin_dir_url( __FILE__ ) . 'js/po-admin-menu-fix.js', array( 'jquery' ), $version, false );
            wp_enqueue_script(  $this->plugin_name . "_menu_fix" );
            
        }
        
        $version  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/simplebar.min.js' ));
		wp_register_script( $this->plugin_name . '-simplebar', plugin_dir_url( __FILE__ ) . 'js/simplebar.min.js', array( 'jquery' ), $version, true );
		wp_enqueue_script(  $this->plugin_name . '-simplebar' );
        
        $version  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/po-admin.js' ));
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/po-admin.js', array( 'jquery' ), $version, true );
		wp_localize_script( $this->plugin_name, 'po_object', $array );
		wp_enqueue_script(  $this->plugin_name );

	}


	function check_memory_usage() {
		return function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage() / 1024 / 1024, 2 ) : 0;
	}

	/**
	 * Add Admin-Bar Pages
	 */
	function add_plugin_in_admin_bar( $wp_admin_bar ) {
        
        $current_url = sospo_mu_plugin()->current_full_url;
        
        // Main top menu item
		$wp_admin_bar->add_menu( array(
			'id'    => 'plugin_optimizer',
			'title' => '<span class="sos-icon"></span> Plugin Optimizer | Memory used: ' . $this->check_memory_usage() . ' Mb<span class="sos-speed"></span>',
			'href'  => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_settings' ) ),
		) );
        
        
        // Worklist
		// $wp_admin_bar->add_menu( array(
			// 'parent' => 'plugin_optimizer',
			// 'id'     => 'plugin_optimizer_worklist',
			// 'title'  => 'Worklist (' . wp_count_posts( 'plgnoptmzr_work' )->publish . ')',
			// 'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_worklist' ) ),
		// ) );


        // Recreate the menu
		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_recreate_the_menu',
			'title'  => 'Recreate the menu',
            'href'   => $current_url . ( strpos( $current_url, '?' ) !== false ? '&' : '?' ) . 'po_original_menu=get&redirect_to=' . urlencode( $current_url ),
		) );

        // Blocked Plugins
		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_blocked_plugins',
			'title'  => 'Blocked Plugins (' . count( sospo_mu_plugin()->blocked_plugins ) . ')',
		) );

		foreach ( sospo_mu_plugin()->get_names_list( "blocked_plugins" ) as $plugin_path => $plugin_name) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'plugin_optimizer_blocked_plugins',
				'id'     => 'plugin_optimizer_blocked_plugin_' . $plugin_path,
				'title'  => $plugin_name,
			) );
		}


        // Running Plugins
		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_running_plugins',
			'title'  => 'Running Plugins (' . count( sospo_mu_plugin()->filtered_active_plugins ) . ')',
		) );

		foreach ( sospo_mu_plugin()->get_names_list( "filtered_active_plugins" ) as $plugin_path => $plugin_name) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'plugin_optimizer_running_plugins',
				'id'     => 'plugin_optimizer_running_plugin_' . $plugin_path,
				'title'  => $plugin_name,
			) );
		}
        
        if( sospo_mu_plugin()->is_po_default_page ){
            $wp_admin_bar->add_menu( array(
                'parent' => 'plugin_optimizer',
                'id'     => 'plugin_optimizer_default_page',
                'title'  => 'We are on a PO default page.',
            ) );
        }
        
        if( ! sospo_mu_plugin()->is_being_filtered ){
            $wp_admin_bar->add_menu( array(
                'parent' => 'plugin_optimizer',
                'id'     => 'plugin_optimizer_being_filtered',
                'title'  => 'This page is not being filtered.',
            ) );
        }
        
        if( ! empty( sospo_mu_plugin()->filters_in_use ) ){
            
            $wp_admin_bar->add_menu( array(
                'parent' => 'plugin_optimizer',
                'id'     => 'plugin_optimizer_filters_in_use',
                'title'  => 'Filters in use: ' . count( sospo_mu_plugin()->filters_in_use ),
            ) );
            
            foreach ( sospo_mu_plugin()->filters_in_use as $filter_id => $filter_name) {
                $wp_admin_bar->add_menu( array(
                    'parent' => 'plugin_optimizer_filters_in_use',
                    'id'     => 'plugin_optimizer_filter_in_use_' . $filter_id,
                    'title'  => $filter_name,
                    'href'   => admin_url('admin.php?page=plugin_optimizer_add_filters&filter_id=' . $filter_id ),
                ) );
            }
            
        }
        
        // Temp turn filters off
        if( sospo_mu_plugin()->is_being_filtered ){
            $wp_admin_bar->add_menu( array(
                'parent' => 'plugin_optimizer',
                'id'     => 'plugin_optimizer_unfiltered_page',
                'title'  => 'Visit the unfiltered page',
                'href'   => $current_url . ( strpos( $current_url, '?' ) !== false ? '&' : '?' ) . 'disable_po=yes',
            ) );
        }

	}

	/**
	 * Register all post types
	 */
	function register_post_types() {
		/**
		 * Register filter for page
		 */
		register_post_type( 'plgnoptmzr_filter', array(
			'label'         => null,
			'labels'        => array(
				'name'               => 'Filters',
				'singular_name'      => 'Filter',
				'add_new'            => 'Add Filter',
				'add_new_item'       => 'Added Filter',
				'edit_item'          => 'Edit Filter',
				'new_item'           => 'New Filter',
				'view_item'          => 'View Filter',
				'search_items'       => 'Search Filter',
				'not_found'          => 'Not found',
				'not_found_in_trash' => 'Not found in trash',
				'parent_item_colon'  => '',
				'menu_name'          => 'Filters',
			),
			'description'   => 'Filter for your customers',
			'public'        => true,
			'show_in_menu'  => false,
			// 'show_in_admin_bar'   => null,
			'show_in_rest'  => null,
			'rest_base'     => null,
			'menu_position' => 6,
			'menu_icon'     => 'dashicons-forms',
			'hierarchical'  => false,
			'supports'      => [ 'title' ],
			'taxonomies'    => [],
			'has_archive'   => false,
			'rewrite'       => true,
			'query_var'     => true,
		) );

		/**
		 * Register group for plugins
		 */
		register_post_type( 'plgnoptmzr_group', array(
			'label'         => null,
			'labels'        => array(
				'name'               => 'Groups plugins',
				'singular_name'      => 'Group plugins',
				'add_new'            => 'Add Group plugins',
				'add_new_item'       => 'Added Group plugins',
				'edit_item'          => 'Edit Group plugins',
				'new_item'           => 'New Group plugins',
				'view_item'          => 'View Group plugins',
				'search_items'       => 'Search Group plugins',
				'not_found'          => 'Not found',
				'not_found_in_trash' => 'Not found in trash',
				'parent_item_colon'  => '',
				'menu_name'          => 'Groups plugins',
			),
			'description'   => 'Group plugins for your customers',
			'public'        => true,
			'show_in_menu'  => false,
			// 'show_in_admin_bar'   => null,
			'show_in_rest'  => null,
			'rest_base'     => null,
			'menu_position' => 6,
			'menu_icon'     => 'dashicons-tickets',
			'hierarchical'  => false,
			'supports'      => [ 'title' ],
			'taxonomies'    => [],
			'has_archive'   => false,
			'rewrite'       => true,
			'query_var'     => true,
		) );

		/**
		 * Register work for worklist
		 */
		register_post_type( 'plgnoptmzr_work', array(
			'label'         => null,
			'labels'        => array(
				'name'               => 'Works',
				'singular_name'      => 'Work',
				'add_new'            => 'Add Work',
				'add_new_item'       => 'Added Work',
				'edit_item'          => 'Edit Work',
				'new_item'           => 'New Work',
				'view_item'          => 'View Work',
				'search_items'       => 'Search Work',
				'not_found'          => 'Not found',
				'not_found_in_trash' => 'Not found in trash',
				'parent_item_colon'  => '',
				'menu_name'          => 'Works',
			),
			'description'   => 'Items that are created after activating the plugin, or creating a page or post and that are recorded in a Worklist',
			'public'        => true,
			'show_in_menu'  => false,
			// 'show_in_admin_bar'   => null,
			'show_in_rest'  => null,
			'rest_base'     => null,
			'menu_position' => 6,
			'menu_icon'     => 'dashicons-editor-paste-word',
			'hierarchical'  => false,
			'supports'      => [ 'title' ],
			'taxonomies'    => [],
			'has_archive'   => false,
			'rewrite'       => true,
			'query_var'     => true,
		) );

	}

	/**
	 * Register all taxonomies
	 */
	function register_taxonomies() {

		register_taxonomy( 'plgnoptmzr_categories', array( 'plgnoptmzr_filter' ), array(
			'hierarchical' => true,
			'labels'       => array(
				'name'              => _x( 'Categories', 'taxonomy general name' ),
				'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
				'search_items'      => __( 'Search Categories' ),
				'all_items'         => __( 'All Categories' ),
				'parent_item'       => __( 'Parent Category' ),
				'parent_item_colon' => __( 'Parent Category:' ),
				'edit_item'         => __( 'Edit Category' ),
				'update_item'       => __( 'Update Category' ),
				'add_new_item'      => __( 'Create Category' ),
				'new_item_name'     => __( 'New Category Name' ),
				'menu_name'         => __( 'Categories' ),
			),
			'show_ui'       => true,
			'query_var'     => true,
			'default_term'  => 'Uncategorized',
		) );

	}

	/**
	 * Disable the dreadful nags
	 */
	function disable_all_notice_nags() {
        
        $screen = get_current_screen();
        
        if( $screen->base != 'toplevel_page_plugin_optimizer' && strpos( $screen->base, "plugin-optimizer_page_plugin_optimizer" ) === false ){
            return;
        }

        // sospo_mu_plugin()->write_log( $screen->base, "disable_all_notice_nags-screen-base" );
        
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
        
	}

	/**
	 * Creating a work after publishing a post or page
	 */
	function add_item_to_worklist( $post_id ) {

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( get_post( $post_id )->post_status != 'publish' ) {
			return;
		}

		$title_work = 'Add filter to ' . get_post( $post_id )->post_title;
		$post_link  = get_permalink( $post_id );

		global $wpdb;

		$table_name = $wpdb->get_blog_prefix() . 'post_links';

		$wpdb->insert(
            $table_name,
            array(
                'audit'           => 1,
                'name_post'       => get_post( $post_id )->post_title,
                'type_post'       => get_post_type( $post_id ),
                'permalinks_post' => get_permalink( $post_id ),
            ),
            array( '%d', '%s', '%s', '%s' )
        );

		$post_data = array(
			'post_title'  => $title_work,
			'post_type'   => 'plgnoptmzr_work',
			'post_status' => 'publish',
			'post_author' => 1,
		);

		$post_id = wp_insert_post( $post_data, true );
		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( $post_id->get_error_message() );
		}
		add_post_meta( $post_id, 'post_link', $post_link );
	}

}

