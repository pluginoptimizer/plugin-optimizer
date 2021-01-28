<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Plugin_Optimizer
 * @subpackage Plugin_Optimizer/admin
 * @author     Web Dev <some@some.com>
 */
class Plugin_Optimizer_Admin {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'css/plugin-optimizer-admin-public.css', array(), $this->version, 'all' );
		if ( stripos( $_SERVER["QUERY_STRING"], "plugin_optimizer" ) || stripos( $_SERVER["QUERY_STRING"], "action=edit" ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-optimizer-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '_bootstrap', plugin_dir_url( __FILE__ ) . 'css/plugin-optimizer-admin-bootstrap.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-optimizer-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'plugin_optimizer_groups', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name );

	}

	/**
	 * Add type module for js
	 */
	public function add_type_attribute( $tag, $handle, $src ) {
		// if not your script, do nothing and return original $tag
		if ( $this->plugin_name !== $handle ) {
			return $tag;
		}
		// change the script tag by adding type="module" and return it.
		$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';

		return $tag;
	}

	/*public function add_type_attribute_worklist($tag, $handle, $src) {
		// if not your script, do nothing and return original $tag
		if ( 'worklist' !== $handle ) {
			return $tag;
		}
		// change the script tag by adding type="module" and return it.
		$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
		return $tag;
	}*/

	/**
	 * Add Menu Pages
	 */
	public function add_menu_pages() {

		add_menu_page( 'Plugin Optimizer', 'Plugin Optimizer', 'manage_options', 'plugin_optimizer_overview', array(
			$this,
			'render_overview_page'
		), 'dashicons-sos' );
		add_submenu_page( 'plugin_optimizer_overview', 'Overview', 'Overview', 'manage_options', 'plugin_optimizer_overview', array(
			$this,
			'render_overview_page'
		) );
		add_submenu_page( 'plugin_optimizer_overview', 'Filters', 'Filters', 'manage_options', 'plugin_optimizer_filters', array(
			$this,
			'render_filters_page'
		) );
		add_submenu_page( 'plugin_optimizer_overview', 'Add Filters', 'Add Filters', 'manage_options', 'plugin_optimizer_add_filters', array(
			$this,
			'render_add_filters_page'
		) );
		add_submenu_page( 'plugin_optimizer_overview', 'Filters Categories', 'Filters Categories', 'manage_options', 'plugin_optimizer_filters_categories', array(
			$this,
			'render_filters_categories_page'
		) );
		add_submenu_page( 'plugin_optimizer_overview', 'Groups plugin', 'Groups plugin', 'manage_options', 'plugin_optimizer_groups', array(
			$this,
			'render_groups_page'
		) );
		add_submenu_page( 'plugin_optimizer_overview', 'Add groups plugin', 'Add groups plugin', 'manage_options', 'plugin_optimizer_add_groups', array(
			$this,
			'render_add_groups_page'
		) );
		add_submenu_page( 'plugin_optimizer_overview', 'Worklist', 'Worklist', 'manage_options', 'plugin_optimizer_worklist', array(
			$this,
			'render_worklist_page'
		) );
		add_submenu_page( 'plugin_optimizer_overview', 'Settings', 'Settings', 'manage_options', 'plugin_optimizer_settings', array(
			$this,
			'render_settings_page'
		) );
		add_submenu_page( 'plugin_optimizer_overview', 'Support', 'Support', 'manage_options', 'plugin_optimizer_support', array(
			$this,
			'render_support_page'
		) );

	}


	public function render_overview_page() {
		include 'partials/page-overview.php';
	}

	public function render_filters_page() {
		include 'partials/page-filters.php';
	}

	public function render_add_filters_page() {
		include 'partials/page-add-filters.php';
	}

	public function render_filters_categories_page() {
		include 'partials/page-categories.php';
	}

	public function render_groups_page() {
		include 'partials/page-groups.php';
	}

	public function render_add_groups_page() {
		include 'partials/page-add-groups.php';
	}

	public function render_settings_page() {
		include 'partials/page-settings-template.php';
	}

	public function render_worklist_page() {
		include 'partials/page-worklist.php';
	}

	public function render_support_page() {
		include 'partials/page-support.php';
	}

	function check_memory_usage() {
		return function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage() / 1024 / 1024, 2 ) : 0;
	}

	/**
	 * Add Admin-Bar Pages
	 */
	public function add_plugin_in_admin_bar( $wp_admin_bar ) {

		$wp_admin_bar->add_menu( array(
			'id'    => 'plugin_optimizer',
			'title' => '<span class="sos-icon"></span> Plugin Optimizer | Memory used: ' . $this->check_memory_usage() . ' Mb<span class="sos-speed"></span>',
			'href'  => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_settings' ) ),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_overview',
			'title'  => 'Overview',
			'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_overview' ) ),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_filters',
//			'title'  => 'Filters (' . wp_count_posts('sos_filter')->publish . ')',
			'title'  => 'Filters',
			'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_filters' ) ),
		) );

		$posts = get_posts( array(
			'post_type'   => 'sos_filter',
			'numberposts' => - 1,
		) );
		foreach ( $posts as $post ) {
			if ( get_permalink( substr( get_post_meta( $post->ID, 'selected_page', true ), - 1 ) ) == get_home_url() . trim( $_SERVER["REQUEST_URI"] ) ) {
				$wp_admin_bar->add_menu( array(
					'parent' => 'plugin_optimizer_filters',
					'id'     => 'plugin_optimizer_filters' . $post->post_title,
					'title'  => $post->post_title,
					'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_filters&filter_title=' . $post->post_title ) ),
				) );
			}
		}

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_filters_categories',
			'title'  => 'Filters Categories (' . wp_count_terms( 'category' ) . ')',
			'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_filters_categories' ) ),
		) );

		$categories = get_categories( [
			'taxonomy'   => 'category',
			'type'       => 'sos_filter',
			'parent'     => 0,
			'hide_empty' => 0,
		] );

		if ( $categories ) {
			foreach ( $categories as $cat ) {
				$wp_admin_bar->add_menu( array(
					'parent' => 'plugin_optimizer_filters_categories',
					'id'     => 'plugin_optimizer_filters_categories_' . $cat->cat_name,
					'title'  => $cat->cat_name,
					'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_filters_categories' ) ),
				) );

				$subcategories = get_categories( array(
					'parent'     => $cat->cat_ID,
					'taxonomy'   => 'category',
					'type'       => 'sos_filter',
					'hide_empty' => 0,
				) );
				if ( $subcategories ) {
					foreach ( $subcategories as $subcategory ) {
						$wp_admin_bar->add_menu( array(
							'parent' => 'plugin_optimizer_filters_categories',
							'id'     => 'plugin_optimizer_filters_categories_' . $subcategory->cat_name,
							'title'  => $subcategory->cat_name,
							'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_filters_categories' ) ),
						) );
					}
				}
			}
		}

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_groups',
			'title'  => 'Groups (' . wp_count_posts( 'sos_group' )->publish . ')',
			'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_groups' ) ),
		) );

		$posts = get_posts( array(
			'post_type'   => 'sos_group',
			'numberposts' => - 1,
			'meta_query'  => array(
				array(
					'key'   => 'group_parents',
					'value' => 'None'
				)
			),
		) );
		foreach ( $posts as $post ) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'plugin_optimizer_groups',
				'id'     => 'plugin_optimizer_groups' . $post->post_title,
				'title'  => $post->post_title,
				'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_groups&group_title=' . $post->post_title ) ),
			) );
			$posts_chidrens = get_posts( array(
				'post_type'   => 'sos_group',
				'numberposts' => - 1,
				'meta_query'  => array(
					array(
						'key'   => 'group_parents',
						'value' => $post->post_title,
					)
				),
			) );


			if ( $posts_chidrens ) {
				foreach ( $posts_chidrens as $post_chidren ) {
					$wp_admin_bar->add_menu( array(
						'parent' => 'plugin_optimizer_groups',
						'id'     => 'plugin_optimizer_groups' . $post_chidren->post_title,
						'title'  => $post_chidren->post_title,
						'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_groups&group_title=' . $post_chidren->post_title ) ),
					) );
				}
			}
		}

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_worklist',
			'title'  => 'Worklist (' . wp_count_posts( 'sos_work' )->publish . ')',
			'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_worklist' ) ),
		) );

		$posts = get_posts( array(
			'post_type'   => 'sos_work',
			'numberposts' => - 1,
		) );
		foreach ( $posts as $post ) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'plugin_optimizer_worklist',
				'id'     => 'plugin_optimizer_worklist' . $post->post_title,
				'title'  => $post->post_title,
				'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_worklist&work_title=' . $post->post_title ) ),
			) );
		}

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_settings',
			'title'  => 'Settings',
			'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_settings' ) ),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_disable_plugins',
			'title'  => 'Disabled Plugins',
		) );

		$all_plugins        = Plugin_Optimizer_Helper::get_plugins_with_status();
		$activate_plugins   = array();
		$deactivate_plugins = array();
		foreach ( $all_plugins as $plugin ) {
			foreach ( $plugin as $key => $value ) {
				if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
					if ( $value ) {
						array_push( $activate_plugins, $plugin['name'] );
					} else {
						array_push( $deactivate_plugins, $plugin['name'] );
					}
				}
			}
		}

		$plugin_arr  = array();
		$current_url = get_home_url() . trim( $_SERVER["REQUEST_URI"] );
		$posts       = get_posts( array(
			'post_type'   => 'sos_filter',
			'numberposts' => - 1,
		) );
		foreach ( $posts as $post ) {
			$selected_pages = get_post_meta( $post->ID, 'selected_page', true );

			if ( is_array( $selected_pages ) ) {
				foreach ( $selected_pages as $selected_page ) {
					if ( $selected_page == $current_url ) {
						$plugin_arr = array_merge( $plugin_arr, get_post_meta( $post->ID, 'block_plugins', true ) );
					}
				}
			} else {
				if ( $selected_pages == $current_url ) {
					$plugin_arr = array_merge( $plugin_arr, get_post_meta( $post->ID, 'block_plugins', true ) );
				}
			}
		}

		if ( $plugin_arr ) {
			$plugin_arr = array_unique( $plugin_arr );
			foreach ( $plugin_arr as $plugin ) {
				$wp_admin_bar->add_menu( array(
					'parent' => 'plugin_optimizer_disable_plugins',
					'id'     => 'plugin_optimizer_disable_plugin_' . $plugin,
					'title'  => $plugin,
				) );
			}
		}

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_enabled_plugins',
			'title'  => 'Enabled Plugins',
		) );

		$enabled_plugins = array_diff( $activate_plugins, $plugin_arr );
		foreach ( $enabled_plugins as $enabled_plugin ) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'plugin_optimizer_enabled_plugins',
				'id'     => 'plugin_optimizer_enabled_plugins' . $enabled_plugin,
				'title'  => $enabled_plugin,
			) );
		}


