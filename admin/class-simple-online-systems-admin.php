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
		wp_enqueue_style( $this->plugin_name .'-bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {

		/*wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/simple-online-systems-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'simple_online_systems_groups', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name );*/

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/simple-online-systems-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'simple_online_systems_groups', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}

	/**
	 * Add Menu Pages
	 */
	public function add_menu_pages() {

		add_menu_page( 'Plugin Optimizer', 'Plugin Optimizer', 'manage_options', 'simple_online_systems_settings', array( $this, 'render_settings_page' ), 'dashicons-sos' );
		add_submenu_page( 'simple_online_systems_settings', 'Overview', 'Overview', 'manage_options', 'simple_online_systems_overview', array( $this, 'render_overview_page' ) );
		add_submenu_page( 'simple_online_systems_settings', 'Filters', 'Filters', 'manage_options', 'simple_online_systems_filters', array( $this, 'render_filters_page' ) );
		add_submenu_page( 'simple_online_systems_settings', 'Groups plugin', 'Groups plugin', 'manage_options', 'simple_online_systems_groups', array( $this, 'render_groups_page' ) );
		add_submenu_page( 'simple_online_systems_settings', 'Settings', 'Settings', 'manage_options', 'simple_online_systems_settings', array( $this, 'render_settings_page' ) );
		add_submenu_page( 'simple_online_systems_settings', 'Worklist', 'Worklist', 'manage_options', 'simple_online_systems_worklist', array( $this, 'render_worklist_page' ) );
		add_submenu_page( 'simple_online_systems_settings', 'Support', 'Support', 'manage_options', 'simple_online_systems_support', array( $this, 'render_support_page' ) );

	}


	public function render_overview_page() {
		include 'partials/page-overview.php';
	}

	public function render_filters_page() {
		include 'partials/page-filters.php';
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
			'id'     => 'plugin_optimizer_groups',
			'title'  => 'Groups',
			'href'   => esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_groups')),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_settings',
			'title'  => 'Settings',
			'href'   => esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_settings')),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'plugin_optimizer',
			'id'     => 'plugin_optimizer_worklist',
			'title'  => 'Worklist',
			'href'   => esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_worklist')),
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

	public  function register_meta_boxes() {

		add_meta_box( 'sos_filter_options', 'Filter Options', array( $this, 'render_filter_options' ), array( 'sos_filter' ) );
		add_meta_box( 'sos_group_options', 'Group Options', array( $this, 'render_group_options' ), array( 'sos_group' ) );

	}

	public function ajax_add_plugin_to_filter( $block_group_plugins_value ) {
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

		foreach ( $posts as $post ) : ?>
            <tr id="post-205" class="iedit author-self level-0 post-205 type-sos_work status-publish hentry pmpro-has-access">
                <th scope="row" class="check-column"> <label class="screen-reader-text" for="cb-select-205">
                        Select <?= $post->post_title; ?> </label>
                    <input id="cb-select-205" type="checkbox" name="post[]" value="205">
                    <div class="locked-indicator">
                        <span class="locked-indicator-icon" aria-hidden="true"></span>
                        <span class="screen-reader-text">
                                “<?= $post->post_title; ?>” is locked </span>
                    </div>
                </th>
                <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                    <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                    <strong>
                        <a class="row-title" href="<?= esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_filters&work_title=' . urlencode(str_replace(' ', '_', str_replace('Add filter to ', '', $post->post_title))) . '&work_link=' . urlencode(implode( '', get_metadata( 'post', $post->ID, 'post_link' ))))); ?>" aria-label="“<?= $post->post_title; ?>” (Edit)">
							<?= $post->post_title; ?>
                        </a>
                    </strong>

                    <div class="row-actions">
						<span class="edit">
							<a href="<?= esc_url(get_edit_post_link($post->ID)); ?>" aria-label="Edit “<?= $post->post_title; ?>”">Edit</a> |
						</span>
                        <span class="trash">
							<a href="<?= esc_url(get_delete_post_link($post->ID)); ?>" class="submitdelete" aria-label="Move “<?= $post->post_title; ?>” to the Trash">
								Trash
							</a>
						</span>
                        <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
                </td>
                <td class="date column-date" data-colname="Date">Published<br><?= substr(str_replace( '-', '/', str_replace(" ", " at ", $post->post_date)), 0 , -3) . ' pm'; ?></td>
            </tr>
		<?php endforeach;

		wp_send_json_success( ob_get_clean() );

	}


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


	public function ajax_search_filters(){

		ob_start();

		$posts = get_posts( array(
			'post_type'   => 'sos_filter',
			'numberposts' => - 1,
			's'           => esc_attr( $_POST['keyword'] ),
		) );

		foreach ( $posts as $post ) : ?>
            <tr id="tag-7" class="level-0">
                <th scope="row" class="check-column"><label class="screen-reader-text" for="cb-select-7">Select <?= $post->post_title; ?></label><input type="checkbox" name="delete_tags[]" value="7" id="cb-select-7"></th>
                <td class="name column-name has-row-actions column-primary" data-colname="Name"><strong><a class="row-title" href="<?= esc_url(get_edit_post_link($post->ID)); ?>" aria-label="“<?= $post->post_title; ?>” (Edit)"><?= $post->post_title; ?></a></strong><br>
                    <div class="hidden" id="inline_7">
                        <div class="name"><?= $post->post_title; ?></div>
                        <div class="slug"><?= $post->post_title; ?></div>
                        <div class="parent">0</div>
                    </div>
                    <div class="row-actions"><span class="edit"><a href="<?= esc_url(get_edit_post_link($post->ID)); ?>" aria-label="Edit “<?= $post->post_title; ?>”">Edit</a> | </span><span class="inline hide-if-no-js"><button type="button" class="button-link editinline" aria-label="Quick edit “<?= $post->post_title; ?>” inline" aria-expanded="false">Quick&nbsp;Edit</button> | </span><span class="delete"><a href="<?= esc_url(get_delete_post_link($post->ID)); ?>" class="delete-tag aria-button-if-js" aria-label="Delete “<?= $post->post_title; ?>”" role="button">Delete</a></span>
                    </div>
                    <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span>
                    </button>
                </td>
                <td class="description column-description" data-colname="Selected pages"><span aria-hidden="true"><?= implode( ",", get_metadata( 'post', $post->ID, 'selected_post_type' ) ) . ', '  . implode( get_metadata( 'post', $post->ID, 'selected_page' )); ?></span><span class="screen-reader-text">No description</span></td>
                <td class="slug column-slug" data-colname="Block plugins"><?= implode( ', ', get_metadata( 'post', $post->ID, 'block_plugins' )); ?></td>
                <td class="posts column-posts" data-colname="Count">
					<?php
					$selected_post_types = explode(', ', implode( ",", get_metadata( 'post', $post->ID, 'selected_post_type' ) ));
					$count_posts = 0;

					foreach( $selected_post_types as $selected_post_type ):
						$count_posts += wp_count_posts($selected_post_type)->publish;
					endforeach;
					echo $count_posts + count(explode(", ", implode( get_metadata( 'post', $post->ID, 'selected_page' )))); ?>
                </td>
            </tr>
		<?php endforeach;

		wp_send_json_success( ob_get_clean() );

	}

	//	groups
	public function ajax_add_group_plugins() {

		$title_group   = htmlspecialchars( $_POST[ 'title_group' ] );
		$type_group    = htmlspecialchars( $_POST[ 'type_group' ] );
		$group_plugins = htmlspecialchars( $_POST[ 'group_plugins' ] );

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

		ob_start();

		$posts = get_posts( array(
			'post_type'   => 'sos_group',
			'numberposts' => - 1,
		) );

		foreach ( $posts as $post ) : ?>
            <tr id="tag-7" class="level-0">
                <th scope="row" class="check-column"><label class="screen-reader-text" for="cb-select-7">Select <?= $post->post_title; ?></label><input type="checkbox" name="delete_tags[]" value="7" id="cb-select-7"></th>
                <td class="name column-name has-row-actions column-primary" data-colname="Name"><strong><a class="row-title" href="<?= get_edit_post_link($post->ID); ?>" aria-label="“<?= $post->post_title; ?>” (Edit)"><?= $post->post_title; ?></a></strong><br>
                    <div class="hidden" id="inline_7">
                        <div class="name"><?= $post->post_title; ?></div>
                        <div class="slug"><?= $post->post_title; ?></div>
                        <div class="parent">0</div>
                    </div>
                    <div class="row-actions"><span class="edit"><a href="<?= esc_url(get_edit_post_link($post->ID)); ?>" aria-label="Edit “<?= $post->post_title; ?>”">Edit</a> | </span><span class="inline hide-if-no-js"><button type="button" class="button-link editinline" aria-label="Quick edit “<?= $post->post_title; ?>” inline" aria-expanded="false">Quick&nbsp;Edit</button> | </span><span class="delete"><a href="<?= esc_url(get_delete_post_link($post->ID)); ?>" class="delete-tag aria-button-if-js" aria-label="Delete “<?= $post->post_title; ?>”" role="button">Delete</a></span>
                    </div>
                    <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span>
                    </button>
                </td>
                <td class="description column-description" data-colname="Type groups"><span aria-hidden="true"><?= implode( ",", get_metadata( 'post', $post->ID, 'type_group' ) ); ?></span><span class="screen-reader-text">No description</span></td>
                <td class="slug column-slug" data-colname="Group plugins"><?= implode( ', ', get_metadata( 'post', $post->ID, 'group_plugins' )); ?></td>
                <td class="posts column-posts" data-colname="Count">
					<?= count(explode(", ", implode( get_metadata( 'post', $post->ID, 'group_plugins' )))); ?>
                </td>
            </tr>
		<?php endforeach;

		wp_send_json_success( ob_get_clean() );

	}

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

	public function ajax_search_works(){

		ob_start();

		$posts = get_posts( array(
			'post_type'   => 'sos_work',
			'numberposts' => - 1,
			's' => esc_attr( $_POST['keyword'] ),
		) );

		foreach ( $posts as $post ) : ?>
            <tr id="post-205" class="iedit author-self level-0 post-205 type-sos_work status-publish hentry pmpro-has-access">
                <th scope="row" class="check-column"> <label class="screen-reader-text" for="cb-select-205">
                        Select <?= $post->post_title; ?> </label>
                    <input id="cb-select-205" type="checkbox" name="post[]" value="205">
                    <div class="locked-indicator">
                        <span class="locked-indicator-icon" aria-hidden="true"></span>
                        <span class="screen-reader-text">
                                “<?= $post->post_title; ?>” is locked </span>
                    </div>
                </th>
                <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                    <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                    <strong>
                        <a value="<?= implode( '', get_metadata( 'post', $post->ID, 'post_link' )); ?>" class="row-title" href="<?= get_admin_url(null, 'admin.php?page=simple_online_systems_filters&work=' . str_replace(' ', '_', str_replace('Add filter to ', '', $post->post_title))) ?>" aria-label="“<?= $post->post_title; ?>” (Edit)">
							<?= $post->post_title; ?>
                        </a>
                    </strong>

                    <div class="row-actions">
						<span class="edit">
							<a href="<?= esc_url(get_edit_post_link($post->ID)); ?>" aria-label="Edit “<?= $post->post_title; ?>”">Edit</a> |
						</span>
                        <span class="trash">
							<a href="<?= esc_url(get_delete_post_link($post->ID)); ?>" class="submitdelete" aria-label="Move “<?= $post->post_title; ?>” to the Trash">
								Trash
							</a>
						</span>
                        <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
                </td>
                <td class="date column-date" data-colname="Date">Published<br><?= substr(str_replace( '-', '/', str_replace(" ", " at ", $post->post_date)), 0 , -3) . ' pm'; ?></td>
            </tr>
		<?php endforeach;

		wp_send_json_success( ob_get_clean() );

	}

}

