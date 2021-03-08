<?php
/**
 * The admin ajax functionality of the plugin.
 *
 * @package    PluginOptimizer
 * @subpackage PluginOptimizer/admin
 * @author     Simple Online Systems <admin@simpleonlinesystems.com>
 */

class PO_Ajax {

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

	}

	/**
	 * Create/Update filter
	 */
	function po_save_filter() {
        
        parse_str( $_POST['data'], $array);
        
        $data = $array['PO_filter_data'];
        
        // po_mu_plugin()->write_log( $_POST, "po_save_filter-_POST" );
        // po_mu_plugin()->write_log( $data, "po_save_filter-data" );
        
        
        if( empty( $data["title"] ) ){
            
            wp_send_json_error( [ "message" => "The title is a required field!" ] );
        }
        
        if( ! empty( $data["type"] ) && $data["type"] == "_endpoint" && count( $data["endpoints"] ) === 1 && empty( $data["endpoints"][0] ) ){
            
            wp_send_json_error( [ "message" => "There has to be at least 1 endpoint defined for this filter type!" ] );
        }
        
		$post_data = array(
			'post_title'  => $data["title"],
			'post_type'   => 'sos_filter',
			'post_status' => 'publish',
			'post_author' => 1,// TODO get_current_user_id() with localize_script in enqueue function
			// 'tax_input'   => [ "сategories_filters" => ( ! empty( $data["categories"] ) ? array_keys( $data["categories"] ) : [] ) ],
		);
        
        if( ! empty( $data["ID"] ) ){
            $post_data["ID"] = $data["ID"];
        }

		$post_id = wp_insert_post( $post_data, true );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( [ "message" => $post_id->get_error_message() ] );
		}

        if( ! empty( $data["categories"] ) ){
            
            $category_ids = array_keys( $data["categories"] );
            
            foreach( $category_ids as $index => $cat_id ){
                
                $category_ids[ $index ] = (int) $cat_id;
            }
            
            $set_categories = wp_set_object_terms( $post_id, $category_ids, "сategories_filters" );
            
            // po_mu_plugin()->write_log( $set_categories, "po_save_filter-set_categories" );
        }
        
        $meta = [
            "filter_type"       => ! empty( $data["type"] )             ? $data["type"]             : "",
            "endpoints"         => ! empty( $data["endpoints"] )        ? $data["endpoints"]        : [],
            "plugins_to_block"  => ! empty( $data["plugins_to_block"] ) ? $data["plugins_to_block"] : [],
            "groups_used"       => ! empty( $data["groups"] )           ? $data["groups"]           : [],
            "categories"        => ! empty( $data["categories"] )       ? $data["categories"]       : [],
        ];
        
        foreach( $meta as $meta_key => $meta_value ){
            
            update_post_meta( $post_id, $meta_key, $meta_value );
        }
        
        
		wp_send_json_success( [ "message" => "All good, the filter is saved.", "id" => $post_id, ] );

	}

	/**
	 * Create/Update Group
	 */
	function po_save_group() {
        
        parse_str( $_POST['data'], $array);
        
        $data = $array['PO_filter_data'];
        
		// wp_send_json_success( $data );
        // exit;
        
        // po_mu_plugin()->write_log( $_POST, "po_save_group-_POST" );
        // po_mu_plugin()->write_log( $data, "po_save_group-data" );
        
        
        if( empty( $data["title"] ) ){
            
            wp_send_json_error( [ "message" => "The title is a required field!" ] );
        }
        
        if( empty( $data["plugins_to_block"] ) ){
            
            wp_send_json_error( [ "message" => "There has to be at least 1 plugin selected in order to save this group!" ] );
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

        $meta = [
            "group_plugins" => $data["plugins_to_block"],
        ];
        
        foreach( $meta as $meta_key => $meta_value ){
            
            update_post_meta( $post_id, $meta_key, $meta_value );
        }
        
        
		wp_send_json_success( [ "message" => "All good, the group is saved.", "id" => $post_id, ] );

	}

	/**
	 * Create/Update Category
	 */
	function po_save_category() {
        
        parse_str( $_POST['data'], $array);
        
        $data = $array['PO_filter_data'];
        
		// wp_send_json_success( $data );
        // exit;
        
        // po_mu_plugin()->write_log( $_POST, "po_save_category-_POST" );
        // po_mu_plugin()->write_log( $data, "po_save_category-data" );
        
        
        if( empty( $data["title"] ) ){
            
            wp_send_json_error( [ "message" => "The title is a required field!" ] );
        }
        
        if( empty( $data["ID"] ) ){
            
            $category = wp_create_term( $data["title"], "сategories_filters" );
            
            if( is_wp_error( $category ) ){
                wp_send_json_error( [ "message" => "An error occured: " . $category->get_error_message() ] );
            }
            
            $term_id = $category["term_id"];
            
        } else {
            
            $term_id = $data["ID"];
        }
        
        $args = [
            'name' => trim( $data["title"] ),
        ];
        
        if( ! empty( $data["description"] ) ){
            
            $args["description"] = trim( $data["description"] );
            
        }
        
        $category = wp_update_term( $term_id, "сategories_filters", $args );
        
        if( is_wp_error( $category ) ){
            wp_send_json_error( [ "message" => "An error occured: " . $category->get_error_message() ] );
        }
        
		wp_send_json_success( [ "message" => "All good, the category is saved.", "id" => $category["term_id"], ] );

	}

	/**
	 * Create new category
	 */
	function po_create_category(){
        
        // po_mu_plugin()->write_log( $_POST, "po_create_category-_POST" );
        
        $category_name = htmlspecialchars( $_POST['category_name'] );
        
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
        
		$name_post_type = htmlspecialchars( $_POST['name_post_type'] );
		$id_elements    = htmlspecialchars( $_POST['id_elements'] );
		$type_elements  = htmlspecialchars( $_POST['type_elements'] );

		if ( $name_post_type === 'cat' ) {
			$id_elements = explode( ',', $id_elements );

			foreach ( $id_elements as $id_element ) {
				wp_delete_term( $id_element, 'сategories_filters' );
			}

			wp_send_json_success( [ "message" => "Categories are deleted." ] );
            
		} elseif ( $type_elements === 'all' ) {
         
			$posts = get_posts( array(
				'post_type' => $name_post_type,
				'include'   => $id_elements,
			) );

			foreach ( $posts as $post ) {
				wp_trash_post( $post->ID );
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
		$name_post_type = htmlspecialchars( $_POST['name_post_type'] );
		$id_elements    = $_POST['id_elements'];

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
        
        $tab_id  = $_POST["tab_id"];
        $user_id = $_POST["user_id"];
        
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
        
        // po_mu_plugin()->write_log( $_POST["should_alphabetize"], "po_save_option_alphabetize_menu-post-should_alphabetize" );
        // po_mu_plugin()->write_log( $should, "po_save_option_alphabetize_menu-should" );
        
        update_option("po_should_alphabetize_menu", $should );
        
		wp_send_json_success( [ "message" => "Option saved successfully." ] );
        
    }
    
}

