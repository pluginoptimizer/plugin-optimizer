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
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/simple-online-systems-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'filter_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name );
	}

	/**
	 * Add Menu Pages
	 */
	public function add_menu_pages() {
		add_menu_page( 'SOS', 'SOS', 'manage_options', 'simple_online_systems_settings', array( $this, 'render_settings_page' ), 'dashicons-sos' );
		add_submenu_page( 'simple_online_systems_settings', 'General Settings', 'General Settings', 'manage_options', 'simple_online_systems_settings' );
		add_submenu_page( 'simple_online_systems_settings', 'Filters', 'Filters', 'manage_options', 'simple_online_systems_filters', array( $this, 'render_filters_page' ) );
	}

	public function render_settings_page() {
		include 'partials/page-settings-template.php';
	}

	public function render_filters_page() {
		include 'partials/page-filters.php';
	}

	/**
	 * Register all post types
	 */
	public function register_post_types() {
		// add FAQ
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
			'menu_icon'     => 'dashicons-editor-help',
			'hierarchical'  => false,
			'supports'      => [ 'title', 'editor', 'custom-fields' ],
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
		register_taxonomy( 'Group', array( 'sos_filter' ), array(
			'hierarchical' => true,
			'labels'       => array(
				'name'              => _x( 'Groups', 'taxonomy general name' ),
				'singular_name'     => _x( 'Group', 'taxonomy singular name' ),
				'search_items'      => __( 'Search Groups' ),
				'all_items'         => __( 'All Groups' ),
				'parent_item'       => __( 'Parent Group' ),
				'parent_item_colon' => __( 'Parent Group:' ),
				'edit_item'         => __( 'Edit Group' ),
				'update_item'       => __( 'Update Group' ),
				'add_new_item'      => __( 'Add New Group' ),
				'new_item_name'     => __( 'New Group Name' ),
				'menu_name'         => __( 'Group' ),
			),
			'show_ui'      => true,
			'query_var'    => true,
		) );
	}

	function ajax_add_plugin_to_filter() {
		$block_plugins = htmlspecialchars( $_POST[ 'block_plugins' ] );
		$post_type     = htmlspecialchars( $_POST[ 'post_type' ] );
		$pages         = htmlspecialchars( $_POST[ 'pages' ] );
		$title_filter  = htmlspecialchars( $_POST[ 'title_filter' ] );
		$type_filter   = htmlspecialchars( $_POST[ 'type_filter' ] );

		$post_data = array(
			'post_title'  => $title_filter,
			'post_type'   => 'sos_filter',
			'post_status' => 'publish',
			'post_author' => 1,
		);

		$post_id = wp_insert_post( $post_data, true );

		if ( is_wp_error( $post_id ) ) {
            wp_send_json_error( $post_id->get_error_message() );
		} else {
			add_post_meta( $post_id, 'block_plugins', $block_plugins );
			add_post_meta( $post_id, 'selected_post_type', $post_type );
			add_post_meta( $post_id, 'selected_page', $pages );
			add_post_meta( $post_id, 'type_filter', $type_filter );
		}

        ob_start();
        $posts = get_posts( array(
            'post_type' => 'sos_filter',
            'numberposts' => -1,
        ) );
        foreach( $posts as $post ){
            setup_postdata($post);
            ?>
            <tr id="tag-7" class="level-0">
                <th scope="row" class="check-column"><label class="screen-reader-text" for="cb-select-7">Select <?= $post->post_title; ?></label><input
                            type="checkbox" name="delete_tags[]" value="7" id="cb-select-7"></th>
                <td class="name column-name has-row-actions column-primary" data-colname="Name"><strong><a
                                class="row-title" href="<?= get_edit_post_link(); ?>"
                                aria-label="“<?= $post->post_title; ?>” (Edit)"><?= $post->post_title; ?></a></strong><br>
                    <div class="hidden" id="inline_7">
                        <div class="name"><?= $post->post_title; ?></div>
                        <div class="slug"><?= $post->post_title; ?></div>
                        <div class="parent">0</div>
                    </div>
                    <div class="row-actions"><span class="edit"><a
                                    href="<?= get_edit_post_link(); ?>"
                                    aria-label="Edit “<?= $post->post_title; ?>”">Edit</a> | </span><span
                                class="inline hide-if-no-js"><button type="button"
                                                                     class="button-link editinline"
                                                                     aria-label="Quick edit “<?= $post->post_title; ?>” inline"
                                                                     aria-expanded="false">Quick&nbsp;Edit</button> | </span><span
                                class="delete"><a href="<?= get_delete_post_link(); ?>"
                                                  class="delete-tag aria-button-if-js"
                                                  aria-label="Delete “<?= $post->post_title; ?>”" role="button">Delete</a></span>
                    </div>
                    <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span>
                    </button>
                </td>
                <td class="description column-description" data-colname="Selected pages"><span
                            aria-hidden="true"><?= implode( ",", get_metadata( 'post', $post->ID, 'selected_post_type' ) ); ?></span><span
                            class="screen-reader-text">No description</span></td>
                <td class="slug column-slug"
                    data-colname="Block plugins"><?= implode( ",", get_metadata( 'post', $post->ID, 'block_plugins' ) ); ?></td>
                <td class="posts column-posts"
                    data-colname="Count"><?= wp_count_posts()->publish; ?></td>
            </tr>
            <?php
        }

        wp_reset_postdata();
        $data = ob_get_clean();
        wp_send_json_success($data);
		die;
	}

}



