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
		copy(__DIR__ . '/class-plugin-optimizer-mu.php', WPMU_PLUGIN_DIR.'/class-plugin-optimizer-mu.php' );
		self::add_elements_to_worklist();
	}

	/**
	 * Add posts, pages, plugins
	 */
	public function add_elements_to_worklist(){
//		Add posts
		$posts = get_posts();
		foreach( $posts as $post ){
			$title_work = 'Add filter to ' . get_post( $post->ID )->post_title;
			$post_link  =  get_permalink( $post->ID );

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
		foreach( $pages as $page ){
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
		$all_plugins        = Plugin_Optimizer_Helper::get_plugins_with_status();

		foreach ($all_plugins as $plugin ) {
			$title_work = 'Add filter to ' . ucfirst( dirname( $plugin["file"] ) );
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

}
