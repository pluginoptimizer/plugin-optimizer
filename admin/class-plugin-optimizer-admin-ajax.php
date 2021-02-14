<?php

/**
 * The admin ajax functionality of the plugin.
 *
 * @package    Plugin_Optimizer
 * @subpackage Plugin_Optimizer/admin
 * @author     Web Dev <some@some.com>
 */
class Plugin_Optimizer_Ajax {

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

		add_action( 'wp_ajax_sos_add_plugin_to_filter',    [ $this, 'ajax_add_plugin_to_filter'     ] ); // sos_add_plugin_to_filter',   
		add_action( 'wp_ajax_sos_search_pages',            [ $this, 'ajax_search_pages'             ] ); // sos_search_pages',           
		add_action( 'wp_ajax_sos_search_filters',          [ $this, 'ajax_search_filters'           ] ); // sos_search_filters',         
		add_action( 'wp_ajax_sos_search_elements',         [ $this, 'ajax_search_elements'          ] ); // sos_search_elements',        
		add_action( 'wp_ajax_sos_all_elements',            [ $this, 'ajax_all_elements'             ] ); // sos_all_elements',           
		add_action( 'wp_ajax_sos_trash_elements',          [ $this, 'ajax_trash_elements'           ] ); // sos_trash_elements',         
		add_action( 'wp_ajax_sos_delete_elements',         [ $this, 'ajax_delete_elements'          ] ); // sos_delete_elements',        
		add_action( 'wp_ajax_sos_publish_elements',        [ $this, 'ajax_publish_elements'         ] ); // sos_publish_elements',       
		add_action( 'wp_ajax_sos_count_elements',          [ $this, 'ajax_count_elements'           ] ); // sos_count_elements',         
		add_action( 'wp_ajax_sos_add_group_plugins',       [ $this, 'ajax_add_group_plugins'        ] ); // sos_add_group_plugins',      
		add_action( 'wp_ajax_sos_create_category',         [ $this, 'ajax_create_category'          ] ); // sos_create_category',        
		add_action( 'wp_ajax_sos_create_cat_subcat',       [ $this, 'ajax_create_cat_subcat'        ] ); // sos_create_cat_subcat',      
		add_action( 'wp_ajax_sos_delete_category',         [ $this, 'ajax_delete_category'          ] ); // sos_delete_category',        
		add_action( 'wp_ajax_sos_check_name_elements',     [ $this, 'ajax_check_name_elements'      ] ); // sos_check_name_elements',    
		add_action( 'wp_ajax_sos_change_plugins_to_filter',[ $this, 'ajax_change_plugins_to_filter' ] ); // sos_change_plugins_to_filter'
		add_action( 'wp_ajax_sos_add_category_to_filter',  [ $this, 'ajax_add_category_to_filter'   ] ); // sos_add_category_to_filter', 
		add_action( 'wp_ajax_sos_transition_viewed',       [ $this, 'ajax_transition_viewed'        ] ); // sos_transition_viewed',      
		add_action( 'wp_ajax_sos_get_parent_cat',          [ $this, 'ajax_get_parent_cat'           ] ); // sos_get_parent_cat',         
		add_action( 'wp_ajax_sos_get_parent_group',        [ $this, 'ajax_get_parent_group'         ] ); // sos_get_parent_group',       
		add_action( 'wp_ajax_sos_change_plugins_to_group', [ $this, 'ajax_change_plugins_to_group'  ] ); // sos_change_plugins_to_group',
		add_action( 'wp_ajax_sos_show_plugins',            [ $this, 'ajax_show_plugins'             ] ); // sos_show_plugins',           
		add_action( 'wp_ajax_sos_change_permalink',        [ $this, 'ajax_change_permalink'         ] ); // sos_change_permalink',       
		add_action( 'wp_ajax_sos_change_type',             [ $this, 'ajax_change_type'              ] ); // sos_change_type',            
		add_action( 'wp_ajax_sos_change_data_category',    [ $this, 'ajax_change_data_category'     ] ); // sos_change_data_category',   
		add_action( 'wp_ajax_sos_change_groups_to_filter', [ $this, 'ajax_change_groups_to_filter'  ] ); // sos_change_groups_to_filter',

	}

	/**
	 * Create filter
	 */
	function ajax_add_plugin_to_filter() {
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

		Plugin_Optimizer_Helper::content_filters( $posts );

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Search for pages when creating a filter
	 */
	function ajax_search_pages() {
		ob_start();

		$posts = get_posts( array(
			'numberposts' => - 1,
			's'           => esc_attr( $_POST['keyword'] ),
			'post_type'   => 'page',
		) );

		if ( $posts ){
			foreach ( $posts as $post ){ ?>
                <h2>
                    <a href="<?= get_permalink( $post->ID ); ?>" class="link_search_page">
						<?= $post->post_title; ?>
                    </a>
                </h2>
			<?php
			}
		} else {
			?>
            <h2>
                Not Found
            </h2>
		<?php
		}

		wp_send_json_success( ob_get_clean() );
	}

	/**
	 * Create group
	 */
	function ajax_add_group_plugins() {

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

		Plugin_Optimizer_Helper::content_groups( $posts );

		wp_send_json_success( ob_get_clean() );

	}


	/**
	 * Search elements
	 */
	function ajax_search_elements() {
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
				Plugin_Optimizer_Helper::content_works( $posts );
			} elseif ( $name_post_type === 'sos_filter' ) {
				Plugin_Optimizer_Helper::content_filters( $posts );
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
				Plugin_Optimizer_Helper::content_groups( $posts );
			} elseif ( $name_post_type === 'cat' ) {
				$categories = get_categories( [
					'taxonomy'   => 'сategories_filters',
					'type'       => 'sos_filter',
					'parent'     => 0,
					'hide_empty' => 0,
					'name__like' => esc_attr( $_POST['keyword'] ),
				] );

				Plugin_Optimizer_Helper::content_filters_categories( $categories );
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
				Plugin_Optimizer_Helper::content_works( $posts );
			} elseif ( $name_post_type === 'sos_filter' ) {
				Plugin_Optimizer_Helper::content_filters( $posts );
			} elseif ( $name_post_type === 'sos_group' ) {
				Plugin_Optimizer_Helper::content_groups( $posts );
			}
		}

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Show all elements
	 */
	function ajax_all_elements() {
		$name_post_type = htmlspecialchars( $_POST['name_post_type'] );
		ob_start();

		$posts = get_posts( array(
			'post_type'   => $name_post_type,
			'numberposts' => - 1,
		) );

		if ( $name_post_type === 'sos_work' ) {
			Plugin_Optimizer_Helper::content_works( $posts );
		} elseif ( $name_post_type === 'sos_filter' ) {
			Plugin_Optimizer_Helper::content_filters( $posts );
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
			Plugin_Optimizer_Helper::content_groups( $posts );
		}

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Show trash elements
	 */
	function ajax_trash_elements() {
		$name_post_type = htmlspecialchars( $_POST['name_post_type'] );
		ob_start();

		$posts = get_posts( array(
			'post_type'   => $name_post_type,
			'numberposts' => - 1,
			'post_status' => 'trash',
		) );

		if ( $name_post_type === 'sos_work' ) {
			Plugin_Optimizer_Helper::content_works( $posts );
		} elseif ( $name_post_type === 'sos_filter' ) {
			Plugin_Optimizer_Helper::content_filters( $posts );
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
			Plugin_Optimizer_Helper::content_groups( $posts );
		}

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Delete elements
	 */
	function ajax_delete_elements() {
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

				Plugin_Optimizer_Helper::content_filters_categories( $categories );

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
	function ajax_publish_elements() {
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
	function ajax_count_elements() {
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
	function ajax_create_category( $post ) {
        
        return Plugin_Optimizer_Helper::create_category( $post );
	}

	/**
	 * Delete category
	 */
	function ajax_delete_category() {
		$cat_ID    = htmlspecialchars( $_POST['id_category'] );
		$id_filter = htmlspecialchars( $_POST['id_filter'] );

		wp_delete_term( $cat_ID, 'сategories_filters' );

		Plugin_Optimizer_Helper::create_category( $id_filter );
	}

	/**
	 * Add category to filter
	 */
	function ajax_add_category_to_filter() {
		$cat_ID    = htmlspecialchars( $_POST['id_category'] );
		$filter_ID = htmlspecialchars( $_POST['id_filter'] );
		$trigger   = htmlspecialchars( $_POST['trigger'] );
		$page      = htmlspecialchars( $_POST['page'] );


		if ( $trigger === 'delete' ) {
			wp_remove_object_terms( intval( $filter_ID ), intval( $cat_ID ), 'сategories_filters' );
		} else {
			wp_set_post_terms( $filter_ID, $cat_ID, 'сategories_filters', true );
		}


		if ( $page === 'filters' ){
			Plugin_Optimizer_Helper::create_category( $filter_ID );
		} else {
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
		}

	}

	/**
	 * Check name group
	 */
	function ajax_check_name_elements() {
        
        // po_mu_plugin()->write_log( $_POST, "ajax_check_name_elements-post" );
		$name_element = htmlspecialchars( $_POST['name_element'] );
		$type_element = htmlspecialchars( $_POST['type_element'] );

		$posts      = [];
		$categories = [];

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
	function ajax_create_cat_subcat() {
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

		Plugin_Optimizer_Helper::content_filters_categories( $categories );

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * After create category add select parents category
	 */
	function ajax_get_parent_cat() {

		ob_start();

		$categories = get_categories( [
			'taxonomy'   => 'сategories_filters',
			'type'       => 'sos_filter',
			'parent'     => 0,
			'hide_empty' => 0,
		] );

		?><div class="content block none_parent">
            <span value="None">None</span>
        </div><?php

		if ( $categories ):
			foreach ( $categories as $cat ):
				?><div class="content select_parent_to_category">
                    <span value="<?= $cat->cat_ID; ?>"><?= $cat->cat_name; ?></span>
                </div><?php
			endforeach;
		endif;

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Add plugin to filter
	 */
	function ajax_change_plugins_to_filter() {
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
				Plugin_Optimizer_Helper::content_plugin_to_filter( $post );
			}
		}

		$return = array(
			'filter_id' => $filter_id,
			'return'    => ob_get_clean(),
		);

		wp_send_json_success( $return );

	}

	/**
	 * Create new category for page category
	 */
	function ajax_transition_viewed() {
		$self_id = htmlspecialchars( $_POST['selfId'] );

		ob_start();

		switch ( $self_id ) {
			case 'window_filters':
				include 'pages/page-filters.php';
				break;
			case 'window_categories':
				include 'pages/page-categories.php';
				break;
			case 'window_groups':
				include 'pages/page-groups.php';
				break;
			case 'window_worklist':
				include 'pages/page-worklist.php';
				break;
		}

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * After create group add select parents group
	 */
	function ajax_get_parent_group() {

		ob_start();

		?><div class="header">
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
        </div><div class="plugin-wrapper">
            <div class="content none_group block">
                <span>None</span>
            </div>
			<?php
			if ( $groups ){
				foreach ( $groups as $group ){
					?>
                    <div class="content">
                        <span><?= $group->post_title; ?></span>
                    </div>
				<?php
				}
			}
			?>
        </div><?php

		wp_send_json_success( ob_get_clean() );

	}

	/**
	 * Add plugin to group
	 */
	function ajax_change_plugins_to_group() {
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

		Plugin_Optimizer_Helper::content_groups( $posts );

		$return = array(
			'group_id' => $group_id,
			'return'   => ob_get_clean(),
		);

		wp_send_json_success( $return );

	}

	/**
	 * Ajax show plugins on settings
	 */
	function ajax_show_plugins() {
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
			Plugin_Optimizer_Helper::content_activate_plugins_to_settings( $activate_plugins );
		} else {
			$this->content_deactive_plugins_to_settings( $deactivate_plugins );
		}

		wp_send_json_success( ob_get_clean() );
	}

	/**
	 * Ajax change permalink
	 */
	function ajax_change_permalink() {
		$text_link = htmlspecialchars( $_POST['text_link'] );
		$filter_id = htmlspecialchars( $_POST['filter_id'] );

		update_post_meta( $filter_id, 'selected_page', $text_link );

		wp_send_json_success( $text_link );
	}

	/**
	 * Ajax change type
	 */
	function ajax_change_type() {
		$text_type = htmlspecialchars( $_POST['text_type'] );
		$filter_id = htmlspecialchars( $_POST['filter_id'] );

		update_post_meta( $filter_id, 'type_filter', $text_type );

		wp_send_json_success( $text_type );
	}

	/**
	 * Ajax change description category
	 */
	function ajax_change_data_category() {
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
	function ajax_change_groups_to_filter() {
        
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
				Plugin_Optimizer_Helper::content_plugin_to_filter( $post );
			}
		}

		$content_plugins_to_filter = ob_get_clean();

		ob_start();
		?><div class="col-12">
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
                                    <span value="<?= isset( $content_plugins[ $block_plugin_in_group ] ) ? $content_plugins[ $block_plugin_in_group ] : "" ?>"><?= $block_plugin_in_group; ?></span>
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
        </div><?php

		$content_groups_to_filter = ob_get_clean();

		$return = array(
			'filter_id'       => $filter_id,
			'content_plugins' => $content_plugins_to_filter,
			'content_groups'  => $content_groups_to_filter,
		);

		wp_send_json_success( $return );

	}

}

