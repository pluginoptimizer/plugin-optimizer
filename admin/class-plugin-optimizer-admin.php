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
	function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

//		$this->getLinkPosts();

	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'css/plugin-optimizer-admin-public.css', array(), $this->version, 'all' );
		if ( stripos( $_SERVER["QUERY_STRING"], "plugin_optimizer" ) || stripos( $_SERVER["QUERY_STRING"], "action=edit" ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-optimizer-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '_bootstrap', plugin_dir_url( __FILE__ ) . 'css/plugin-optimizer-admin-bootstrap.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	function enqueue_scripts() {

        $version  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/plugin-optimizer-admin.js' ));
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-optimizer-admin.js', array( 'jquery' ), $version, false );
        
		wp_localize_script( $this->plugin_name, 'plugin_optimizer_groups', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
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
			'show_ui'      => true,
			'query_var'    => true,
			'default_term'    => 'Uncategorized',
		) );

	}

	/**
	 * Register meta boxes to filter and group
	 */
	function register_meta_boxes() {

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
	 * Edit filter
	 */
	function render_filter_options( $post ) {

		wp_nonce_field( plugin_basename( __FILE__ ), 'nonce_sos_filter_options' );

		$value_block_plugins        = get_post_meta( $post->ID, 'block_plugins', 1 );
		$value_link_block_plugins   = array_unique( get_post_meta( $post->ID, 'block_value_plugins', 1 ) );
		$value_block_group_plugins  = get_post_meta( $post->ID, 'block_group_plugins', 1 );
//		$value_selected_post_type   = get_post_meta( $post->ID, 'selected_post_type', 1 );
		$value_selected_page        = get_post_meta( $post->ID, 'selected_page', 1 );
		$value_type_filter          = get_post_meta( $post->ID, 'type_filter', 1 );
		$value_category_filter      = get_post_meta( $post->ID, 'category_filter', 1 );

		?><div style="display: none">
            <label for="block_plugins"> <?= "Select block plugins" ?> </label>
            <input type="text" id="block_plugins" name="block_plugins" value="<?= implode(', ', $value_block_plugins ); ?>" size="25"/>

            <label for="block_link_plugins"> <?= "Select link block plugins" ?> </label>
            <input type="text" id="block_link_plugins" name="block_link_plugins" value="<?= implode(', ', $value_link_block_plugins ); ?>" size="25"/>

            <label for="block_plugins"> <?= "Select block group plugins" ?> </label>
            <input type="text" id="block_group_plugins" name="block_group_plugins" value="<?= implode(', ', $value_block_group_plugins); ?>" size="25"/>

            <!--
            <label for="selected_post_type"> <?/*= "Add post type" */ ?> </label>
            <input type="text" id="selected_post_type" name="selected_post_type" value=" <?/*= $value_selected_post_type */ ?>" size="25"/>
            -->

            <label for="selected_page"> <?= "Add Permalinks" ?> </label>
            <input type="text" id="selected_page" name="selected_page" value=" <?= $value_selected_page ?>" size="25"/>

            <label for="type_filter"> <?= "Set Type" ?></label>
            <input type="text" id="type_filter" name="type_filter" value=" <?= $value_type_filter ?>" size="25"/>


            <label for="category_filter"> <?= "Category Type" ?></label>
            <input type="text" id="category_filter" name="category_filter" value=" <?= $value_category_filter ?>" size="25"/>
        </div><?php
        
        ?><div class="sos-wrap">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="check_all"></th>
                        <th>TITLE</th>
                        <th>cATEGORIES</th>
                        <th>Type</th>
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
                </tbody>
            </table>
        </div><?php

	}

	/**
	 * Save the edited filter
	 */
	function save_filter_options( $post_id ) {
        
        po_mu_plugin()->write_log( $_POST, "save_filter_options-_POST" );

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

		$block_plugins       = array_unique( explode( ', ', sanitize_text_field( $_POST['block_plugins']         ) ) );
		$block_link_plugins  = array_unique( explode( ', ', sanitize_text_field( $_POST['block_link_plugins']    ) ) );
		$block_group_plugins = array_unique( explode( ', ', sanitize_text_field( $_POST['block_group_plugins']   ) ) );
//		$selected_post_type  = sanitize_text_field( $_POST['selected_post_type'] );
		$selected_page       = sanitize_text_field( $_POST['selected_page'] );
		$type_filter         = sanitize_text_field( $_POST['type_filter'] );
		$category_filter     = sanitize_text_field( $_POST['category_filter'] );

		update_post_meta( $post_id, 'block_plugins',        $block_plugins );
		update_post_meta( $post_id, 'block_link_plugins',   $block_link_plugins );
		update_post_meta( $post_id, 'block_group_plugins',  $block_group_plugins );
//		update_post_meta( $post_id, 'selected_post_type',   $selected_post_type );
		update_post_meta( $post_id, 'selected_page',        $selected_page );
		update_post_meta( $post_id, 'type_filter',          $type_filter );
		update_post_meta( $post_id, 'category_filter',      $category_filter );
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

