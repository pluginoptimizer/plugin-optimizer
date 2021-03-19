<?php
/**
 * The admin ajax functionality of the plugin.
 *
 * @package    PluginOptimizer
 * @subpackage PluginOptimizer/admin
 * @author     Simple Online Systems <admin@simpleonlinesystems.com>
 */

class SOSPO_Ajax {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	function __construct() {

        $this->load_hooks();
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 *
	 * @access   private
	 */
	function load_hooks() {

		add_action( 'wp_ajax_po_save_filter',                   [ $this, 'po_save_filter'                   ] );
		add_action( 'wp_ajax_po_save_group',                    [ $this, 'po_save_group'                    ] );
		add_action( 'wp_ajax_po_save_category',                 [ $this, 'po_save_category'                 ] );
		add_action( 'wp_ajax_po_create_category',               [ $this, 'po_create_category'               ] );
		add_action( 'wp_ajax_po_delete_elements',               [ $this, 'po_delete_elements'               ] );
		add_action( 'wp_ajax_po_publish_elements',              [ $this, 'po_publish_elements'              ] );
		add_action( 'wp_ajax_po_mark_tab_complete',             [ $this, 'po_mark_tab_complete'             ] );
		add_action( 'wp_ajax_po_save_option_alphabetize_menu',  [ $this, 'po_save_option_alphabetize_menu'  ] );
		add_action( 'wp_ajax_po_turn_off_filter',               [ $this, 'po_turn_off_filter'               ] );
		add_action( 'wp_ajax_po_save_original_menu',            [ $this, 'po_save_original_menu'            ] );

	}

	/**
	 * Create/Update filter
	 */
	function po_save_filter() {
        
        if( empty( $_POST['data'] ) ){              wp_send_json_error( [ "message" => "The data never reached the server!" ] ); }
        
        parse_str( $_POST['data'], $array);
        
        if( empty( $array['SOSPO_filter_data'] ) ){ wp_send_json_error( [ "message" => "The data never reached the server!" ] ); }
        
        
        $data = SOSPO_Admin_Helper::format__save_filter_data( $array['SOSPO_filter_data'] );
        
        // sospo_mu_plugin()->write_log( $_POST, "po_save_filter-_POST" );
        // sospo_mu_plugin()->write_log( $data,  "po_save_filter-data"  );
        
        if( is_wp_error( $data ) ){
            
            wp_send_json_error( [ "message" => $data->get_error_message() ] );
        }
        
        
		$post_data = array(
			'post_title'  => $data["title"],
			'post_type'   => 'sos_filter',
			'post_status' => 'publish',
			'post_author' => 1,// TODO get_current_user_id() with localize_script in enqueue function
		);
        
        if( ! empty( $data["ID"] ) ){
            $post_data["ID"] = $data["ID"];
        }

		$post_id = wp_insert_post( $post_data, true );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( [ "message" => $post_id->get_error_message() ] );
		}



        if( ! empty( $data["categories"] ) ){
            
            $category_ids = array_map( 'intval', array_keys( $data["categories"] ) );
            
            $set_categories = wp_set_object_terms( $post_id, $category_ids, "сategories_filters" );
            
        }
        
        
        
        foreach( $data["meta"] as $meta_key => $meta_value ){
            
            update_post_meta( $post_id, $meta_key, $meta_value );
        }
        
        
        
		wp_send_json_success( [ "message" => "All good, the filter is saved.", "id" => $post_id, ] );

	}

	/**
	 * Create/Update Group
	 */
	function po_save_group() {
        
        if( empty( $_POST['data'] ) ){              wp_send_json_error( [ "message" => "The data never reached the server!" ] ); }
        
        parse_str( $_POST['data'], $array);
        
        if( empty( $array['SOSPO_filter_data'] ) ){ wp_send_json_error( [ "message" => "The data never reached the server!" ] ); }
        
        
        $data = SOSPO_Admin_Helper::format__save_group_data( $array['SOSPO_filter_data'] );
        
        // sospo_mu_plugin()->write_log( $_POST, "po_save_group-_POST" );
        // sospo_mu_plugin()->write_log( $data,  "po_save_group-data"  );
        
        if( is_wp_error( $data ) ){
            
            wp_send_json_error( [ "message" => $data->get_error_message() ] );
        }
        
        
		$post_data = array(
			'post_title'  => $data["title"],
			'post_type'   => 'sos_group',
			'post_status' => 'publish',
			'post_author' => 1,// TODO get_current_user_id() with localize_script in enqueue function
		);
        
        if( ! empty( $data["ID"] ) ){
            $post_data["ID"] = $data["ID"];
        }

		$post_id = wp_insert_post( $post_data, true );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( [ "message" => $post_id->get_error_message() ] );
		}

