<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    PluginOptimizer
 * @subpackage PluginOptimizer/admin
 * @author     Simple Online Systems <admin@simpleonlinesystems.com>
 */

class PO_Admin {

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

//		$this->getLinkPosts();

	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'css/po-admin-public.css', array(), $this->version, 'all' );
		if ( stripos( $_SERVER["QUERY_STRING"], "plugin_optimizer" ) || stripos( $_SERVER["QUERY_STRING"], "action=edit" ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/po-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '_bootstrap', plugin_dir_url( __FILE__ ) . 'css/po-admin-bootstrap.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	function enqueue_scripts() {

        $version  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/po-admin.js' ));
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/po-admin.js', array( 'jquery' ), $version, false );
        
        $array = array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'home_url' => home_url(),
            'user_id'  => get_current_user_id(),
        );
        
		wp_localize_script( $this->plugin_name, 'po_object', $array );
		wp_enqueue_script( $this->plugin_name );

	}

	/**
	 * Add type module for js
	 */
	function add_type_attribute( $tag, $handle, $src ) {
		// if not your script, do nothing and return original $tag
		if ( $this->plugin_name !== $handle ) {
			return $tag;
		}
		// change the script tag by adding type="module" and return it.
		$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';

		return $tag;
	}

    // TODO not used?
	function check_memory_usage() {
		return function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage() / 1024 / 1024, 2 ) : 0;
	}

	/**
	 * Add Admin-Bar Pages
	 */
	function add_plugin_in_admin_bar( $wp_admin_bar ) {
        
        // Main top menu item
		$wp_admin_bar->add_menu( array(
			'id'    => 'plugin_optimizer',
			'title' => '<span class="sos-icon"></span> Plugin Optimizer | Memory used: ' . $this->check_memory_usage() . ' Mb<span class="sos-speed"></span>',
			'href'  => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_settings' ) ),
		) );
        
        
        // Worklist
		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_worklist',
			'title'  => 'Worklist (' . wp_count_posts( 'sos_work' )->publish . ')',
			'href'   => esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_worklist' ) ),
		) );


        // Blocked Plugins
		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_blocked_plugins',
			'title'  => 'Blocked Plugins (' . count( po_mu_plugin()->blocked_plugins ) . ')',
		) );

		foreach ( po_mu_plugin()->get_names_list( "blocked_plugins" ) as $plugin_path => $plugin_name) {
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
			'title'  => 'Running Plugins (' . count( po_mu_plugin()->filtered_active_plugins ) . ')',
		) );

		foreach ( po_mu_plugin()->get_names_list( "filtered_active_plugins" ) as $plugin_path => $plugin_name) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'plugin_optimizer_running_plugins',
				'id'     => 'plugin_optimizer_running_plugin_' . $plugin_path,
				'title'  => $plugin_name,
			) );
		}
        
        if( po_mu_plugin()->is_po_default_page ){
            $wp_admin_bar->add_menu( array(
                'parent' => 'plugin_optimizer',
                'id'     => 'plugin_optimizer_default_page',
                'title'  => 'We are on a PO default page.',
            ) );
        }
        
        if( ! po_mu_plugin()->is_being_filtered ){
            $wp_admin_bar->add_menu( array(
                'parent' => 'plugin_optimizer',
                'id'     => 'plugin_optimizer_being_filtered',
                'title'  => 'This page is not being filtered.',
            ) );
        }
        
        if( ! empty( po_mu_plugin()->filters_in_use ) ){
            
            $wp_admin_bar->add_menu( array(
                'parent' => 'plugin_optimizer',
                'id'     => 'plugin_optimizer_filters_in_use',
                'title'  => 'Filters in use: ' . count( po_mu_plugin()->filters_in_use ),
            ) );
            
            foreach ( po_mu_plugin()->filters_in_use as $filter_id => $filter_name) {
                $wp_admin_bar->add_menu( array(
                    'parent' => 'plugin_optimizer_filters_in_use',
                    'id'     => 'plugin_optimizer_filter_in_use_' . $filter_id,
                    'title'  => $filter_name,
                ) );
            }
            
        }
        
	}

	/**
	 * Register all post types
	 */
	function register_post_types() {
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
	function register_taxonomies() {

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
			'show_ui'       => true,
			'query_var'     => true,
			'default_term'  => 'Uncategorized',
		) );

	}

	/**
	 * Edit group
	 */
	function render_group_options( $post ) {

		wp_nonce_field( plugin_basename( __FILE__ ), 'nonce_sos_group_options' );

		$value_group_plugins = get_post_meta( $post->ID, 'group_plugins', 1 );

		?><div style="display: none">
            <label for="block_plugins"> <?= "Select group plugins" ?> </label>
            <input type="text" id="block_plugins" name="block_plugins" value=" <?= $value_group_plugins ?>" size="25"/>
            <br>
            <br>
        </div><?php

		$group_plugins = get_post_meta( $post->ID, 'group_plugins', true );

		?><div class="sos-wrap">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="check_all"></th>
                    <th>Title</th>
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


				if ( $posts_chidren ){
					foreach ( $posts_chidren as $post_chidren ){
						$children_group_plugins = get_post_meta( $post_chidren->ID, 'group_plugins', true );
						?>

                        <tr class="block_info block_children">
                            <td><input type="checkbox" id="<?= $post_chidren->ID; ?>"></td>
                            <td> — <?= $post_chidren->post_title; ?></td>
                            <td><?= get_post_meta( $post_chidren->ID, 'type_group', true ); ?></td>
                            <td><?= $children_group_plugins; ?></td>
                            <td><?= substr_count( $children_group_plugins, ',' ) + 1; ?></td>
                        </tr>
                        
					<?php
                    }
				}
				?>
            </tbody>
        </table>
        </div><?php
	}

	/**
	 * Save the edited group
	 */
	function save_group_options( $post_id ) {

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

