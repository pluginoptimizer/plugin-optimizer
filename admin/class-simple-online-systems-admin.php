<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Simple_Online_Systems
 * @subpackage Simple_Online_Systems/admin
 * @author     Web Dev <some@some.com>
 */
class Simple_Online_Systems_Admin {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/simple-online-systems-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/simple-online-systems-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'simple_online_systems_groups', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name );

	}

	/**
	 * Add type module for js
	 */
	public function add_type_attribute($tag, $handle, $src) {
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

		add_menu_page( 'Plugin Optimizer', 'Plugin Optimizer', 'manage_options', 'simple_online_systems_overview', array( $this, 'render_overview_page' ), 'dashicons-sos' );
		add_submenu_page( 'simple_online_systems_overview', 'Overview', 'Overview', 'manage_options', 'simple_online_systems_overview', array( $this, 'render_overview_page' ) );
		add_submenu_page( 'simple_online_systems_overview', 'Filters', 'Filters', 'manage_options', 'simple_online_systems_filters', array( $this, 'render_filters_page' ) );
		add_submenu_page( 'simple_online_systems_overview', 'Filters Categories', 'Filters Categories', 'manage_options', 'simple_online_systems_filters_categories', array( $this, 'render_filters_categories_page' ) );
		add_submenu_page( 'simple_online_systems_overview', 'Groups plugin', 'Groups plugin', 'manage_options', 'simple_online_systems_groups', array( $this, 'render_groups_page' ) );
		add_submenu_page( 'simple_online_systems_overview', 'Worklist', 'Worklist', 'manage_options', 'simple_online_systems_worklist', array( $this, 'render_worklist_page' ) );
		add_submenu_page( 'simple_online_systems_overview', 'Settings', 'Settings', 'manage_options', 'simple_online_systems_settings', array( $this, 'render_settings_page' ) );
		add_submenu_page( 'simple_online_systems_overview', 'Support', 'Support', 'manage_options', 'simple_online_systems_support', array( $this, 'render_support_page' ) );

	}


	public function render_overview_page() {
		include 'partials/page-overview.php';
	}

	public function render_filters_page() {
		include 'partials/page-filters.php';
	}

	public function render_filters_categories_page() {
		include 'partials/page-categories.php';
	}

	public function render_groups_page() {
		include 'partials/page-groups.php';
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

	/**
	 * Add Admin-Bar Pages
	 */
	public function add_plugin_in_admin_bar($wp_admin_bar){
		$wp_admin_bar->add_menu( array(
			'id'    => 'plugin_optimizer',
			'title' => '<span class="sos-icon"></span> Plugin Optimizer',
			'href'  => esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_settings')),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_overview',
			'title'  => 'Overview',
			'href'   => esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_overview')),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_filters',
			'title'  => 'Filters',
			'href'   => esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_filters')),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_filters_categories',
			'title'  => 'Filters Categories',
			'href'   => esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_filters_categories')),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_groups',
			'title'  => 'Groups',
			'href'   => esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_groups')),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_worklist',
			'title'  => 'Worklist',
			'href'   => esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_worklist')),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_settings',
			'title'  => 'Settings',
			'href'   => esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_settings')),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_support',
			'title'  => 'Support',
			'href'   => esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_support')),
		) );

	}

	/**
	 * Register all post types
	 */
	public function register_post_types() {

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
			'show_in_menu'  => true,
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
			'show_in_menu'  => true,
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
			'description'   => 'Work for your customers',
			'public'        => true,
			'show_in_menu'  => true,
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

		register_taxonomy( 'Category filters', array( 'sos_filter' ), array(
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
		) );

	}

	/**
	 * Register meta boxes to filter and group
	 */
	public  function register_meta_boxes() {

		add_meta_box( 'sos_filter_options', 'Filter Options', array( $this, 'render_filter_options' ), array( 'sos_filter' ) );
		add_meta_box( 'sos_group_options', 'Group Options', array( $this, 'render_group_options' ), array( 'sos_group' ) );

	}

	/**
	 * Create filter
	 */
	public function ajax_add_plugin_to_filter() {
		$block_group_plugins_get = explode(',',  htmlspecialchars( $_POST[ 'block_group_plugins' ] ));
		$block_group_plugins = '';
		foreach( $block_group_plugins_get as $block_group_plugin ){
			$posts = get_posts( array(
				'post_type'     => 'sos_group',
				's'             => $block_group_plugin,
				'numberposts'   => -1,
			) );
			foreach( $posts as $post ){
				$block_group_plugins .= implode( ",", get_metadata( 'post', $post->ID, 'group_plugins' ) ) . ', ';
			}
		}
		$block_group_plugins = substr($block_group_plugins, 0, -2);

		$block_plugins      = htmlspecialchars( $_POST[ 'block_plugins' ] );
		$post_type          = htmlspecialchars( $_POST[ 'post_type' ] );
		$pages              = htmlspecialchars( $_POST[ 'pages' ] );
		$title_filter       = htmlspecialchars( $_POST[ 'title_filter' ] );
		$type_filter        = htmlspecialchars( $_POST[ 'type_filter' ] );
		$category_filter    = htmlspecialchars( $_POST[ 'category_filter' ] );

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
		add_post_meta( $post_id, 'block_group_plugins', $block_group_plugins );
		add_post_meta( $post_id, 'selected_post_type', $post_type );
		add_post_meta( $post_id, 'selected_page', $pages );
		add_post_meta( $post_id, 'type_filter', $type_filter );
		add_post_meta( $post_id, 'category_filter', $category_filter );

		ob_start();

		$posts = get_posts( array(
			'post_type'   => 'sos_filter',
			'numberposts' => - 1,
		) );

		$this->content_filters($posts);

		wp_send_json_success( ob_get_clean() );

	}


	/**
	 * Edit filter
	 */
	public function render_filter_options( $post ) {

		wp_nonce_field( plugin_basename( __FILE__ ), 'nonce_sos_filter_options' );

		$value_block_plugins          = get_post_meta( $post->ID, 'block_plugins', 1 );
		$value_block_group_plugins    = get_post_meta( $post->ID, 'block_group_plugins', 1 );
		$value_selected_post_type     = get_post_meta( $post->ID, 'selected_post_type', 1 );
		$value_selected_page          = get_post_meta( $post->ID, 'selected_page', 1 );
		$value_type_filter            = get_post_meta( $post->ID, 'type_filter', 1 );
		$value_category_filter        = get_post_meta( $post->ID, 'category_filter', 1 );

		?>
		<label for="block_plugins"> <?= "Select block plugins" ?> </label>
		<input type="text" id="block_plugins" name="block_plugins" value=" <?= $value_block_plugins ?>" size="25" />
		<br>
		<br>

        <label for="block_plugins"> <?= "Select block group plugins" ?> </label>
		<input type="text" id="block_plugins" name="block_plugins" value=" <?= $value_block_group_plugins ?>" size="25" />
		<br>
		<br>

		<label for="selected_post_type"> <?= "Add post type" ?> </label>
		<input type="text" id="selected_post_type" name="selected_post_type" value=" <?= $value_selected_post_type ?>" size="25" />
		<br>
		<br>

		<label for="selected_page"> <?= "Add Permalinks" ?> </label>
		<input type="text" id="selected_page" name="selected_page" value=" <?= $value_selected_page ?>" size="25" />
		<br>
		<br>

		<label for="type_filter"> <?= "Set Type" ?></label>
		<input type="text" id="type_filter" name="type_filter" value=" <?= $value_type_filter ?>" size="25" />
		<br>
		<br>

        <label for="type_filter"> <?= "Category Type" ?></label>
		<input type="text" id="type_filter" name="category_filter" value=" <?= $value_category_filter ?>" size="25" />
		<br>
		<br>

		<?php

	}

	/**
	 * Save the edited filter
	 */
	public function save_filter_options( $post_id ) {

		if ( ! isset( $_POST[ 'block_plugins' ] ) && ! isset( $_POST[ 'block_group_plugins' ] ) && ! isset( $_POST[ 'selected_post_type' ] ) && ! isset( $_POST[ 'selected_page' ] ) && ! isset( $_POST[ 'type_filter' ] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST[ 'nonce_sos_filter_options' ], plugin_basename( __FILE__ ) ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$block_plugins          = sanitize_text_field( $_POST[ 'block_plugins' ] );
		$block_group_plugins    = sanitize_text_field( $_POST[ 'block_group_plugins' ] );
		$selected_post_type     = sanitize_text_field( $_POST[ 'selected_post_type' ] );
		$selected_page          = sanitize_text_field( $_POST[ 'selected_page' ] );
		$type_filter            = sanitize_text_field( $_POST[ 'type_filter' ] );
		$category_filter        = sanitize_text_field( $_POST[ 'category_filter' ] );

		update_post_meta( $post_id, 'block_plugins', $block_plugins );
		update_post_meta( $post_id, 'block_group_plugins', $block_group_plugins );
		update_post_meta( $post_id, 'selected_post_type', $selected_post_type );
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

		if( $posts ) :
			foreach ( $posts as $post ) : ?>
	            <h2>
	                <a href="<?= esc_url( $post->guid ); ?>" class="link_search_page">
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

		$title_group   = htmlspecialchars( $_POST[ 'title_group' ] );
		$type_group    = htmlspecialchars( $_POST[ 'type_group' ] );
		$group_plugins = htmlspecialchars( $_POST[ 'group_plugins' ] );
		$group_parents = htmlspecialchars( $_POST[ 'group_parents' ] );

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

		add_post_meta( $post_id, 'type_group', $type_group );
		add_post_meta( $post_id, 'group_plugins', $group_plugins );
		add_post_meta( $post_id, 'group_parents', $group_parents );

		ob_start();

		$posts = get_posts( array(
			'post_type'   => 'sos_group',
			'numberposts' => -1,
			'meta_query' => array(
				array(
					'key' => 'group_parents',
					'value' => 'None'
				)
			),
		) );

		$this->content_groups($posts);

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Edit group
	 */
	public function render_group_options( $post ) {

		wp_nonce_field( plugin_basename( __FILE__ ), 'nonce_sos_group_options' );

		$value_type_group        = get_post_meta( $post->ID, 'type_group', 1 );
		$value_group_plugins     = get_post_meta( $post->ID, 'group_plugins', 1 );

		?>
		<label for="type_filter"> <?= "Set Type" ?></label>
		<input type="text" id="type_filter" name="type_filter" value=" <?= $value_type_group ?>" size="25" />
		<br>
		<br>

		<label for="block_plugins"> <?= "Select group plugins" ?> </label>
		<input type="text" id="block_plugins" name="block_plugins" value=" <?= $value_group_plugins  ?>" size="25" />
		<br>
		<br>

		<?php
	}


	/**
	 * Save the edited group
	 */
	public function save_group_options( $post_id ) {

		if ( ! isset( $_POST[ 'type_group' ] ) && ! isset( $_POST[ 'group_plugins' ] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST[ 'nonce_sos_group_options' ], plugin_basename( __FILE__ ) ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$type_group         = sanitize_text_field( $_POST[ 'type_group' ] );
		$group_plugins      = sanitize_text_field( $_POST[ 'group_plugins' ] );

		update_post_meta( $post_id, '$type_group', $type_group );
		update_post_meta( $post_id, 'group_plugins', $group_plugins );
	}

	/**
	 * Creating a work after publishing a post or page
	 */
	function add_item_to_worklist( $post_id ) {

		if ( wp_is_post_revision( $post_id ) ){
			return;
		}

		if ( get_post($post_id)->post_status != 'publish' ){
			return;
		}

		$title_work  = 'Add filter to ' . get_post($post_id)->post_title;
		$post_link   = get_post_permalink(get_post($post_id));

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

		$title_work  = 'Add filter to ' .  ucfirst(dirname($plugin));
		$post_link   = $plugin;

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
	 * Content for works
	 */
	function content_works($posts){
		if( $posts ) :
			foreach ( $posts as $post ) : ?>
				<tr>
					<td><input type="checkbox" id="<?= $post->ID; ?>"></td>
					<td><?= $post->post_title; ?></td>
					<td><?= esc_url(implode( '', get_metadata( 'post', $post->ID, 'post_link' ))); ?></td>
					<td><?= substr(str_replace( '-', '/', str_replace(" ", " at ", $post->post_date)), 0 , -3) . ' pm'; ?></td>
					<td>
						<a class="row-title" href="<?= esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_filters&work_title=' . urlencode(str_replace(' ', '_', str_replace('Add filter to ', '', $post->post_title))) . '&work_link=' . urlencode(esc_url(implode( '', get_metadata( 'post', $post->ID, 'post_link' )))))); ?>" aria-label="“<?/*= $post->post_title; */?>” (Edit)">
							<button class="add-filter"><span class="pluse">+</span> add new filter</button>
						</a>
					</td>
				</tr>
			<?php
			endforeach;
		else:
			?>
			<tr>
				<td colspan="5">Not works</td>
			</tr>
		<?php
		endif;
	}

	/**
	 * Content for group plugins
	 */
	function content_groups($posts){
		$all_plugins = Simple_Online_Systems_Helper::get_plugins_with_status();
		$activate_plugins = array();
		$deactivate_plugins = array();
		foreach ($all_plugins as $plugin) {
			foreach ($plugin as $key => $value) {
				if($key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer'){
					if($value){
						array_push($activate_plugins, $plugin['name']);
					} else{
						array_push($deactivate_plugins, $plugin['name']);
					}
				}
			}
		}
		if( $posts ) :
			foreach ( $posts as $post ) :
				$group_plugins = implode( ', ', get_metadata( 'post', $post->ID, 'group_plugins' ));
				?>

                <tr class="block_info">
                    <td><input type="checkbox" id="<?= $post->ID; ?>"></td>
                    <td><?= $post->post_title; ?></td>
                    <td><?= implode( ",", get_metadata( 'post', $post->ID, 'type_group' ) ); ?></td>
                    <td><?= implode( ', ', get_metadata( 'post', $post->ID, 'group_plugins' )); ?></td>
                    <td><?= count(explode(", ", implode( get_metadata( 'post', $post->ID, 'group_plugins' )))); ?></td>
                </tr>
                <tr class="hidden_info">
                    <td colspan="6">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            Plugins <span class="disabled">- Disabled: 2/8</span>
                                        </div>
                                        <span class="count-plugin">( Active: <?= count($activate_plugins);?>   |   Inactive: <?= count($deactivate_plugins); ?> )</span>
                                    </div>
									<?php
									if($activate_plugins):
										?>
                                        <div class="plugin-wrapper">
											<?php
											foreach ($activate_plugins as $activate_plugin):
												?>
                                                <div class="content
                                             <?php
												if(substr_count($group_plugins, $activate_plugin)){
													echo 'block';
												}
												?>
                                             ">
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
				<?php
                if($post->post_status === 'publish'){
	                $posts_chidren = get_posts( array(
		                'post_type'   => 'sos_group',
		                'numberposts' => -1,
		                'meta_query' => array(
			                array(
				                'key' => 'group_parents',
				                'value' => $post->post_title,
			                )
		                ),
	                ) );
                } else if($post->post_status === 'trash'){
	                $posts_chidren = get_posts( array(
		                'post_type'   => 'sos_group',
		                'numberposts' => -1,
		                'post_status' => 'trash',
		                'meta_query'  => array(
			                array(
				                'key'   => 'group_parents',
				                'value' => $post->post_title,
			                )
		                ),
	                ) );
                }


				if( $posts_chidren ) :
					foreach ( $posts_chidren as $post_chidren ) :
						$children_group_plugins = implode( ', ', get_metadata( 'post', $post_chidren->ID, 'group_plugins' ));
						?>

                        <tr class="block_info block_children">
                            <td><input type="checkbox" id="<?= $post_chidren->ID; ?>"></td>
                            <td> — <?= $post_chidren->post_title; ?></td>
                            <td><?= implode( ",", get_metadata( 'post', $post_chidren->ID, 'type_group' ) ); ?></td>
                            <td><?= implode( ', ', get_metadata( 'post', $post_chidren->ID, 'group_plugins' )); ?></td>
                            <td><?= count(explode(", ", implode( get_metadata( 'post', $post_chidren->ID, 'group_plugins' )))); ?></td>
                        </tr>
                        <tr class="hidden_info">
                            <td colspan="6">
                                <div class="content-filter">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="header">
                                                <div class="title">
                                                    Plugins <span class="disabled">- Disabled: 2/8</span>
                                                </div>
                                                <span class="count-plugin">( Active: <?= count($activate_plugins);?>   |   Inactive: <?= count($deactivate_plugins); ?> )</span>
                                            </div>
											<?php
											if($activate_plugins):
												?>
                                                <div class="plugin-wrapper">
													<?php
													foreach ($activate_plugins as $activate_plugin):
														?>
                                                        <div class="content
                                             <?php
														if(substr_count($children_group_plugins, $activate_plugin)){
															echo 'block';
														}
														?>
                                             ">
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
				<td colspan="5">Not group plugins</td>
			</tr>
		<?php
		endif;
	}

	/**
	 * Content for filters categories
	 */
	function content_filters_categories($categories){
		if( $categories ) :
			foreach ( $categories as $cat ) :?>
                <tr class="block_info">
                    <td><input type="checkbox" id="<?= $cat->cat_ID ?>"></td>
                    <td><?= $cat->cat_name; ?></td>
                </tr>
                <?php
				$difference = $cat->cat_ID;
				$subcategories = get_categories( array(
					'parent'       => $difference,
					'taxonomy'      => 'category',
					'type'          => 'sos_filter',
					'hide_empty'    => 0,
				) );
				if( $subcategories ) :
					foreach ( $subcategories as $subcategory ) :?>
                        <tr class="block_info block_children">
                            <td><input type="checkbox" id="<?= $subcategory->cat_ID ?>"></td>
                            <td> — <?= $subcategory->cat_name; ?></td>
                        </tr>
					<?php endforeach;
				endif;
                ?>
			<?php endforeach;
		else:
			?>
			<tr>
				<td colspan="5">Not filters categories</td>
			</tr>
		<?php
		endif;
	}

	/**
	 * Content for filters
	 */
	function content_filters($posts){
		$all_plugins = Simple_Online_Systems_Helper::get_plugins_with_status();
		$activate_plugins = array();
		$deactivate_plugins = array();
		foreach ($all_plugins as $plugin) {
			foreach ($plugin as $key => $value) {
				if($key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer'){
					if($value){
						array_push($activate_plugins, $plugin['name']);
					} else{
						array_push($deactivate_plugins, $plugin['name']);
					}
				}
			}
		}
		if( $posts ) :
			foreach ( $posts as $post ) :
				$block_plugins = implode( ', ', get_metadata( 'post', $post->ID, 'block_plugins' )) . ', ' . implode( get_metadata( 'post', $post->ID, 'block_group_plugins' ));
				?>
				<tr class="block_info" id="filter-<?= $post->ID; ?>">
					<td><input type="checkbox" id="<?= $post->ID; ?>"></td>
					<td><?= $post->post_title; ?></td>
					<td><?= implode( ",", get_metadata( 'post', $post->ID, 'category_filter' ) ); ?></td>
					<td><?= implode( ",", get_metadata( 'post', $post->ID, 'type_filter' ) ); ?></td>
					<td><?= implode( ",", get_metadata( 'post', $post->ID, 'selected_post_type' ) ) . ', '  . implode( get_metadata( 'post', $post->ID, 'selected_page' )); ?></td>
					<td><?= implode( ', ', get_metadata( 'post', $post->ID, 'block_plugins' )) . ', ' . implode( get_metadata( 'post', $post->ID, 'block_group_plugins' )); ?></td>
				</tr>
				<tr class="hidden_info">
					<td colspan="6">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-4">
                                    <div class="header">Type</div>
                                    <div>
                                        <div class="content">
                                            <span><?= implode( ",", get_metadata( 'post', $post->ID, 'type_filter' ) ); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="header">Permalinks</div>
                                    <div class="content-permalinks">
                                        <div class="link">
                                            <span><?= implode( get_metadata( 'post', $post->ID, 'selected_page' )); ?></span>
                                        </div>
                                        <button class="add-filter add-permalink"><span class="pluse">+</span> Permalink</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            Plugins <span class="disabled">- Disabled: 2/8</span>
                                        </div>
                                        <span class="count-plugin">( Active: <?= count($activate_plugins);?>   |   Inactive: <?= count($deactivate_plugins); ?> )</span>
                                    </div>
                                    <?php
                                    if($activate_plugins):
	                                    ?>
                                        <div class="plugin-wrapper">
                                        <?php
                                        foreach ($activate_plugins as $activate_plugin):
                                            ?>
                                            <div class="content
                                             <?php
                                            if(substr_count($block_plugins, $activate_plugin)){
                                                echo 'block';
                                            }
                                            ?>
                                             ">
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
                                                    <span>No activate plugins for blocking</span>
                                                </div>
                                            </div>
                                        <?php
                                    endif;
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            groups <span class="disabled">- Disabled: 1/2</span>
                                        </div>
                                    </div>
                                    <div class="plugin-wrapper">
	                                    <?php
	                                    $groups = get_posts( array(
		                                    'post_type'   => 'sos_group',
		                                    'numberposts' => -1,
	                                    ) );
                                        if( $groups ) :
                                            foreach ( $groups as $group ) :
	                                    ?>
                                                <div class="content
                                                <?php
                                                    if(implode( ",", get_metadata( 'post', $group->ID, 'group_plugins' ) ) === implode( get_metadata( 'post', $post->ID, 'block_group_plugins' ))){
                                                        echo 'block';
                                                    }
                                                ?>
                                                ">
                                                    <span><?= $group->post_title; ?></span>
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
                                        $this->ajax_create_category($post);
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
				<td colspan="6">Not filtres</td>
			</tr>
		<?php
		endif;
	}

	/**
	 * Search elements
	 */
	public function ajax_search_elements(){
		$name_post_type  = htmlspecialchars( $_POST[ 'name_post_type' ] );
		$type_works  = htmlspecialchars( $_POST[ 'type_works' ] );

		ob_start();
		if($type_works === 'all'){
			$posts = get_posts( array(
				'post_type'   => $name_post_type,
				'numberposts' => - 1,
				's' => esc_attr( $_POST['keyword'] ),
			) );

			if($name_post_type === 'sos_work'){
				$this->content_works($posts);
			} elseif($name_post_type === 'sos_filter'){
				$this->content_filters($posts);
			} elseif($name_post_type === 'sos_group'){
				$this->content_groups($posts);
			}
		} else {
			$posts = get_posts( array(
				'post_type'   => $name_post_type,
				'numberposts' => - 1,
				'post_status' => 'trash',
				's' => esc_attr( $_POST['keyword'] ),
			) );

			if($name_post_type === 'sos_work'){
				$this->content_works($posts);
			} elseif($name_post_type === 'sos_filter'){
				$this->content_filters($posts);
			} elseif($name_post_type === 'sos_group'){
				$this->content_groups($posts);
			}
		}

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Show all elements
	 */
	public function ajax_all_elements(){
		$name_post_type  = htmlspecialchars( $_POST[ 'name_post_type' ] );
		ob_start();

		$posts = get_posts( array(
			'post_type'   => $name_post_type,
			'numberposts' => - 1,
		) );

		if($name_post_type === 'sos_work'){
			$this->content_works($posts);
		} elseif($name_post_type === 'sos_filter'){
			$this->content_filters($posts);
		} elseif($name_post_type === 'sos_group'){
			$posts = get_posts( array(
				'post_type'   => 'sos_group',
				'numberposts' => -1,
				'meta_query' => array(
					array(
						'key' => 'group_parents',
						'value' => 'None'
					)
				),
			) );
			$this->content_groups($posts);
		}

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Show trash elements
	 */
    public function ajax_trash_elements(){
	    $name_post_type  = htmlspecialchars( $_POST[ 'name_post_type' ] );
	    ob_start();

	    $posts = get_posts( array(
		    'post_type'   => $name_post_type,
			'numberposts' => - 1,
			'post_status' => 'trash',
		) );

        if($name_post_type === 'sos_work'){
	        $this->content_works($posts);
        } elseif($name_post_type === 'sos_filter'){
	        $this->content_filters($posts);
        } elseif($name_post_type === 'sos_group'){
	        $posts = get_posts( array(
		        'post_type'   => 'sos_group',
		        'numberposts' => -1,
		        'post_status' => 'trash',
		        'meta_query' => array(
			        array(
				        'key' => 'group_parents',
				        'value' => 'None'
			        )
		        ),
	        ) );
	        $this->content_groups($posts);
        }

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Delete elements
	 */
	public function ajax_delete_elements(){
		$name_post_type  = htmlspecialchars( $_POST[ 'name_post_type' ] );
		$id_works  = htmlspecialchars( $_POST[ 'id_works' ] );
		$type_works  = htmlspecialchars( $_POST[ 'type_works' ] );

		if($type_works === 'all'){
			$posts = get_posts( array(
				'post_type'   =>$name_post_type,
				'include'     => $id_works,
			) );

			foreach ($posts as $post) {
				wp_trash_post( $post->ID);
			}
		    $this->ajax_all_elements();
        } else {
			$posts = get_posts( array(
				'post_type'   =>$name_post_type,
				'include'     => $id_works,
				'post_status' => 'trash',
			) );

			foreach ($posts as $post) {
				wp_delete_post( $post->ID, true );
			}
			$this->ajax_trash_elements();
        }
	}

	/**
	 * Restore works
	 */
	public function ajax_publish_elements(){
		$name_post_type  = htmlspecialchars( $_POST[ 'name_post_type' ] );
		$id_works  = htmlspecialchars( $_POST[ 'id_works' ] );

		$posts = get_posts( array(
			'post_type'   => $name_post_type,
			'include'     => $id_works,
			'post_status' => 'trash',
		) );

		foreach ($posts as $post) {
			wp_publish_post( $post->ID );
		}
		$this->ajax_trash_elements();
	}

	/**
	 * Show count elements
	 */
	public function ajax_count_elements(){
		$name_post_type  = htmlspecialchars( $_POST[ 'name_post_type' ] );

		$return = array(
			'all'   => wp_count_posts($name_post_type)->publish,
			'trash' =>  wp_count_posts($name_post_type)->trash,
		);
		wp_send_json_success( $return );

	}

	/**
	 * Create new category
	 */

	public function ajax_create_category($post){
	    if($post && !is_numeric($post)){
		    $id_filter = $post->ID;
	    } elseif(is_numeric($post)) {
		    $id_filter = $post;
	    } else {
		    $id_filter = htmlspecialchars( $_POST[ 'id_filter' ] );
		    $name_category = htmlspecialchars( $_POST[ 'name_category' ] );

		    wp_set_object_terms( $id_filter, $name_category, 'category' );
	    }


		ob_start();

		$categories = get_categories( [
			'taxonomy'      => 'category',
			'type'          => 'sos_filter',
			'hide_empty'    => 0,
		] );

		if( $categories ):
			foreach( $categories as $cat ):
				?>
                <div class="content filter-category">
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
		if($post && !is_numeric($post)){
			echo ob_get_clean();
		} else {
			wp_send_json_success( ob_get_clean() );
		}

	}

	/**
	 * Delete category
	 */

	public function ajax_delete_category(){
		$cat_ID = htmlspecialchars( $_POST[ 'id_category' ] );
		$id_filter = htmlspecialchars( $_POST[ 'id_filter' ] );

		wp_delete_category( $cat_ID );

		$this->ajax_create_category($id_filter);
	}

	/**
	 * Check name group
	 */

	public function ajax_check_name_group(){
		$name_group = htmlspecialchars( $_POST[ 'name_group' ] );
		$posts = get_posts( array(
			'post_type'   => 'sos_group',
			'numberposts' => -1,
		) );

		$names_group = array();

		if( $posts ) {
			foreach ( $posts as $post ) {
				array_push($names_group, $post->post_title);
			}
		} else {
			wp_send_json_success( 'nothing' );
		}
		if ( in_array($name_group, $names_group) ) {
			wp_send_json_success( true );
		} else {
			wp_send_json_success( false );
		}
	}

}

