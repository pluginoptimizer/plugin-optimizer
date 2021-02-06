<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Optimizer
 * @subpackage Plugin_Optimizer/includes
 * @author     Web Dev <some@some.com>
 */
class Plugin_Optimizer_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 */
	public static function activate() {
		copy( __DIR__ . '/class-plugin-optimizer-mu.php', WPMU_PLUGIN_DIR . '/class-plugin-optimizer-mu.php' );
		// self::add_elements_to_worklist();
		self::create_table();
		self::insert_posts();
	}

	/**
	 * Add posts, pages, plugins
	 */
	public static function add_elements_to_worklist() {
//		Add posts
		$posts = get_posts();
		foreach ( $posts as $post ) {
			$title_work = 'Add filter to ' . get_post( $post->ID )->post_title;
			$post_link  = get_permalink( $post->ID );

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

//		Add pages
		$pages = get_pages();
		foreach ( $pages as $page ) {
			$title_work = 'Add filter to ' . $page->post_title;
			$post_link  = get_page_link( $page->ID );

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


//		Add plugins
		$all_plugins = Plugin_Optimizer_Helper::get_plugins_with_status();

		foreach ( $all_plugins as $plugin ) {
			$title_work = 'Add filter to ' . $plugin["name"];
			$post_link  = $plugin["file"];

			if ( $plugin["file"] !== 'plugin-optimizer/plugin-optimizer.php' ) {
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
	}

	/**
	 * Create db table
	 *
	 */
	public static function create_table() {
		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$table_name      = $wpdb->get_blog_prefix() . 'post_links';
		$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

		$sql = "CREATE TABLE {$table_name} (
		    id  bigint(20) unsigned NOT NULL auto_increment,
		    audit varchar(20) NOT NULL default '',
		    name_post longtext NOT NULL default '',
		    type_post longtext NOT NULL default '',
		    permalinks_post longtext NOT NULL default '',
		    PRIMARY KEY  (id),
			KEY audit (audit)
			)
			{$charset_collate};";

		dbDelta( $sql );
	}


	/**
	 * Inserting data in db
	 *
	 */
	public static function insert_posts() {
		global $wpdb;

		$table_name = $wpdb->get_blog_prefix() . 'post_links';

		$post_types         = get_post_types( [ 'publicly_queryable' => 1 ] );
		$post_types['page'] = 'page';
		unset( $post_types['attachment'], $post_types['sos_filter'], $post_types['sos_group'], $post_types['sos_work'] );

		foreach ( $post_types as $post_type ) {
			$posts = get_posts( array(
				'post_type'   => $post_type,
				'numberposts' => - 1,
			) );

			foreach ( $posts as $post ) {
				$wpdb->insert(
					$table_name,
					array(
						'audit'           => 1,
						'name_post'       => $post->post_title,
						'type_post'       => $post->post_type,
						'permalinks_post' => get_permalink( $post->ID ),
					),
					array( '%d', '%s', '%s', '%s' )
				);
			}
		}
	}


}