//		if ( is_admin() ) {


		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer_settings',
			'id'     => 'plugin_optimizer_settings_plugin_activated',
			'title'  => 'Activate plugins (' . count( $activate_plugins ) . ')',
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer_settings',
			'id'     => 'plugin_optimizer_settings_plugin_deactivated',
			'title'  => 'Deactivate plugins (' . count( $deactivate_plugins ) . ')',
		) );


		foreach ( $activate_plugins as $activate_plugin ) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'plugin_optimizer_settings_plugin_activated',
				'id'     => 'plugin_optimizer_settings_plugin_activated' . $activate_plugin,
				'title'  => $activate_plugin,
				'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_settings&plugin_title=' . $activate_plugin ) ),
			) );
		}
//		}

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_support',
			'title'  => 'Support',
			'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_support' ) ),
		) );

	}

	/**
	 * Register all post types
	 */
	public function register_post_types() {
		/**
		 * Register filter for page
		 */
		register_post_type( 'sos_filter', array(
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
		register_post_type( 'sos_group', array(
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
		register_post_type( 'sos_work', array(
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
	public function register_taxonomies() {

		register_taxonomy( 'сategories_filters', array( 'sos_filter' ), array(
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
				'add_new_item'      => __( 'Add New Category' ),
				'new_item_name'     => __( 'New Category Name' ),
				'menu_name'         => __( 'Categories' ),
			),
			'show_ui'      => true,
			'query_var'    => true,
			'default_term'    => 'Uncategorized',
		) );

	}

	/**
	 * Register meta boxes to filter and group
	 */
	public function register_meta_boxes() {

		add_meta_box( 'sos_filter_options', 'Filter Options', array(
			$this,
			'render_filter_options'
		), array( 'sos_filter' ) );
		add_meta_box( 'sos_group_options', 'Group Options', array(
			$this,
			'render_group_options'
		), array( 'sos_group' ) );

	}

	/**
	 * Create filter
	 */
	public function ajax_add_plugin_to_filter() {
		$block_group = htmlspecialchars( $_POST['block_group_plugins'] );
		if ( $block_group !== 'None' ) {
			$block_group_plugins_get = explode( ',', htmlspecialchars( $_POST['block_group_plugins'] ) );
			$block_group_plugins     = '';
			foreach ( $block_group_plugins_get as $block_group_plugin ) {
				$posts = get_posts( array(
					'post_type'   => 'sos_group',
					's'           => $block_group_plugin,
					'numberposts' => - 1,
				) );
				foreach ( $posts as $post ) {
					$block_group_plugins .= get_post_meta( $post->ID, 'group_plugins', true ) . ', ';
				}
			}
			$block_group_plugins = substr( $block_group_plugins, 0, - 2 );

			$block_plugins = htmlspecialchars( $_POST['block_plugins'] ) . ', ' . $block_group_plugins;
			$block_plugins = explode( ', ', $block_plugins );
			$block_plugins = array_unique( $block_plugins );

			$all_plugins         = Plugin_Optimizer_Helper::get_plugins_with_status();
			$block_value_plugins = array();
			foreach ( $all_plugins as $plugin ) {
				if ( in_array( $plugin['name'], $block_plugins ) ) {
					array_push( $block_value_plugins, $plugin['file'] );
				}
			}
			$block_value_plugins = array_unique( $block_value_plugins );
		} else {
			$block_plugins       = explode( ', ', htmlspecialchars( $_POST['block_plugins'] ) );
			$block_value_plugins = explode( ', ', htmlspecialchars( $_POST['block_value_plugins'] ) );
		}

//		$post_type          = htmlspecialchars( $_POST['post_type'] );
		$pages              = htmlspecialchars( $_POST['pages'] );
		$title_filter       = htmlspecialchars( $_POST['title_filter'] );
		$type_filter        = htmlspecialchars( $_POST['type_filter'] );
		$category_filter    = htmlspecialchars( $_POST['category_filter'] );
		$category_id_filter = htmlspecialchars( $_POST['category_id_filter'] );
		$block_group        = explode( ', ', $block_group );


		$post_data = array(
			'post_title'  => $title_filter,
			'post_type'   => 'sos_filter',
			'post_status' => 'publish',
			'post_author' => 1,
		);

		$post_id = wp_insert_post( $post_data, true );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( $post_id->get_error_message() );
		}

		add_post_meta( $post_id, 'block_plugins', $block_plugins );
		add_post_meta( $post_id, 'block_group_plugins', $block_group );
		add_post_meta( $post_id, 'block_value_plugins', $block_value_plugins );
//		add_post_meta( $post_id, 'selected_post_type', $post_type );
		add_post_meta( $post_id, 'selected_page', $pages );
		add_post_meta( $post_id, 'type_filter', $type_filter );
		add_post_meta( $post_id, 'category_filter', $category_filter );
		wp_set_post_terms( $post_id, $category_id_filter, 'сategories_filters' );

		ob_start();

		$posts = get_posts( array(
			'post_type'   => 'sos_filter',
			'numberposts' => - 1,
		) );

		$this->content_filters( $posts );

		wp_send_json_success( ob_get_clean() );

	}


	/**
	 * Edit filter
	 */
	public function render_filter_options( $post ) {

		wp_nonce_field( plugin_basename( __FILE__ ), 'nonce_sos_filter_options' );

		$value_block_plugins       = get_post_meta( $post->ID, 'block_plugins', 1 );
		$value_link_block_plugins       = get_post_meta( $post->ID, 'block_value_plugins', 1 );
		$value_block_group_plugins = get_post_meta( $post->ID, 'block_group_plugins', 1 );
//		$value_selected_post_type  = get_post_meta( $post->ID, 'selected_post_type', 1 );
		$value_selected_page   = get_post_meta( $post->ID, 'selected_page', 1 );
		$value_type_filter     = get_post_meta( $post->ID, 'type_filter', 1 );
		$value_category_filter = get_post_meta( $post->ID, 'category_filter', 1 );

		?>
		<div style="display: none">
        <label for="block_plugins"> <?= "Select block plugins" ?> </label>
        <input type="text" id="block_plugins" name="block_plugins" value=" <?= implode(', ', $value_block_plugins ); ?>" size="25"/>
        <br>
        <br>

        <label for="block_link_plugins"> <?= "Select link block plugins" ?> </label>
        <input type="text" id="block_link_plugins" name="block_link_plugins" value=" <?= implode(', ', $value_link_block_plugins ); ?>" size="25"/>
        <br>
        <br>

        <label for="block_plugins"> <?= "Select block group plugins" ?> </label>
        <input type="text" id="block_group_plugins" name="block_group_plugins" value=" <?= implode(', ', $value_block_group_plugins); ?>"
               size="25"/>
        <br>
        <br>

        <!-- <label for="selected_post_type"> <?/*= "Add post type" */ ?> </label>
        <input type="text" id="selected_post_type" name="selected_post_type" value=" <?/*= $value_selected_post_type */ ?>"
               size="25"/>
        <br>
        <br>-->

        <label for="selected_page"> <?= "Add Permalinks" ?> </label>
        <input type="text" id="selected_page" name="selected_page" value=" <?= $value_selected_page ?>" size="25"/>
        <br>
        <br>

        <label for="type_filter"> <?= "Set Type" ?></label>
        <input type="text" id="type_filter" name="type_filter" value=" <?= $value_type_filter ?>" size="25"/>
        <br>
        <br>


        <label for="category_filter"> <?= "Category Type" ?></label>
        <input type="text" id="category_filter" name="category_filter" value=" <?= $value_category_filter ?>" size="25"/>
        <br>
        <br>
        </div>
        <div class="sos-wrap">
        <table>
        <thead>
        <tr>
            <th><input type="checkbox" id="check_all"></th>
                            <th>TITLE</th>
                            <th>cATEGORIES</th>
                            <th>type</th>
                            <th>permalinks</th>
                            <th>Block plugins</th>
        </tr>
        </thead>
        <tbody id="the-list">
                <tr class="block_info" id="filter-<?= $post->ID; ?>">
                    <td><input type="checkbox" id="<?= $post->ID; ?>"></td>
                    <td><?= $post->post_title; ?></td>
                    <td><?= get_post_meta( $post->ID, 'category_filter', true ); ?></td>
                    <td class="data-type-filter"><?= get_post_meta( $post->ID, 'type_filter', true ); ?></td>
                    <td class="data-link-filter"><?= get_post_meta( $post->ID, 'selected_page', true ); ?></td>
                    <td><?= implode( ', ', get_post_meta( $post->ID, 'block_plugins', true ) ); ?></td>
                </tr>
                <tr class="hidden_info">
                    <td colspan="6">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
									<?php
									$type_filter = get_post_meta( $post->ID, 'type_filter', true );
									if ( $type_filter === 'none' ):
										?>
                                        <div class="header">Permalinks</div>
                                        <div class="content-permalinks">
                                            <div class="link">
                                            <span class="data-interaction data-link" filter_id="<?= $post->ID ?>"
                                                  contenteditable>
                                            <?= get_post_meta( $post->ID, 'selected_page', true ) ?>
                                            </span>
                                            </div>
                                        </div>
									<?php

									else:
										?>
                                        <div class="header">Type</div>
                                        <div>
                                            <div class="content">
                                            <span class="data-interaction data-type" filter_id="<?= $post->ID ?>"
                                                  contenteditable>
                                                <?= $type_filter ?>
                                            </span>
                                            </div>
                                        </div>

									<?php
									endif;
									?>
                                </div>

                            </div>
                            <div class="row content-plugins">
								<?php
								$this->content_plugin_to_filter( $post );
								?>
                            </div>
                            <div class="row group-wrapper">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
											<?php
											$groups_plugins = get_post_meta( $post->ID, 'block_group_plugins', true );
											$count_groups   = 0;
											$groups         = get_posts( array(
												'post_type'   => 'sos_group',
												'numberposts' => - 1,
											) );
											if ( $groups ) {
												foreach ( $groups as $group ) {
													if ( in_array( $group->post_title, $groups_plugins ) ) {
														$count_groups ++;
													}
												}
											}
											?>
                                            groups <span
                                                    class="disabled">- Disabled: <?= $count_groups; ?>/<?= count( $groups ); ?></span>
                                        </div>
                                    </div>
                                    <div class="plugin-wrapper group-wrapper">
										<?php
										$all_plugins     = Plugin_Optimizer_Helper::get_plugins_with_status();
										$content_plugins = array();
										foreach ( $all_plugins as $plugin ) {
											foreach ( $plugin as $key => $value ) {
												if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
													$content_plugins[ $plugin['name'] ] = $plugin['file'];
												}
											}
										}

										$groups = get_posts( array(
											'post_type'   => 'sos_group',
											'numberposts' => - 1,
										) );
										if ( $groups ) :
											foreach ( $groups as $group ) :
												?>
                                                <div id="<?= $group->ID; ?>" value="<?= $post->ID; ?>"
                                                     class="content <?= in_array( $group->post_title, $groups_plugins ) ? 'block' : ''; ?> ">
                                                    <span><?= $group->post_title; ?></span>
													<?php
													$block_plugins_in_group = explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) );
													foreach ( $block_plugins_in_group as $block_plugin_in_group ) :
														?>
                                                        <div class="hidden_content content">
                                                            <span value="<?= $content_plugins[ $block_plugin_in_group ]; ?>"><?= $block_plugin_in_group; ?></span>
                                                        </div>
													<?php
													endforeach;
													?>
                                                </div>
											<?php
											endforeach;
										endif;
										?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            categories
                                        </div>
                                    </div>
                                    <div class="plugin-wrapper">
										<?php
										$this->ajax_create_category( $post );
										?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
        </tbody>
        </table>
			<?php

	}

	/**
	 * Save the edited filter
	 */
	public function save_filter_options( $post_id ) {

//		if ( ! isset( $_POST['block_plugins'] ) && ! isset( $_POST['block_group_plugins'] ) && ! isset( $_POST['selected_post_type'] ) && ! isset( $_POST['selected_page'] ) && ! isset( $_POST['type_filter'] ) ) {
		if ( ! isset( $_POST['block_plugins'] ) && ! isset( $_POST['block_group_plugins'] ) && ! isset( $_POST['selected_page'] ) && ! isset( $_POST['type_filter'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['nonce_sos_filter_options'], plugin_basename( __FILE__ ) ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$block_plugins       = explode(', ', sanitize_text_field( $_POST['block_plugins'] ));
		$block_link_plugins       = explode(', ', sanitize_text_field( $_POST['block_link_plugins'] ));
		$block_group_plugins = explode(', ', sanitize_text_field( $_POST['block_group_plugins'] ));
//		$selected_post_type  = sanitize_text_field( $_POST['selected_post_type'] );
		$selected_page   = sanitize_text_field( $_POST['selected_page'] );
		$type_filter     = sanitize_text_field( $_POST['type_filter'] );
		$category_filter = sanitize_text_field( $_POST['category_filter'] );

		update_post_meta( $post_id, 'block_plugins', $block_plugins );
		update_post_meta( $post_id, 'block_link_plugins', $block_link_plugins );
		update_post_meta( $post_id, 'block_group_plugins', $block_group_plugins );
//		update_post_meta( $post_id, 'selected_post_type', $selected_post_type );
		update_post_meta( $post_id, 'selected_page', $selected_page );
		update_post_meta( $post_id, 'type_filter', $type_filter );
		update_post_meta( $post_id, 'category_filter', $category_filter );
	}

	/**
	 * Search for pages when creating a filter
	 */
	public function ajax_search_pages() {
		ob_start();

		$posts = get_posts( array(
			'numberposts' => - 1,
			's'           => esc_attr( $_POST['keyword'] ),
			'post_type'   => 'page',
		) );

		if ( $posts ) :
			foreach ( $posts as $post ) : ?>
                <h2>
                    <a href="<?= get_permalink( $post->ID ); ?>" class="link_search_page">
						<?= $post->post_title; ?>
                    </a>
                </h2>
			<?php
			endforeach;
		else:
			?>
            <h2>
                Not Found
            </h2>
		<?php
		endif;

		wp_send_json_success( ob_get_clean() );
	}

	/**
	 * Create group
	 */
	public function ajax_add_group_plugins() {

		$title_group   = htmlspecialchars( $_POST['title_group'] );
		$group_plugins = htmlspecialchars( $_POST['group_plugins'] );

		$post_data = array(
			'post_title'  => $title_group,
			'post_type'   => 'sos_group',
			'post_status' => 'publish',
			'post_author' => 1,
		);

		$post_id = wp_insert_post( $post_data, true );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( $post_id->get_error_message() );
		}

		add_post_meta( $post_id, 'group_plugins', $group_plugins );

		ob_start();

		$posts = get_posts( array(
			'post_type'   => 'sos_group',
			'numberposts' => - 1,
			'meta_query'  => array(
				array(
					'key'   => 'group_parents',
					'value' => 'None'
				)
			),
		) );

		$this->content_groups( $posts );

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Edit group
	 */
	public function render_group_options( $post ) {

		wp_nonce_field( plugin_basename( __FILE__ ), 'nonce_sos_group_options' );

		$value_group_plugins = get_post_meta( $post->ID, 'group_plugins', 1 );

		?>

        <div style="display: none">
            <label for="block_plugins"> <?= "Select group plugins" ?> </label>
            <input type="text" id="block_plugins" name="block_plugins" value=" <?= $value_group_plugins ?>" size="25"/>
            <br>
            <br>
        </div>

		<?php

		$all_plugins        = Plugin_Optimizer_Helper::get_plugins_with_status();
		$activate_plugins   = array();
		$deactivate_plugins = array();
		foreach ( $all_plugins as $plugin ) {
			foreach ( $plugin as $key => $value ) {
				if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
					if ( $value ) {
						array_push( $activate_plugins, $plugin['name'] );
					} else {
						array_push( $deactivate_plugins, $plugin['name'] );
					}
				}
			}
		}


				$group_plugins = get_post_meta( $post->ID, 'group_plugins', true );
				?>
            <div class="sos-wrap">
        <table>
        <thead>
        <tr>
            <th><input type="checkbox" id="check_all"></th>
            <th>TITLE</th>
            <th>Plugins</th>
            <th>Count</th>
        </tr>
        </thead>
        <tbody id="the-list">
                <tr class="block_info" id="group_<?= $post->ID; ?>">
                    <td><input type="checkbox" id="<?= $post->ID; ?>"></td>
                    <td><?= $post->post_title; ?></td>
                    <td><?= $group_plugins; ?></td>
                    <td><?= $group_plugins ? count( explode( ',', $group_plugins ) ) : 0; ?></td>
                </tr>
                <tr class="hidden_info">
                    <td colspan="6">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
											<?php
											$count_block_plugins = 0;

											foreach ( $all_plugins as $plugin ) {
												foreach ( $plugin as $key => $value ) {
													if ( $key === 'name' ) {
														if ( substr_count( $group_plugins, $plugin['name'] ) ) {
															$count_block_plugins ++;
														}
													}
												}
											}
											?>
                                            Plugins <span
                                                    class="disabled">- Disabled: <?= $count_block_plugins ?>/<?= count( $activate_plugins ); ?></span>
                                        </div>
                                        <span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
                                    </div>
									<?php
									if ( $activate_plugins ):
										?>
                                        <div class="plugin-wrapper wrapper-group-plugins">
											<?php
											foreach ( $activate_plugins as $activate_plugin ):
												?>
                                                <div class="content<?= substr_count( $group_plugins, $activate_plugin ) ? ' block' : ''; ?>"
                                                     group_id="<?= $post->ID; ?>">
                                                    <span><?= $activate_plugin; ?></span>
                                                </div>
											<?php
											endforeach;
											foreach ( $deactivate_plugins as $deactivate_plugin ):
												?>
                                                <div class="content deactivate-plugin<?= substr_count( $group_plugins, $deactivate_plugin ) ? ' block' : ''; ?>">
                                                    <span><?= $deactivate_plugin; ?></span>
                                                </div>
											<?php
											endforeach;
											?>
                                        </div>
									<?php
									else:
										?>
                                        <div class="plugin-wrapper no-plugins">
                                            <div class="content">
                                                <span>No activate plugins</span>
                                            </div>
                                        </div>
									<?php
									endif;
									?>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
				<?php
				if ( $post->post_status === 'publish' ) {
					$posts_chidren = get_posts( array(
						'post_type'   => 'sos_group',
						'numberposts' => - 1,
						'meta_query'  => array(
							array(
								'key'   => 'group_parents',
								'value' => $post->post_title,
							)
						),
					) );
				} else if ( $post->post_status === 'trash' ) {
					$posts_chidren = get_posts( array(
						'post_type'   => 'sos_group',
						'numberposts' => - 1,
						'post_status' => 'trash',
						'meta_query'  => array(
							array(
								'key'   => 'group_parents',
								'value' => $post->post_title,
							)
						),
					) );
				}


				if ( $posts_chidren ) :
					foreach ( $posts_chidren as $post_chidren ) :
						$children_group_plugins = get_post_meta( $post_chidren->ID, 'group_plugins', true );
						?>

                        <tr class="block_info block_children">
                            <td><input type="checkbox" id="<?= $post_chidren->ID; ?>"></td>
                            <td> — <?= $post_chidren->post_title; ?></td>
                            <td><?= get_post_meta( $post_chidren->ID, 'type_group', true ); ?></td>
                            <td><?= $children_group_plugins; ?></td>
                            <td><?= substr_count( $children_group_plugins, ',' ) + 1; ?></td>
                        </tr>
                        <tr class="hidden_info">
                            <td colspan="6">
                                <div class="content-filter">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="header">
                                                <div class="title">
													<?php
													$count_block_plugins = 0;
													foreach ( $activate_plugins as $activate_plugin ) {
														if ( substr_count( $children_group_plugins, $activate_plugin ) ) {
															$count_block_plugins ++;
														}
													}
													?>
                                                    Plugins <span
                                                            class="disabled">- Disabled: <?= $count_block_plugins ?>/<?= count( $activate_plugins ); ?></span>
                                                </div>
                                                <span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
                                            </div>
											<?php
											if ( $activate_plugins ):
												?>
                                                <div class="plugin-wrapper wrapper-group-plugins">
													<?php
													foreach ( $activate_plugins as $activate_plugin ):
														?>
                                                        <div class="content <?= substr_count( $children_group_plugins, $activate_plugin ) ? 'block' : ''; ?>">
                                                            <span><?= $activate_plugin; ?></span>
                                                        </div>
													<?php
													endforeach;
													?>
                                                </div>
											<?php
											else:
												?>
                                                <div class="plugin-wrapper no-plugins">
                                                    <div class="content">
                                                        <span>No activate plugins</span>
                                                    </div>
                                                </div>
											<?php
											endif;
											?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                        </table>
                        </div>
					<?php endforeach;
				endif;
	}


	/**
	 * Save the edited group
	 */
	public function save_group_options( $post_id ) {

		if ( ! isset( $_POST['block_plugins'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['nonce_sos_group_options'], plugin_basename( __FILE__ ) ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$group_plugins = sanitize_text_field( $_POST['block_plugins'] );

		update_post_meta( $post_id, 'group_plugins', $group_plugins );
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
		$post_link  = get_post_permalink( get_post( $post_id ) );

		$post_data = array(
			'post_title'  => $title_work,
			'post_type'   => 'sos_work',
			'post_status' => 'publish',
			'post_author' => 1,
		);

		$post_id = wp_insert_post( $post_data, true );
		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( $post_id->get_error_message() );
		}
		add_post_meta( $post_id, 'post_link', $post_link );
	}

	/**
	 * Creating a work after active plugins
	 */
	function add_item_to_worklist_active_plugins( $plugin ) {

		$title_work = 'Add filter to ' . ucfirst( dirname( $plugin ) );
		$post_link  = $plugin;

		if ( $plugin !== 'plugin-optimizer/plugin-optimizer.php' ) {
			$post_data = array(
				'post_title'  => $title_work,
				'post_type'   => 'sos_work',
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

	/**
	 * Content for works
	 */
	function content_works( $posts ) {
		if ( $posts ) :
			foreach ( $posts as $post ) : ?>
                <tr>
                    <td><input type="checkbox" id="<?= $post->ID; ?>"></td>
                    <td><?= $post->post_title; ?></td>
                    <td><?= get_post_meta( $post->ID, 'post_link', true ); ?></td>
                    <td><?= substr( str_replace( '-', '/', str_replace( " ", " at ", $post->post_date ) ), 0, - 3 ) . ' pm'; ?></td>
                    <td>
                        <a class="row-title"
                           href="<?= get_admin_url( null, 'admin.php?page=plugin_optimizer_filters&work_title=' . urlencode( str_replace( ' ', '_', str_replace( 'Add filter to ', '', $post->post_title ) ) ) . '&work_link=' . urlencode( get_post_meta( $post->ID, 'post_link', true ) ) ); ?>">
                            <button class="add-filter"><span class="pluse">+</span> add new filter</button>
                        </a>
                    </td>
                </tr>
			<?php
			endforeach;
		else:
			?>
            <tr>
                <td colspan="5">Great job your work is done</td>
            </tr>
		<?php
		endif;
	}

	/**
	 * Content for group plugins
	 */
	function content_groups( $posts ) {
		$all_plugins        = Plugin_Optimizer_Helper::get_plugins_with_status();
		$activate_plugins   = array();
		$deactivate_plugins = array();
		foreach ( $all_plugins as $plugin ) {
			foreach ( $plugin as $key => $value ) {
				if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
					if ( $value ) {
						array_push( $activate_plugins, $plugin['name'] );
					} else {
						array_push( $deactivate_plugins, $plugin['name'] );
					}
				}
			}
		}
		if ( $posts ) :
			foreach ( $posts as $post ) :
				$group_plugins = get_post_meta( $post->ID, 'group_plugins', true );
				?>

                <tr class="block_info" id="group_<?= $post->ID; ?>">
                    <td><input type="checkbox" id="<?= $post->ID; ?>"></td>
                    <td><?= $post->post_title; ?></td>
                    <td><?= $group_plugins; ?></td>
                    <td><?= $group_plugins ? count( explode( ',', $group_plugins ) ) : 0; ?></td>
                </tr>
                <tr class="hidden_info">
                    <td colspan="6">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
											<?php
											$count_block_plugins = 0;

											foreach ( $all_plugins as $plugin ) {
												foreach ( $plugin as $key => $value ) {
													if ( $key === 'name' ) {
														if ( substr_count( $group_plugins, $plugin['name'] ) ) {
															$count_block_plugins ++;
														}
													}
												}
											}
											?>
                                            Plugins <span
                                                    class="disabled">- Disabled: <?= $count_block_plugins ?>/<?= count( $activate_plugins ); ?></span>
                                        </div>
                                        <span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
                                    </div>
									<?php
									if ( $activate_plugins ):
										?>
                                        <div class="plugin-wrapper wrapper-group-plugins">
											<?php
											foreach ( $activate_plugins as $activate_plugin ):
												?>
                                                <div class="content<?= substr_count( $group_plugins, $activate_plugin ) ? ' block' : ''; ?>"
                                                     group_id="<?= $post->ID; ?>">
                                                    <span><?= $activate_plugin; ?></span>
                                                </div>
											<?php
											endforeach;
											foreach ( $deactivate_plugins as $deactivate_plugin ):
												?>
                                                <div class="content deactivate-plugin<?= substr_count( $group_plugins, $deactivate_plugin ) ? ' block' : ''; ?>">
                                                    <span><?= $deactivate_plugin; ?></span>
                                                </div>
											<?php
											endforeach;
											?>
                                        </div>
									<?php
									else:
										?>
                                        <div class="plugin-wrapper no-plugins">
                                            <div class="content">
                                                <span>No activate plugins</span>
                                            </div>
                                        </div>
									<?php
									endif;
									?>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
				<?php
				if ( $post->post_status === 'publish' ) {
					$posts_chidren = get_posts( array(
						'post_type'   => 'sos_group',
						'numberposts' => - 1,
						'meta_query'  => array(
							array(
								'key'   => 'group_parents',
								'value' => $post->post_title,
							)
						),
					) );
				} else if ( $post->post_status === 'trash' ) {
					$posts_chidren = get_posts( array(
						'post_type'   => 'sos_group',
						'numberposts' => - 1,
						'post_status' => 'trash',
						'meta_query'  => array(
							array(
								'key'   => 'group_parents',
								'value' => $post->post_title,
							)
						),
					) );
				}


				if ( $posts_chidren ) :
					foreach ( $posts_chidren as $post_chidren ) :
						$children_group_plugins = get_post_meta( $post_chidren->ID, 'group_plugins', true );
						?>

                        <tr class="block_info block_children">
                            <td><input type="checkbox" id="<?= $post_chidren->ID; ?>"></td>
                            <td> — <?= $post_chidren->post_title; ?></td>
                            <td><?= get_post_meta( $post_chidren->ID, 'type_group', true ); ?></td>
                            <td><?= $children_group_plugins; ?></td>
                            <td><?= substr_count( $children_group_plugins, ',' ) + 1; ?></td>
                        </tr>
                        <tr class="hidden_info">
                            <td colspan="6">
                                <div class="content-filter">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="header">
                                                <div class="title">
													<?php
													$count_block_plugins = 0;
													foreach ( $activate_plugins as $activate_plugin ) {
														if ( substr_count( $children_group_plugins, $activate_plugin ) ) {
															$count_block_plugins ++;
														}
													}
													?>
                                                    Plugins <span
                                                            class="disabled">- Disabled: <?= $count_block_plugins ?>/<?= count( $activate_plugins ); ?></span>
                                                </div>
                                                <span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
                                            </div>
											<?php
											if ( $activate_plugins ):
												?>
                                                <div class="plugin-wrapper wrapper-group-plugins">
													<?php
													foreach ( $activate_plugins as $activate_plugin ):
														?>
                                                        <div class="content <?= substr_count( $children_group_plugins, $activate_plugin ) ? 'block' : ''; ?>">
                                                            <span><?= $activate_plugin; ?></span>
                                                        </div>
													<?php
													endforeach;
													?>
                                                </div>
											<?php
											else:
												?>
                                                <div class="plugin-wrapper no-plugins">
                                                    <div class="content">
                                                        <span>No activate plugins</span>
                                                    </div>
                                                </div>
											<?php
											endif;
											?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
					<?php endforeach;
				endif;

				?>
			<?php endforeach;
		else:
			?>
            <tr>
                <td colspan="5">No Groups</td>
            </tr>
		<?php
		endif;
	}

	/**
	 * Content for filters categories
	 */
	function content_filters_categories( $categories ) {
		if ( $categories ) :
			foreach ( $categories as $cat ) :?>
                <tr class="block_info" id="cat-<?= $cat->cat_ID ?>">
                    <td><input type="checkbox" id="<?= $cat->cat_ID ?>"></td>
                    <td class="data-title-category"><?= $cat->cat_name; ?></td>
                </tr>
                <tr class="hidden_info">
                    <td colspan="2">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            Name
                                        </div>
                                    </div>
                                    <div class="content-description">
                                        <span class="data-interaction data-title-cat" cat_id="<?= $cat->cat_ID ?>"
                                              contenteditable>
                                            <?= $cat->cat_name; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row description">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            Description
                                        </div>
                                    </div>
                                    <div class="content-description">
                                        <span class="data-interaction data-description-cat" cat_id="<?= $cat->cat_ID ?>"
                                              contenteditable>
                                            <?= $cat->category_description ? $cat->category_description : 'None description'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
											<?php
											$count_filters = 0;
											$posts         = get_posts( array(
												'post_type'   => 'sos_filter',
												'numberposts' => - 1,
											) );
											if ( $posts ) {
												foreach ( $posts as $post ) {
													if ( has_term( $cat->cat_ID, 'сategories_filters', $post->ID ) ) {
														$count_filters ++;
													}
												}
											}
											?>
                                            Filters <span
                                                    class="disabled">- Used: <?= $count_filters; ?>/<?= wp_count_posts( 'sos_filter' )->publish; ?></span>
                                        </div>
                                    </div>
                                    <div class="plugin-wrapper wrapper_filter_to_category">
										<?php
										$posts = get_posts( array(
											'post_type'   => 'sos_filter',
											'numberposts' => - 1,
										) );
										if ( $posts ) :
											foreach ( $posts as $post ) :
												?>
                                                <div class="content <?= has_term( $cat->cat_ID, 'сategories_filters', $post->ID ) ? 'block' : ''; ?>"
                                                     id="<?= $post->ID; ?>" cat_id="cat_<?= $cat->cat_ID; ?>">
                                                    <span><?= $post->post_title; ?></span>
                                                </div>
											<?php
											endforeach;
										endif;
										?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
				<?php
				$difference    = $cat->cat_ID;
				$subcategories = get_categories( array(
					'parent'     => $difference,
					'taxonomy'   => 'сategories_filters',
					'type'       => 'sos_filter',
					'hide_empty' => 0,
				) );
				if ( $subcategories ) :
					foreach ( $subcategories as $subcategory ) :?>
                        <tr class="block_info block_children">
                            <td><input type="checkbox" id="<?= $subcategory->cat_ID ?>"></td>
                            <td> — <?= $subcategory->cat_name; ?></td>
                        </tr>
                        <tr class="hidden_info">
                            <td colspan="2">
                                <div class="content-filter">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="header">
                                                <div class="title">
													<?php
													$count_filters = 0;
													$posts         = get_posts( array(
														'post_type'   => 'sos_filter',
														'numberposts' => - 1,
													) );
													if ( $posts ) {
														foreach ( $posts as $post ) {
															if ( has_term( $subcategory->cat_ID, 'сategories_filters', $post->ID ) ) {
																$count_filters ++;
															}
														}
													}
													?>
                                                    Filters <span
                                                            class="disabled">- Used: <?= $count_filters; ?>/<?= wp_count_posts( 'sos_filter' )->publish; ?></span>
                                                </div>
                                            </div>
                                            <div class="plugin-wrapper wrapper_filter_to_category">
												<?php
												$posts = get_posts( array(
													'post_type'   => 'sos_filter',
													'numberposts' => - 1,
												) );
												if ( $posts ) :
													foreach ( $posts as $post ) :
														?>
                                                        <div class="content <?= has_term( $subcategory->cat_ID, 'сategories_filters', $post->ID ) ? 'block' : ''; ?>"
                                                             id="<?= $post->ID; ?>"
                                                             cat_id="cat_<?= $subcategory->cat_ID; ?>">
                                                            <span><?= $post->post_title; ?></span>
                                                        </div>
													<?php
													endforeach;
												endif;
												?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
					<?php endforeach;
				endif;
				?>
			<?php endforeach;
		else:
			?>
            <tr>
                <td colspan="5">No categories</td>
            </tr>
		<?php
		endif;
	}

	/**
	 * Content for filters
	 */
	function content_filters( $posts ) {
		if ( $posts ) :
			foreach ( $posts as $post ) :
				?>
                <tr class="block_info" id="filter-<?= $post->ID; ?>">
                    <td><input type="checkbox" id="<?= $post->ID; ?>"></td>
                    <td><?= $post->post_title; ?></td>
                    <td><?= get_post_meta( $post->ID, 'category_filter', true ); ?></td>
                    <td class="data-type-filter"><?= get_post_meta( $post->ID, 'type_filter', true ); ?></td>
                    <td class="data-link-filter"><?= get_post_meta( $post->ID, 'selected_page', true ); ?></td>
                    <td><?= implode( ', ', get_post_meta( $post->ID, 'block_plugins', true ) ); ?></td>
                </tr>
                <tr class="hidden_info">
                    <td colspan="6">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
									<?php
									$type_filter = get_post_meta( $post->ID, 'type_filter', true );
									if ( $type_filter === 'none' ):
										?>
                                        <div class="header">Permalinks</div>
                                        <div class="content-permalinks">
                                            <div class="link">
                                            <span class="data-interaction data-link" filter_id="<?= $post->ID ?>"
                                                  contenteditable>
                                            <?= get_post_meta( $post->ID, 'selected_page', true ) ?>
                                            </span>
                                            </div>
                                        </div>
									<?php

									else:
										?>
                                        <div class="header">Type</div>
                                        <div>
                                            <div class="content">
                                            <span class="data-interaction data-type" filter_id="<?= $post->ID ?>"
                                                  contenteditable>
                                                <?= $type_filter ?>
                                            </span>
                                            </div>
                                        </div>

									<?php
									endif;
									?>
                                </div>

                            </div>
                            <div class="row content-plugins">
								<?php
								$this->content_plugin_to_filter( $post );
								?>
                            </div>
                            <div class="row group-wrapper">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
											<?php
											$groups_plugins = get_post_meta( $post->ID, 'block_group_plugins', true );
											$count_groups   = 0;
											$groups         = get_posts( array(
												'post_type'   => 'sos_group',
												'numberposts' => - 1,
											) );
											if ( $groups ) {
												foreach ( $groups as $group ) {
													if ( in_array( $group->post_title, $groups_plugins ) ) {
														$count_groups ++;
													}
												}
											}
											?>
                                            groups <span
                                                    class="disabled">- Disabled: <?= $count_groups; ?>/<?= count( $groups ); ?></span>
                                        </div>
                                    </div>
                                    <div class="plugin-wrapper group-wrapper">
										<?php
										$all_plugins     = Plugin_Optimizer_Helper::get_plugins_with_status();
										$content_plugins = array();
										foreach ( $all_plugins as $plugin ) {
											foreach ( $plugin as $key => $value ) {
												if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
													$content_plugins[ $plugin['name'] ] = $plugin['file'];
												}
											}
										}

										$groups = get_posts( array(
											'post_type'   => 'sos_group',
											'numberposts' => - 1,
										) );
										if ( $groups ) :
											foreach ( $groups as $group ) :
												?>
                                                <div id="<?= $group->ID; ?>" value="<?= $post->ID; ?>"
                                                     class="content <?= in_array( $group->post_title, $groups_plugins ) ? 'block' : ''; ?> ">
                                                    <span><?= $group->post_title; ?></span>
													<?php
													$block_plugins_in_group = explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) );
													foreach ( $block_plugins_in_group as $block_plugin_in_group ) :
														?>
                                                        <div class="hidden_content content">
                                                            <span value="<?= $content_plugins[ $block_plugin_in_group ]; ?>"><?= $block_plugin_in_group; ?></span>
                                                        </div>
													<?php
													endforeach;
													?>
                                                </div>
											<?php
											endforeach;
										endif;
										?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            categories
                                        </div>
                                    </div>
                                    <div class="plugin-wrapper">
										<?php
										$this->ajax_create_category( $post );
										?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
			<?php
			endforeach;
		else:
			?>
            <tr>
                <td colspan="6">No filters</td>
            </tr>
		<?php
		endif;
	}

	/**
	 * Search elements
	 */
	public function ajax_search_elements() {
		$name_post_type = htmlspecialchars( $_POST['name_post_type'] );
		$type_elements  = htmlspecialchars( $_POST['type_elements'] );

		ob_start();
		if ( $type_elements === 'all' ) {
			$posts = get_posts( array(
				'post_type'   => $name_post_type,
				'numberposts' => - 1,
				's'           => esc_attr( $_POST['keyword'] ),
			) );

			if ( $name_post_type === 'sos_work' ) {
				$this->content_works( $posts );
			} elseif ( $name_post_type === 'sos_filter' ) {
				$this->content_filters( $posts );
			} elseif ( $name_post_type === 'sos_group' ) {
				$posts = get_posts( array(
					'post_type'   => 'sos_group',
					'numberposts' => - 1,
					's'           => esc_attr( $_POST['keyword'] ),
					'meta_query'  => array(
						array(
							'key'   => 'group_parents',
							'value' => 'None'
						)
					),
				) );
				$this->content_groups( $posts );
			} elseif ( $name_post_type === 'cat' ) {
				$categories = get_categories( [
					'taxonomy'   => 'сategories_filters',
					'type'       => 'sos_filter',
					'parent'     => 0,
					'hide_empty' => 0,
					'name__like' => esc_attr( $_POST['keyword'] ),
				] );

				$this->content_filters_categories( $categories );
			} elseif ( $name_post_type === 'plugins' ) {
				$filter_plugins = htmlspecialchars( $_POST['keyword'] );

				$all_plugins        = Plugin_Optimizer_Helper::get_plugins_with_status();
				$activate_plugins   = array();
				$deactivate_plugins = array();
				foreach ( $all_plugins as $plugin ) {
					foreach ( $plugin as $key => $value ) {
						if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
							if ( $value ) {
								array_push( $activate_plugins, $plugin['name'] );
							} else {
								array_push( $deactivate_plugins, $plugin['name'] );
							}
						}
					}
				}

				$activate_plugins = preg_grep( '~^' . $filter_plugins . '~i', $activate_plugins );

				$this->content_plugins_to_settings( $activate_plugins );
			}
		} else {
			$posts = get_posts( array(
				'post_type'   => $name_post_type,
				'numberposts' => - 1,
				'post_status' => 'trash',
				's'           => esc_attr( $_POST['keyword'] ),
			) );

			if ( $name_post_type === 'sos_work' ) {
				$this->content_works( $posts );
			} elseif ( $name_post_type === 'sos_filter' ) {
				$this->content_filters( $posts );
			} elseif ( $name_post_type === 'sos_group' ) {
				$this->content_groups( $posts );
			}
		}

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Show all elements
	 */
	public function ajax_all_elements() {
		$name_post_type = htmlspecialchars( $_POST['name_post_type'] );
		ob_start();

		$posts = get_posts( array(
			'post_type'   => $name_post_type,
			'numberposts' => - 1,
		) );

		if ( $name_post_type === 'sos_work' ) {
			$this->content_works( $posts );
		} elseif ( $name_post_type === 'sos_filter' ) {
			$this->content_filters( $posts );
		} elseif ( $name_post_type === 'sos_group' ) {
			$posts = get_posts( array(
				'post_type'   => 'sos_group',
				'numberposts' => - 1,
				'meta_query'  => array(
					array(
						'key'   => 'group_parents',
						'value' => 'None'
					)
				),
			) );
			$this->content_groups( $posts );
		}

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Show trash elements
	 */
	public function ajax_trash_elements() {
		$name_post_type = htmlspecialchars( $_POST['name_post_type'] );
		ob_start();

		$posts = get_posts( array(
			'post_type'   => $name_post_type,
			'numberposts' => - 1,
			'post_status' => 'trash',
		) );

		if ( $name_post_type === 'sos_work' ) {
			$this->content_works( $posts );
		} elseif ( $name_post_type === 'sos_filter' ) {
			$this->content_filters( $posts );
		} elseif ( $name_post_type === 'sos_group' ) {
			$posts = get_posts( array(
				'post_type'   => 'sos_group',
				'numberposts' => - 1,
				'post_status' => 'trash',
				'meta_query'  => array(
					array(
						'key'   => 'group_parents',
						'value' => 'None'
					)
				),
			) );
			$this->content_groups( $posts );
		}

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Delete elements
	 */
	public function ajax_delete_elements() {
		$name_post_type = htmlspecialchars( $_POST['name_post_type'] );
		$id_elements    = htmlspecialchars( $_POST['id_elements'] );
		$type_elements  = htmlspecialchars( $_POST['type_elements'] );

		if ( $type_elements === 'all' ) {
			if ( $name_post_type === 'cat' ) {
				$id_elements = explode( ',', $id_elements );

				foreach ( $id_elements as $id_element ) {
					wp_delete_term( $id_element, 'сategories_filters' );
				}

				ob_start();

				$categories = get_categories( [
					'taxonomy'   => 'сategories_filters',
					'type'       => 'sos_filter',
					'parent'     => 0,
					'hide_empty' => 0,
				] );

				$this->content_filters_categories( $categories );

				wp_send_json_success( ob_get_clean() );
			} else {
				$posts = get_posts( array(
					'post_type' => $name_post_type,
					'include'   => $id_elements,
				) );

				foreach ( $posts as $post ) {
					wp_trash_post( $post->ID );
				}
				$this->ajax_all_elements();
			}
		} else {
			$posts = get_posts( array(
				'post_type'   => $name_post_type,
				'include'     => $id_elements,
				'post_status' => 'trash',
			) );

			foreach ( $posts as $post ) {
				wp_delete_post( $post->ID, true );
			}
			$this->ajax_trash_elements();
		}
	}

	/**
	 * Restore works
	 */
	public function ajax_publish_elements() {
		$name_post_type = htmlspecialchars( $_POST['name_post_type'] );
		$id_elements    = htmlspecialchars( $_POST['id_elements'] );

		$posts = get_posts( array(
			'post_type'   => $name_post_type,
			'include'     => $id_elements,
			'post_status' => 'trash',
		) );

		foreach ( $posts as $post ) {
			wp_publish_post( $post->ID );
		}
		$this->ajax_trash_elements();
	}

	/**
	 * Show count elements
	 */
	public function ajax_count_elements() {
		$name_post_type = htmlspecialchars( $_POST['name_post_type'] );

		if ( $name_post_type === 'cat' ) {
			$return = array(
				'all' => wp_count_terms( 'сategories_filters' ),
			);
		} else {
			$return = array(
				'all'   => wp_count_posts( $name_post_type )->publish,
				'trash' => wp_count_posts( $name_post_type )->trash,
			);
		}

		wp_send_json_success( $return );

	}

	/**
	 * Create new category
	 */

	public function ajax_create_category( $post ) {
		if ( $post && ! is_numeric( $post ) ) {
			$id_filter = $post->ID;
		} elseif ( is_numeric( $post ) ) {
			$id_filter = $post;
		} else {
			$id_filter     = htmlspecialchars( $_POST['id_filter'] );
			$name_category = htmlspecialchars( $_POST['name_category'] );

			wp_set_object_terms( $id_filter, $name_category, 'сategories_filters' );
		}


		ob_start();

		$categories = get_categories( [
			'taxonomy'   => 'сategories_filters',
			'type'       => 'sos_filter',
			'hide_empty' => 0,
		] );

		if ( $categories ):
			foreach ( $categories as $cat ):
				?>
                <div class="content filter-category <?= ( has_term( $cat->cat_name, 'сategories_filters', $id_filter ) ) ? 'block' : ''; ?>">
                    <span><?= $cat->cat_name; ?></span>
                    <span class="close" id="<?= $cat->cat_ID; ?>">×</span>
                </div>
			<?php
			endforeach;
		endif;
		?>
        <input type="text" placeholder="Name category">
        <button class="add-filter add-permalink add-category" id="post-<?= $id_filter; ?>">
            <span class="pluse">+</span> Category
        </button>
		<?php
		if ( $post && ! is_numeric( $post ) ) {
			echo ob_get_clean();
		} else {
			wp_send_json_success( ob_get_clean() );
		}

	}

	/**
	 * Delete category
	 */

	public function ajax_delete_category() {
		$cat_ID    = htmlspecialchars( $_POST['id_category'] );
		$id_filter = htmlspecialchars( $_POST['id_filter'] );

		wp_delete_term( $cat_ID, 'сategories_filters' );

		$this->ajax_create_category( $id_filter );
	}

	/**
	 * Add category to filter
	 */

	public function ajax_add_category_to_filter() {
		$cat_ID    = htmlspecialchars( $_POST['id_category'] );
		$filter_ID = htmlspecialchars( $_POST['id_filter'] );
		$trigger   = htmlspecialchars( $_POST['trigger'] );
		$page      = htmlspecialchars( $_POST['page'] );


		if ( $trigger === 'delete' ) {
			wp_remove_object_terms( intval( $filter_ID ), intval( $cat_ID ), 'сategories_filters' );
		} else {
			wp_set_post_terms( $filter_ID, $cat_ID, 'сategories_filters', true );
		}


		if ( $page === 'filters' ):
			$this->ajax_create_category( $filter_ID );
		else:
			ob_start();
			?>
            <div class="col-12">
                <div class="header">
                    <div class="title">
						<?php
						$count_filters = 0;
						$posts         = get_posts( array(
							'post_type'   => 'sos_filter',
							'numberposts' => - 1,
						) );
						if ( $posts ) {
							foreach ( $posts as $post ) {
								if ( has_term( $cat_ID, 'сategories_filters', $post->ID ) ) {
									$count_filters ++;
								}
							}
						}
						?>
                        Filters <span
                                class="disabled">- Used: <?= $count_filters; ?>/<?= wp_count_posts( 'sos_filter' )->publish; ?></span>
                    </div>
                </div>
                <div class="plugin-wrapper wrapper_filter_to_category">
					<?php
					$posts = get_posts( array(
						'post_type'   => 'sos_filter',
						'numberposts' => - 1,
					) );
					if ( $posts ) :
						foreach ( $posts as $post ) :
							?>
                            <div class="content <?= has_term( $cat_ID, 'сategories_filters', $post->ID ) ? 'block' : ''; ?>"
                                 id="<?= $post->ID; ?>" cat_id="cat_<?= $cat_ID; ?>">
                                <span><?= $post->post_title; ?></span>
                            </div>
						<?php
						endforeach;
					endif;
					?>
                </div>
            </div>
			<?php
			wp_send_json_success( ob_get_clean() );
		endif;

	}

	/**
	 * Check name group
	 */

	public function ajax_check_name_elements() {
		$name_element = htmlspecialchars( $_POST['name_element'] );
		$type_element = htmlspecialchars( $_POST['type_element'] );

		$posts;
		$categories;

		if ( $type_element === 'filters' ) {
			$posts = get_posts( array(
				'post_type'   => 'sos_filter',
				'numberposts' => - 1,
			) );
		} elseif ( $type_element === 'filters_categories' ) {
			$categories = get_categories( [
				'taxonomy' => 'сategories_filters',
				'type'     => 'sos_filter',
			] );
		} elseif ( $type_element === 'groups' ) {
			$posts = get_posts( array(
				'post_type'   => 'sos_group',
				'numberposts' => - 1,
			) );
		}


		$names_element = array();

		if ( $posts ) {
			foreach ( $posts as $post ) {
				array_push( $names_element, $post->post_title );
			}
		} elseif ( $categories ) {
			foreach ( $categories as $cat ) {
				array_push( $names_element, $cat->cat_name );
			}
		} else {
			wp_send_json_success( 'nothing' );
		}
		if ( in_array( $name_element, $names_element ) ) {
			wp_send_json_success( true );
		} else {
			wp_send_json_success( false );
		}
	}


	/**
	 * Create new category for page category
	 */

	public function ajax_create_cat_subcat() {
		$name_category        = htmlspecialchars( $_POST['name_category'] );
		$description_category = htmlspecialchars( $_POST['description_category'] );
		$parent_category      = htmlspecialchars( $_POST['parent_category'] );

		if ( $parent_category === 'None' ) {
			wp_insert_category( array(
				'cat_ID'               => 0,
				'cat_name'             => $name_category,
				'category_description' => $description_category,
				'taxonomy'             => 'сategories_filters'
			) );
		} else {
			wp_insert_category( array(
				'cat_ID'               => 0,
				'cat_name'             => $name_category,
				'category_description' => $description_category,
				'category_parent'      => $parent_category,
				'taxonomy'             => 'сategories_filters'
			) );
		}

		ob_start();

		$categories = get_categories( [
			'taxonomy'   => 'сategories_filters',
			'type'       => 'sos_filter',
			'parent'     => 0,
			'hide_empty' => 0,
		] );

		$this->content_filters_categories( $categories );

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * After create category add select parents category
	 */

	public function ajax_get_parent_cat() {

		ob_start();

		$categories = get_categories( [
			'taxonomy'   => 'сategories_filters',
			'type'       => 'sos_filter',
			'parent'     => 0,
			'hide_empty' => 0,
		] );

		?>
        <div class="content block none_parent">
            <span value="None">None</span>
        </div>
		<?php

		if ( $categories ):
			foreach ( $categories as $cat ):
				?>
                <div class="content select_parent_to_category">
                    <span value="<?= $cat->cat_ID; ?>"><?= $cat->cat_name; ?></span>
                </div>
			<?php
			endforeach;
		endif;

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Add plugin to filter
	 */

	public function ajax_change_plugins_to_filter() {
		$filter_id      = htmlspecialchars( $_POST['filter_id'] );
		$plugin_name    = htmlspecialchars( $_POST['plugin_name'] );
		$plugin_link    = htmlspecialchars( $_POST['plugin_link'] );
		$change_plugins = htmlspecialchars( $_POST['change_plugins'] );


		$array_plugins      = get_post_meta( $filter_id, 'block_plugins', true );
		$array_link_plugins = get_post_meta( $filter_id, 'block_value_plugins', true );

		if ( $change_plugins === '+' ) {
			array_push( $array_plugins, $plugin_name );
			array_push( $array_link_plugins, $plugin_link );
		} else {
			$array_plugins      = array_diff( $array_plugins, [ $plugin_name ] );
			$array_link_plugins = array_diff( $array_link_plugins, [ $plugin_link ] );
		}

		update_post_meta( $filter_id, 'block_plugins', $array_plugins );
		update_post_meta( $filter_id, 'block_value_plugins', $array_link_plugins );


		ob_start();

		$posts = get_posts( array(
			'post_type' => 'sos_filter',
			'include'   => $filter_id,
		) );

		if ( $posts ) {
			foreach ( $posts as $post ) {
				$this->content_plugin_to_filter( $post );
			}
		}

		$return = array(
			'filter_id' => $filter_id,
			'return'    => ob_get_clean(),
		);

		wp_send_json_success( $return );

	}

	function content_plugin_to_filter( $post ) {
		$all_plugins        = Plugin_Optimizer_Helper::get_plugins_with_status();
		$activate_plugins   = array();
		$deactivate_plugins = array();
		foreach ( $all_plugins as $plugin ) {
			foreach ( $plugin as $key => $value ) {
				if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
					if ( $value ) {
						$activate_plugins[ $plugin['name'] ] = $plugin['file'];
					} else {
						$deactivate_plugins[ $plugin['name'] ] = $plugin['file'];
					}
				}
			}
		}
		$block_plugins = get_post_meta( $post->ID, 'block_plugins', true );
		?>
        <div class="col-12">
            <div class="header">
                <div class="title">
					<?php $count_plugins = 0;
					if ( $activate_plugins ) {
						foreach ( $activate_plugins as $activate_plugin ) {
							if ( in_array( $activate_plugin, $block_plugins ) ) {
								$count_plugins ++;
							}
						}
					}
					?>
                    Plugins <span
                            class="disabled">- Disabled: <?= $count_plugins; ?>/<?= count( $activate_plugins ); ?></span>
                </div>
                <span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
            </div>
			<?php
			if ( $activate_plugins ):
				?>
                <div class="header attribute-plugin">Active plugins</div>
                <div class="plugin-wrapper">
					<?php
					foreach ( $activate_plugins as $activate_plugin => $activate_plugin_link ):
						?>
                        <div class="content<?= ( in_array( $activate_plugin, $block_plugins ) ) ? ' block' : '' ?>">
							<?php
							if ( in_array( $activate_plugin, $block_plugins ) ):
								?>
                                <span class="close" id="<?= $activate_plugin; ?>" value="<?= $post->ID; ?>"
                                      link="<?= $activate_plugin_link; ?>">×</span>
							<?php
							else:
								?>
                                <span class="close pluse_plugin" id="<?= $activate_plugin; ?>"
                                      value="<?= $post->ID; ?>" link="<?= $activate_plugin_link; ?>">+</span>
							<?php
							endif;
							?>
							<?php
							$groups_plugins = get_post_meta( $post->ID, 'block_group_plugins', true );

							$groups = get_posts( array(
								'post_type'   => 'sos_group',
								'numberposts' => - 1,
							) );
							if ( $groups ) :
								?>
                                <div class="groups-names">
									<?php
									foreach ( $groups as $group ):
										if ( in_array( $group->post_title, $groups_plugins ) && in_array( $activate_plugin, explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) ) ) ):

											?>
                                            <span><?= $group->post_title; ?></span>
										<?php
										endif;
									endforeach;
									?>
                                </div>
							<?php
							endif;
							?>
                            <span><?= $activate_plugin; ?></span>
                        </div>
					<?php
					endforeach;
					?>
                </div>
                <div class="header attribute-plugin">Inactive plugins</div>
                <div class="plugin-wrapper">
					<?php
					foreach ( $deactivate_plugins as $deactivate_plugin => $deactivate_plugin_link ):
						?>
                        <div class="content deactivate-plugin<?= in_array( $deactivate_plugin, $block_plugins ) ? ' block' : ''; ?>">
							<?php
							$groups_plugins = get_post_meta( $post->ID, 'block_group_plugins', true );

							$groups = get_posts( array(
								'post_type'   => 'sos_group',
								'numberposts' => - 1,
							) );
							if ( $groups ) :
								?>
                                <div class="groups-names">
									<?php
									foreach ( $groups as $group ):
										if ( in_array( $group->post_title, $groups_plugins ) && in_array( $deactivate_plugin, explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) ) ) ):

											?>
                                            <span class="group-name"><?= $group->post_title; ?></span>
										<?php
										endif;
									endforeach;
									?>
                                </div>
							<?php
							endif;
							?>
                            <span><?= $deactivate_plugin; ?></span>
							<?php
							if ( in_array( $deactivate_plugin, $block_plugins ) ):
								?>
                                <span class="close" id="<?= $deactivate_plugin; ?>" value="<?= $post->ID; ?>"
                                      link="<?= $deactivate_plugin_link; ?>">×</span>
							<?php
							else:
								?>
                                <span class="close pluse_plugin" id="<?= $deactivate_plugin; ?>"
                                      value="<?= $post->ID; ?>" link="<?= $deactivate_plugin_link; ?>">+</span>
							<?php
							endif;
							?>
                        </div>
					<?php
					endforeach;
					?>
                </div>
			<?php
			else:
				?>
                <div class="plugin-wrapper no-plugins">
                    <div class="content">
                        <span>No activate plugins for blocking</span>
                    </div>
                </div>
			<?php
			endif;
			?>
        </div>
		<?php
	}


	/**
	 * Content plugins to settings
	 */

	public function content_activate_plugins_to_settings( $activate_plugins ) {

		if ( $activate_plugins ):
			?>
			<?php
			foreach ( $activate_plugins as $activate_plugin ):
				?>
                <tr class="block_info">
                    <td><input type="checkbox"></td>
                    <td><?= $activate_plugin; ?></td>
                </tr>
				<?php
				$posts = get_posts( array(
					'post_type'   => 'sos_filter',
					'numberposts' => - 1,
				) );
				?>
                <tr class="hidden_info">
                    <td colspan="2">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            Filters
                                        </div>
                                        <span class="count-plugin">( All: <?= wp_count_posts( 'sos_filter' )->publish; ?>   |   Trash: <?= wp_count_posts( 'sos_filter' )->trash; ?> )</span>
                                    </div>
									<?php
									if ( $posts ):
										?>
                                        <div class="plugin-wrapper">
											<?php
											foreach ( $posts as $post ):
												$group_plugins = get_post_meta( $post->ID, 'block_plugins', true );
												?>
                                                <a href="<?= esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_filters&filter_title=' . urlencode( $post->post_title ) ) ); ?>">
                                                    <div class="content
                                             <?php
													if ( in_array( $activate_plugin, $group_plugins ) ) {
														echo 'block';
													}
													?>
                                             ">
                                                        <span><?= $post->post_title; ?></span>
                                                    </div>
                                                </a>
											<?php
											endforeach;
											?>
                                        </div>
									<?php
									else:
										?>
                                        <div class="plugin-wrapper no-plugins">
                                            <div class="content">
                                                <span>No activate plugins</span>
                                            </div>
                                        </div>
									<?php
									endif;
									?>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
			<?php
			endforeach;
			?>
		<?php
		else:
			?>
            <tr class="plugin-wrapper no-plugins">
                <td colspan="2">
                    <div class="content">
                        <span>No activate plugins for blocking</span>
                    </div>
                </td>
            </tr>
		<?php
		endif;
	}


	/**
	 * Content deactive plugins to settings
	 */

	public function content_deactive_plugins_to_settings( $deactivate_plugins ) {

		if ( $deactivate_plugins ):
			?>
			<?php
			foreach ( $deactivate_plugins as $deactivate_plugin ):
				?>
                <tr class="block_info">
                    <td><input type="checkbox"></td>
                    <td><?= $deactivate_plugin; ?></td>
                </tr>
				<?php
				$posts = get_posts( array(
					'post_type'   => 'sos_filter',
					'numberposts' => - 1,
				) );
				?>
                <tr class="hidden_info">
                    <td colspan="2">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            Filters
                                        </div>
                                        <span class="count-plugin">( All: <?= wp_count_posts( 'sos_filter' )->publish; ?>   |   Trash: <?= wp_count_posts( 'sos_filter' )->trash; ?> )</span>
                                    </div>
									<?php
									if ( $posts ):
										?>
                                        <div class="plugin-wrapper">
											<?php
											foreach ( $posts as $post ):
												$group_plugins = get_post_meta( $post->ID, 'block_plugins', true );
												?>
                                                <a href="<?= esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_filters&filter_title=' . urlencode( $post->post_title ) ) ); ?>">
                                                    <div class="content
                                             <?php
													if ( in_array( $deactivate_plugin, $group_plugins ) ) {
														echo 'block';
													}
													?>
                                             ">
                                                        <span><?= $post->post_title; ?></span>
                                                    </div>
                                                </a>
											<?php
											endforeach;
											?>
                                        </div>
									<?php
									else:
										?>
                                        <div class="plugin-wrapper no-plugins">
                                            <div class="content">
                                                <span>No activate plugins</span>
                                            </div>
                                        </div>
									<?php
									endif;
									?>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
			<?php
			endforeach;
			?>
		<?php
		else:
			?>
            <tr class="plugin-wrapper no-plugins">
                <td colspan="2">
                    <div class="content">
                        <span>No activate plugins for blocking</span>
                    </div>
                </td>
            </tr>
		<?php
		endif;
	}


	/**
	 * Create new category for page category
	 */

	public function ajax_transition_viewed() {
		$self_id = htmlspecialchars( $_POST['selfId'] );

		ob_start();

		switch ( $self_id ) {
			case 'window_filters':
				include 'partials/page-filters.php';
				break;
			case 'window_categories':
				include 'partials/page-categories.php';
				break;
			case 'window_groups':
				include 'partials/page-groups.php';
				break;
			case 'window_worklist':
				include 'partials/page-worklist.php';
				break;
		}

		wp_send_json_success( ob_get_clean() );

	}


	/**
	 * After create group add select parents group
	 */

	public function ajax_get_parent_group() {

		ob_start();

		?>
        <div class="header">
            <div class="title">
				<?php
				$groups = get_posts( array(
					'post_type'   => 'sos_group',
					'numberposts' => - 1,
				) );
				?>
                Select parent <span
                        class="disabled">- <?= count( $groups ); ?></span>
            </div>
        </div>
        <div class="plugin-wrapper">
            <div class="content none_group block">
                <span>None</span>
            </div>
			<?php
			if ( $groups ) :
				foreach ( $groups as $group ) :
					?>
                    <div class="content">
                        <span><?= $group->post_title; ?></span>
                    </div>
				<?php
				endforeach;
			endif;
			?>
        </div>
		<?php

		wp_send_json_success( ob_get_clean() );

	}


	/**
	 * Add plugin to group
	 */

	public function ajax_change_plugins_to_group() {
		$group_id    = htmlspecialchars( $_POST['group_id'] );
		$plugin_name = htmlspecialchars( $_POST['plugin_name'] );
		$trigger     = htmlspecialchars( $_POST['trigger'] );

		$group_plugins = get_post_meta( $group_id, 'group_plugins', true );
		$group_plugins = $group_plugins ? $group_plugins : '';

		if ( $trigger === 'add' ) {
			if ( $group_plugins ) {
				$group_plugins .= ', ' . $plugin_name;
			} else {
				$group_plugins .= $plugin_name;
			}
		} else {
			if ( stripos( $group_plugins, ', ' . $plugin_name ) !== false ) {
				$group_plugins = str_replace( ', ' . $plugin_name, '', $group_plugins );
			} elseif ( stripos( $group_plugins, $plugin_name . ', ' ) !== false ) {
				$group_plugins = str_replace( $plugin_name . ', ', '', $group_plugins );
			} else {
				$group_plugins = '';
			}

		}

		update_post_meta( $group_id, 'group_plugins', $group_plugins );


		ob_start();

		$posts = get_posts( array(
			'post_type'   => 'sos_group',
			'numberposts' => - 1,
			'meta_query'  => array(
				array(
					'key'   => 'group_parents',
					'value' => 'None'
				)
			),
		) );

		$this->content_groups( $posts );

		$return = array(
			'group_id' => $group_id,
			'return'   => ob_get_clean(),
		);

		wp_send_json_success( $return );

	}


	/**
	 * Ajax show plugins on settings
	 */

	public function ajax_show_plugins() {
		$type_plugins = htmlspecialchars( $_POST['type_plugins'] );

		$all_plugins        = Plugin_Optimizer_Helper::get_plugins_with_status();
		$activate_plugins   = array();
		$deactivate_plugins = array();
		foreach ( $all_plugins as $plugin ) {
			foreach ( $plugin as $key => $value ) {
				if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
					if ( $value ) {
						array_push( $activate_plugins, $plugin['name'] );
					} else {
						array_push( $deactivate_plugins, $plugin['name'] );
					}
				}
			}
		}

		ob_start();

		if ( $type_plugins === 'activate_plugins' ) {
			$this->content_activate_plugins_to_settings( $activate_plugins );
		} else {
			$this->content_deactive_plugins_to_settings( $deactivate_plugins );
		}

		wp_send_json_success( ob_get_clean() );
	}

	/**
	 * Ajax change permalink
	 */

	public function ajax_change_permalink() {
		$text_link = htmlspecialchars( $_POST['text_link'] );
		$filter_id = htmlspecialchars( $_POST['filter_id'] );

		update_post_meta( $filter_id, 'selected_page', $text_link );

		wp_send_json_success( $text_link );
	}

	/**
	 * Ajax change type
	 */

	public function ajax_change_type() {
		$text_type = htmlspecialchars( $_POST['text_type'] );
		$filter_id = htmlspecialchars( $_POST['filter_id'] );

		update_post_meta( $filter_id, 'type_filter', $text_type );

		wp_send_json_success( $text_type );
	}

	/**
	 * Ajax change description category
	 */

	public function ajax_change_data_category() {
		$text_name            = htmlspecialchars( $_POST['text_name'] );
		$description_category = htmlspecialchars( $_POST['description_category'] );
		$cat_id               = htmlspecialchars( $_POST['cat_id'] );


		wp_insert_category( array(
			'cat_ID'               => $cat_id,
			'cat_name'             => $text_name,
			'category_description' => $description_category,
			'taxonomy'             => 'сategories_filters',
		) );


		$categories = get_categories( [
			'taxonomy'   => 'сategories_filters',
			'type'       => 'sos_filter',
			'parent'     => 0,
			'hide_empty' => 0,
			'include'    => $cat_id,
		] );

		if ( $categories ) {
			foreach ( $categories as $cat ) {
				wp_send_json_success( $cat->cat_name );
			}
		}


	}


	/**
	 * Add group to filter
	 */

	public function ajax_change_groups_to_filter() {
		$group_name    = htmlspecialchars( $_POST['group_name'] );
		$filter_id     = htmlspecialchars( $_POST['filter_id'] );
		$change_groups = htmlspecialchars( $_POST['change_groups'] );
		$plugins_names = htmlspecialchars( $_POST['plugins_names'] );
		$plugins_links = htmlspecialchars( $_POST['plugins_links'] );


		$plugins_names = explode( ', ', $plugins_names );
		$plugins_links = explode( ', ', $plugins_links );

		$array_plugins      = get_post_meta( $filter_id, 'block_plugins', true );
		$array_link_plugins = get_post_meta( $filter_id, 'block_value_plugins', true );
		$array_groups_names = get_post_meta( $filter_id, 'block_group_plugins', true );

		if ( $change_groups === 'add' ) {
			foreach ( $plugins_names as $plugin_name ) {
				array_push( $array_plugins, $plugin_name );
			}

			foreach ( $plugins_links as $plugin_link ) {
				array_push( $array_link_plugins, $plugin_link );
			}

			array_push( $array_groups_names, $group_name );

		} else {
			foreach ( $plugins_names as $plugin_name ) {
				if ( count( array_keys( $array_plugins, $plugin_name ) ) > 1 ) {
					$array_plugins = array_diff( $array_plugins, [ $plugin_name ] );
					array_push( $array_plugins, $plugin_name );
				} else {
					$array_plugins = array_diff( $array_plugins, [ $plugin_name ] );
				}
			}

			foreach ( $plugins_links as $plugin_link ) {
				if ( count( array_keys( $array_link_plugins, $plugin_link ) ) > 1 ) {
					$array_link_plugins = array_diff( $array_link_plugins, [ $plugin_link ] );
					array_push( $array_link_plugins, $plugin_link );
				} else {
					$array_link_plugins = array_diff( $array_link_plugins, [ $plugin_link ] );
				}
			}


			$array_groups_names = array_diff( $array_groups_names, [ $group_name ] );
		}

		update_post_meta( $filter_id, 'block_plugins', $array_plugins );
		update_post_meta( $filter_id, 'block_value_plugins', $array_link_plugins );

		update_post_meta( $filter_id, 'block_group_plugins', $array_groups_names );


		ob_start();

		$posts = get_posts( array(
			'post_type' => 'sos_filter',
			'include'   => $filter_id,
		) );

		if ( $posts ) {
			foreach ( $posts as $post ) {
				$this->content_plugin_to_filter( $post );
			}
		}

		$content_plugins_to_filter = ob_get_clean();

		ob_start();
		?>
        <div class="col-12">
            <div class="header">
                <div class="title">
					<?php
					$groups_plugins = get_post_meta( $filter_id, 'block_group_plugins', true );
					$count_groups   = 0;
					$groups         = get_posts( array(
						'post_type'   => 'sos_group',
						'numberposts' => - 1,
					) );
					if ( $groups ) {
						foreach ( $groups as $group ) {
							if ( in_array( $group->post_title, $groups_plugins ) ) {
								$count_groups ++;
							}
						}
					}
					?>
                    groups <span
                            class="disabled">- Disabled: <?= $count_groups; ?>/<?= count( $groups ); ?></span>
                </div>
            </div>
            <div class="plugin-wrapper group-wrapper">
				<?php
				$all_plugins     = Plugin_Optimizer_Helper::get_plugins_with_status();
				$content_plugins = array();
				foreach ( $all_plugins as $plugin ) {
					foreach ( $plugin as $key => $value ) {
						if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
							$content_plugins[ $plugin['name'] ] = $plugin['file'];
						}
					}
				}

				$groups = get_posts( array(
					'post_type'   => 'sos_group',
					'numberposts' => - 1,
				) );
				if ( $groups ) :
					foreach ( $groups as $group ) :
						?>
                        <div id="<?= $group->ID; ?>" value="<?= $filter_id; ?>"
                             class="content <?= in_array( $group->post_title, $groups_plugins ) ? 'block' : ''; ?> ">
                            <span><?= $group->post_title; ?></span>
							<?php
							$block_plugins_in_group = explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) );
							foreach ( $block_plugins_in_group as $block_plugin_in_group ) :
								?>
                                <div class="hidden_content content">
                                    <span value="<?= $content_plugins[ $block_plugin_in_group ]; ?>"><?= $block_plugin_in_group; ?></span>
                                </div>
							<?php
							endforeach;
							?>
                        </div>
					<?php
					endforeach;
				endif;
				?>
            </div>
        </div>
		<?php

		$content_groups_to_filter = ob_get_clean();

		$return = array(
			'filter_id'       => $filter_id,
			'content_plugins' => $content_plugins_to_filter,
			'content_groups'  => $content_groups_to_filter,
		);

		wp_send_json_success( $return );

	}

}