        foreach( $data["meta"] as $meta_key => $meta_value ){
            
            update_post_meta( $post_id, $meta_key, $meta_value );
        }
        
        
		wp_send_json_success( [ "message" => "All good, the group is saved.", "id" => $post_id, ] );

	}

	/**
	 * Create/Update Category
	 */
	function po_save_category() {
        
        if( empty( $_POST['data'] ) ){              wp_send_json_error( [ "message" => "The data never reached the server!" ] ); }
        
        parse_str( $_POST['data'], $array);
        
        if( empty( $array['SOSPO_filter_data'] ) ){ wp_send_json_error( [ "message" => "The data never reached the server!" ] ); }
        
        
        $data = SOSPO_Admin_Helper::format__save_category_data( $array['SOSPO_filter_data'] );
        
        // sospo_mu_plugin()->write_log( $_POST, "po_save_category-_POST" );
        // sospo_mu_plugin()->write_log( $data,  "po_save_category-data"  );
        
        if( is_wp_error( $data ) ){
            
            wp_send_json_error( [ "message" => $data->get_error_message() ] );
        }
        
        
        if( empty( $data["ID"] ) ){
            
            $category = wp_create_term( $data["name"], "сategories_filters" );
            
            if( is_wp_error( $category ) ){
                wp_send_json_error( [ "message" => "An error occured: " . $category->get_error_message() ] );
            }
            
            $term_id = $category["term_id"];
            
        } else {
            
            $term_id = $data["ID"];
            unset( $data["ID"] );
        }
        
        $category = wp_update_term( $term_id, "сategories_filters", $data );
        
        if( is_wp_error( $category ) ){
            wp_send_json_error( [ "message" => "An error occured: " . $category->get_error_message() ] );
        }
        
		wp_send_json_success( [ "message" => "All good, the category is saved.", "id" => $category["term_id"], ] );

	}

	/**
	 * Create new category
	 */
	function po_create_category(){
        
        // sospo_mu_plugin()->write_log( $_POST, "po_create_category-_POST" );
        
        if( empty( $_POST['category_name'] ) ){
            wp_send_json_error( [ "message" => "Category name is a required field!" ] );
        }
        
        $category_name = sanitize_textarea_field( $_POST['category_name'] );
        
        if( term_exists( $category_name, "сategories_filters" ) ){
            wp_send_json_error( [ "message" => "A category with that name already exists!" ] );
        }
        
        $data = wp_create_term( $category_name, "сategories_filters" );
        
        if( is_wp_error( $data ) ){
            wp_send_json_error( [ "message" => "An error occured: " . $data->get_error_message() ] );
        }
        
        wp_send_json_success( [ "category_id" => $data['term_id'] ] );
	}

	/**
	 * Delete elements
	 */
	function po_delete_elements() {
        
		$name_post_type = sanitize_textarea_field( $_POST['name_post_type'] );
		$type_elements  = sanitize_textarea_field( $_POST['type_elements'] );
		$id_elements    = sanitize_textarea_field( $_POST['id_elements'] );
        $id_elements    = array_map( 'intval', explode( ',', $id_elements ) );

		if ( $name_post_type === 'cat' ) {
			
			foreach ( $id_elements as $id_element ) {
				wp_delete_term( $id_element, 'сategories_filters' );
			}

			wp_send_json_success( [ "message" => "Categories are deleted." ] );
            
		} elseif ( $type_elements === 'all' ) {
         
			foreach ( $id_elements as $post_id ) {
				wp_trash_post( $post_id );
			}
            
			wp_send_json_success( [ "message" => "Items are moved to trash." ] );
            
		} else {
            
			$posts = get_posts( array(
				'post_type'   => $name_post_type,
				'include'     => $id_elements,
				'post_status' => 'trash',
			) );

			foreach ( $posts as $post ) {
				wp_delete_post( $post->ID, true );
			}
            
			wp_send_json_success( [ "message" => "Items are permanently deleted." ] );
            
		}
	}

	/**
	 * Restore works
	 */
	function po_publish_elements() {
        
		$name_post_type = sanitize_textarea_field( $_POST['name_post_type'] );
		$id_elements    = sanitize_textarea_field( $_POST['id_elements'] );
        $id_elements    = array_map( 'intval', explode( ',', $id_elements ) );

		$posts = get_posts( array(
			'post_type'   => $name_post_type,
			'include'     => $id_elements,
			'post_status' => 'trash',
		) );

		foreach ( $posts as $post ) {
			wp_publish_post( $post->ID );
		}
        
		wp_send_json_success( [ "message" => "Items are restored." ] );
        
	}
    
	/**
	 * Used for the Overview page
	 */
    function po_mark_tab_complete(){
        
        $tab_id  = sanitize_textarea_field( $_POST["tab_id"] );
        $user_id = intval( $_POST["user_id"] );
        
        $user_tabs_completed = get_user_meta( $user_id, "completed_overview_tabs", true );
        
        if( empty( $user_tabs_completed ) ){
            $user_tabs_completed = [];
        }
        
        if( ! in_array( $tab_id, $user_tabs_completed ) ){
            
            $user_tabs_completed[] = $tab_id;
            sort( $user_tabs_completed );
            
            update_user_meta( $user_id, "completed_overview_tabs", $user_tabs_completed );
        }
        
		wp_send_json_success( [ "message" => "Completed tabs are now remembered." ] );
        
    }
    
	/**
	 * From the Settings page
	 */
    function po_save_option_alphabetize_menu(){
        
        $should  = $_POST["should_alphabetize"] === "true";
        
        update_option("po_should_alphabetize_menu", $should );
        
		wp_send_json_success( [ "message" => "Option saved successfully." ] );
        
    }
    
	/**
	 * From the Filters List page
	 */
    function po_turn_off_filter(){
        
        $turned_off  = $_POST["turned_off"] === "true";
        $post_id  = $_POST["post_id"];
        
        update_post_meta( $post_id, "turned_off", $turned_off );
        
		wp_send_json_success( [ "message" => "Filter turned " . ( $turned_off ? "off" : "on" ) . " successfully." ] );
        
    }
    
	/**
	 * Save the admin menu sidebar HTML in the database
	 */
    function po_save_original_menu(){
        
        $menu_html  = stripcslashes( $_POST["menu_html"] );
        
        // sospo_mu_plugin()->write_log( $menu_html, "po_save_original_menu-menu_html" );
        
        update_option( "sos_po_original_menu", $menu_html );
        
		wp_send_json_success( [ "message" => "Menu saved successfully." ] );
        
    }
    
}

