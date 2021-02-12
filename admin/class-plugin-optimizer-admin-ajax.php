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
	 * The loader that's responsible for maintaining and registering all hooks that power the plugin.
	 *
	 * @access   protected
	 * @var      Plugin_Optimizer_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	function __construct() {

		$this->loader = new Plugin_Optimizer_Loader();

        $this->load_hooks();
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 *
	 * @access   private
	 */
	private function load_hooks() {

		$this->loader->add_action( 'wp_ajax_sos_add_plugin_to_filter',    $this, 'ajax_add_plugin_to_filter'     );
		$this->loader->add_action( 'wp_ajax_sos_search_pages',            $this, 'ajax_search_pages'             );
		$this->loader->add_action( 'wp_ajax_sos_search_filters',          $this, 'ajax_search_filters'           );
		$this->loader->add_action( 'wp_ajax_sos_search_elements',         $this, 'ajax_search_elements'          );
		$this->loader->add_action( 'wp_ajax_sos_all_elements',            $this, 'ajax_all_elements'             );
		$this->loader->add_action( 'wp_ajax_sos_trash_elements',          $this, 'ajax_trash_elements'           );
		$this->loader->add_action( 'wp_ajax_sos_delete_elements',         $this, 'ajax_delete_elements'          );
		$this->loader->add_action( 'wp_ajax_sos_publish_elements',        $this, 'ajax_publish_elements'         );
		$this->loader->add_action( 'wp_ajax_sos_count_elements',          $this, 'ajax_count_elements'           );
		$this->loader->add_action( 'wp_ajax_sos_add_group_plugins',       $this, 'ajax_add_group_plugins'        );
		$this->loader->add_action( 'wp_ajax_sos_create_category',         $this, 'ajax_create_category'          );
		$this->loader->add_action( 'wp_ajax_sos_create_cat_subcat',       $this, 'ajax_create_cat_subcat'        );
		$this->loader->add_action( 'wp_ajax_sos_delete_category',         $this, 'ajax_delete_category'          );
		$this->loader->add_action( 'wp_ajax_sos_check_name_elements',     $this, 'ajax_check_name_elements'      );
		$this->loader->add_action( 'wp_ajax_sos_change_plugins_to_filter',$this, 'ajax_change_plugins_to_filter' );
		$this->loader->add_action( 'wp_ajax_sos_add_category_to_filter',  $this, 'ajax_add_category_to_filter'   );
		$this->loader->add_action( 'wp_ajax_sos_transition_viewed',       $this, 'ajax_transition_viewed'        );
		$this->loader->add_action( 'wp_ajax_sos_get_parent_cat',          $this, 'ajax_get_parent_cat'           );
		$this->loader->add_action( 'wp_ajax_sos_get_parent_group',        $this, 'ajax_get_parent_group'         );
		$this->loader->add_action( 'wp_ajax_sos_change_plugins_to_group', $this, 'ajax_change_plugins_to_group'  );
		$this->loader->add_action( 'wp_ajax_sos_show_plugins',            $this, 'ajax_show_plugins'             );
		$this->loader->add_action( 'wp_ajax_sos_change_permalink',        $this, 'ajax_change_permalink'         );
		$this->loader->add_action( 'wp_ajax_sos_change_type',             $this, 'ajax_change_type'              );
		$this->loader->add_action( 'wp_ajax_sos_change_data_category',    $this, 'ajax_change_data_category'     );
		$this->loader->add_action( 'wp_ajax_sos_change_groups_to_filter', $this, 'ajax_change_groups_to_filter'  );

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

		$this->content_filters( $posts );

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

		$this->content_groups( $posts );

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
				$this->content_works( $posts );
			} elseif ( $name_post_type === 'sos_filter' ) {
				$this->content_filters( $posts );
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
				$this->content_groups( $posts );
			} elseif ( $name_post_type === 'cat' ) {
				$categories = get_categories( [
					'taxonomy'   => 'сategories_filters',
					'type'       => 'sos_filter',
					'parent'     => 0,
					'hide_empty' => 0,
					'name__like' => esc_attr( $_POST['keyword'] ),
				] );

				$this->content_filters_categories( $categories );
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
				$this->content_works( $posts );
			} elseif ( $name_post_type === 'sos_filter' ) {
				$this->content_filters( $posts );
			} elseif ( $name_post_type === 'sos_group' ) {
				$this->content_groups( $posts );
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
			$this->content_works( $posts );
		} elseif ( $name_post_type === 'sos_filter' ) {
			$this->content_filters( $posts );
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
			$this->content_groups( $posts );
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
			$this->content_works( $posts );
		} elseif ( $name_post_type === 'sos_filter' ) {
			$this->content_filters( $posts );
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
			$this->content_groups( $posts );
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

				$this->content_filters_categories( $categories );

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
        
		if ( $post && ! is_numeric( $post ) ) {
			$id_filter = $post->ID;
		} elseif ( is_numeric( $post ) ) {
			$id_filter = $post;
		} else {
			$id_filter     = htmlspecialchars( $_POST['id_filter'] );
			$name_category = htmlspecialchars( $_POST['name_category'] );

			wp_set_object_terms( $id_filter, $name_category, 'сategories_filters' );
		}


		ob_start();

		$categories = get_categories( [
			'taxonomy'   => 'сategories_filters',
			'type'       => 'sos_filter',
			'hide_empty' => 0,
		] );

		if ( $categories ){
			foreach ( $categories as $cat ){
				?>
                <div class="content filter-category <?= ( has_term( $cat->cat_name, 'сategories_filters', $id_filter ) ) ? 'block' : ''; ?>">
                    <span><?= $cat->cat_name; ?></span>
                    <span class="close" id="<?= $cat->cat_ID; ?>">×</span>
                </div>
			<?php
			}
		}
        
		?><input type="text" placeholder="Name category"><?php
        ?><button class="add-filter add-permalink add-category" id="post-<?= $id_filter; ?>">
            <span class="pluse">+</span> Category
        </button><?php
        
		if ( $post && ! is_numeric( $post ) ) {
			echo ob_get_clean();
		} else {
			wp_send_json_success( ob_get_clean() );
		}

	}

	/**
	 * Delete category
	 */
	function ajax_delete_category() {
		$cat_ID    = htmlspecialchars( $_POST['id_category'] );
		$id_filter = htmlspecialchars( $_POST['id_filter'] );

		wp_delete_term( $cat_ID, 'сategories_filters' );

		$this->ajax_create_category( $id_filter );
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
			$this->ajax_create_category( $filter_ID );
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

		$this->content_filters_categories( $categories );

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
				$this->content_plugin_to_filter( $post );
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

		$this->content_groups( $posts );

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
			$this->content_activate_plugins_to_settings( $activate_plugins );
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
				$this->content_plugin_to_filter( $post );
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
                                    <span value="<?= $content_plugins[ $block_plugin_in_group ]; ?>"><?= $block_plugin_in_group; ?></span>
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


	function content_plugin_to_filter( $post ) {
		$all_plugins        = Plugin_Optimizer_Helper::get_plugins_with_status();
		$activate_plugins   = array();
		$deactivate_plugins = array();
		foreach ( $all_plugins as $plugin ) {
			foreach ( $plugin as $key => $value ) {
				if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
					if ( $value ) {
						$activate_plugins[ $plugin['name'] ] = $plugin['file'];
					} else {
						$deactivate_plugins[ $plugin['name'] ] = $plugin['file'];
					}
				}
			}
		}
		$block_plugins = get_post_meta( $post->ID, 'block_plugins', true );
		?><div class="col-12">
            <div class="header">
                <div class="title">
					<?php $count_plugins = 0;
					if ( $activate_plugins ) {
						foreach ( $activate_plugins as $activate_plugin ) {
							if ( in_array( $activate_plugin, $block_plugins ) ) {
								$count_plugins ++;
							}
						}
					}
					?>
                    Plugins <span
                            class="disabled">- Disabled: <?= $count_plugins; ?>/<?= count( $activate_plugins ); ?></span>
                </div>
                <span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
            </div>
			<?php
			if ( $activate_plugins ):
				?>
                <div class="header attribute-plugin">Active plugins</div>
                <div class="plugin-wrapper">
					<?php
					foreach ( $activate_plugins as $activate_plugin => $activate_plugin_link ):
						?>
                        <div class="content<?= ( in_array( $activate_plugin, $block_plugins ) ) ? ' block' : '' ?>">
							<?php
							if ( in_array( $activate_plugin, $block_plugins ) ):
								?>
                                <span class="close" id="<?= $activate_plugin; ?>" value="<?= $post->ID; ?>"
                                      link="<?= $activate_plugin_link; ?>">×</span>
							<?php
							else:
								?>
                                <span class="close pluse_plugin" id="<?= $activate_plugin; ?>"
                                      value="<?= $post->ID; ?>" link="<?= $activate_plugin_link; ?>">+</span>
							<?php
							endif;
							?>
							<?php
							$groups_plugins = get_post_meta( $post->ID, 'block_group_plugins', true );
                            
                            if( empty( $groups_plugins ) ){
                                $groups_plugins = [];
                            } elseif ( ! is_array( $groups_plugins ) ){
                                $groups_plugins = [ $groups_plugins ];
                            }

							$groups = get_posts( array(
								'post_type'   => 'sos_group',
								'numberposts' => - 1,
							) );
							if ( $groups ) :
								?>
                                <div class="groups-names">
									<?php
									foreach ( $groups as $group ):
										if ( in_array( $group->post_title, $groups_plugins ) && in_array( $activate_plugin, explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) ) ) ):

											?>
                                            <span><?= $group->post_title; ?></span>
										<?php
										endif;
									endforeach;
									?>
                                </div>
							<?php
							endif;
							?>
                            <span><?= $activate_plugin; ?></span>
                        </div>
					<?php
					endforeach;
					?>
                </div>
                <div class="header attribute-plugin">Inactive plugins</div>
                <div class="plugin-wrapper">
					<?php
					foreach ( $deactivate_plugins as $deactivate_plugin => $deactivate_plugin_link ):
						?>
                        <div class="content deactivate-plugin<?= in_array( $deactivate_plugin, $block_plugins ) ? ' block' : ''; ?>">
							<?php
							$groups_plugins = get_post_meta( $post->ID, 'block_group_plugins', true );

							$groups = get_posts( array(
								'post_type'   => 'sos_group',
								'numberposts' => - 1,
							) );
							if ( $groups ) :
								?>
                                <div class="groups-names">
									<?php
									foreach ( $groups as $group ):
										if ( in_array( $group->post_title, $groups_plugins ) && in_array( $deactivate_plugin, explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) ) ) ):

											?>
                                            <span class="group-name"><?= $group->post_title; ?></span>
										<?php
										endif;
									endforeach;
									?>
                                </div>
							<?php
							endif;
							?>
                            <span><?= $deactivate_plugin; ?></span>
							<?php
							if ( in_array( $deactivate_plugin, $block_plugins ) ):
								?>
                                <span class="close" id="<?= $deactivate_plugin; ?>" value="<?= $post->ID; ?>"
                                      link="<?= $deactivate_plugin_link; ?>">×</span>
							<?php
							else:
								?>
                                <span class="close pluse_plugin" id="<?= $deactivate_plugin; ?>"
                                      value="<?= $post->ID; ?>" link="<?= $deactivate_plugin_link; ?>">+</span>
							<?php
							endif;
							?>
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
        </div><?php
	}

	/**
	 * Content plugins to settings
	 */
	function content_activate_plugins_to_settings( $activate_plugins ) {

		if ( $activate_plugins ){
            foreach ( $activate_plugins as $activate_plugin ){
                ?>
                <tr class="block_info">
                    <td><input type="checkbox"></td>
                    <td><?= $activate_plugin; ?></td>
                </tr>
				<?php
                    $posts = get_posts( array(
                        'post_type'   => 'sos_filter',
                        'numberposts' => - 1,
                    ) );
				?>
                <tr class="hidden_info">
                    <td colspan="2">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            Filters
                                        </div>
                                        <span class="count-plugin">( All: <?= wp_count_posts( 'sos_filter' )->publish; ?>   |   Trash: <?= wp_count_posts( 'sos_filter' )->trash; ?> )</span>
                                    </div>
									<?php
									if ( $posts ):
										?>
                                        <div class="plugin-wrapper">
											<?php
											foreach ( $posts as $post ):
												$group_plugins = get_post_meta( $post->ID, 'block_plugins', true );
												?>
                                                <a href="<?= esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_filters&filter_title=' . urlencode( $post->post_title ) ) ); ?>">
                                                    <div class="content
                                             <?php
													if ( in_array( $activate_plugin, $group_plugins ) ) {
														echo 'block';
													}
													?>
                                             ">
                                                        <span><?= $post->post_title; ?></span>
                                                    </div>
                                                </a>
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
			<?php } ?>
		<?php } else { ?>
            <tr class="plugin-wrapper no-plugins">
                <td colspan="2">
                    <div class="content">
                        <span>No activate plugins for blocking</span>
                    </div>
                </td>
            </tr>
		<?php
		}
	}

	/**
	 * Content deactive plugins to settings
	 */
	function content_deactive_plugins_to_settings( $deactivate_plugins ) {

		if ( $deactivate_plugins ){
            
			foreach ( $deactivate_plugins as $deactivate_plugin ){
				?>
                <tr class="block_info">
                    <td><input type="checkbox"></td>
                    <td><?= $deactivate_plugin; ?></td>
                </tr>
				<?php
				$posts = get_posts( array(
					'post_type'   => 'sos_filter',
					'numberposts' => - 1,
				) );
				?>
                <tr class="hidden_info">
                    <td colspan="2">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            Filters
                                        </div>
                                        <span class="count-plugin">( All: <?= wp_count_posts( 'sos_filter' )->publish; ?>   |   Trash: <?= wp_count_posts( 'sos_filter' )->trash; ?> )</span>
                                    </div>
									<?php
									if ( $posts ):
										?>
                                        <div class="plugin-wrapper">
											<?php
											foreach ( $posts as $post ):
												$group_plugins = get_post_meta( $post->ID, 'block_plugins', true );
												?>
                                                <a href="<?= esc_url( get_admin_url( null, 'admin.php?page=plugin_optimizer_filters&filter_title=' . urlencode( $post->post_title ) ) ); ?>">
                                                    <div class="content
                                             <?php
													if ( in_array( $deactivate_plugin, $group_plugins ) ) {
														echo 'block';
													}
													?>
                                             ">
                                                        <span><?= $post->post_title; ?></span>
                                                    </div>
                                                </a>
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
			<?php } ?>
		<?php } else { ?>
            <tr class="plugin-wrapper no-plugins">
                <td colspan="2">
                    <div class="content">
                        <span>No activate plugins for blocking</span>
                    </div>
                </td>
            </tr>
		<?php
		}
	}

	/**
	 * Content for works
	 */
	function content_works( $posts ) {
		if ( $posts ){
			foreach ( $posts as $post ){ ?>
                <tr>
                    <td><input type="checkbox" id="<?= $post->ID; ?>"></td>
                    <td><?= $post->post_title; ?></td>
                    <td><?= get_post_meta( $post->ID, 'post_link', true ); ?></td>
                    <td><?= substr( str_replace( '-', '/', str_replace( " ", " at ", $post->post_date ) ), 0, - 3 ) . ' pm'; ?></td>
                    <td>
                        <a class="row-title"
                           href="<?= get_admin_url( null, 'admin.php?page=plugin_optimizer_filters&work_title=' . urlencode( str_replace( ' ', '_', str_replace( 'Add filter to ', '', $post->post_title ) ) ) . '&work_link=' . urlencode( get_post_meta( $post->ID, 'post_link', true ) ) ); ?>">
                            <button class="add-filter"><span class="pluse">+</span> add new filter</button>
                        </a>
                    </td>
                </tr>
			<?php
			}
		} else {
			?>
            <tr>
                <td colspan="5">Great job your work is done</td>
            </tr>
		<?php
		}
	}

	/**
	 * Content for group plugins
	 */
	function content_groups( $posts ) {
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
		if ( $posts ){
			foreach ( $posts as $post ) :
				$group_plugins = get_post_meta( $post->ID, 'group_plugins', true );
				?>

                <tr class="block_info" id="group_<?= $post->ID; ?>">
                    <td><input type="checkbox" id="<?= $post->ID; ?>"></td>
                    <td><?= $post->post_title; ?></td>
                    <td><?= $group_plugins; ?></td>
                    <td><?= $group_plugins ? count( explode( ',', $group_plugins ) ) : 0; ?></td>
                </tr>
                <tr class="hidden_info">
                    <td colspan="6">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
											<?php
											$count_block_plugins = 0;

											foreach ( $all_plugins as $plugin ) {
												foreach ( $plugin as $key => $value ) {
													if ( $key === 'name' ) {
														if ( substr_count( $group_plugins, $plugin['name'] ) ) {
															$count_block_plugins ++;
														}
													}
												}
											}
											?>
                                            Plugins <span
                                                    class="disabled">- Disabled: <?= $count_block_plugins ?>/<?= count( $activate_plugins ); ?></span>
                                        </div>
                                        <span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
                                    </div>
									<?php
									if ( $activate_plugins ):
										?>
                                        <div class="plugin-wrapper wrapper-group-plugins">
											<?php
											foreach ( $activate_plugins as $activate_plugin ):
												?>
                                                <div class="content<?= substr_count( $group_plugins, $activate_plugin ) ? ' block' : ''; ?>"
                                                     group_id="<?= $post->ID; ?>">
                                                    <span><?= $activate_plugin; ?></span>
                                                </div>
											<?php
											endforeach;
											foreach ( $deactivate_plugins as $deactivate_plugin ):
												?>
                                                <div class="content deactivate-plugin<?= substr_count( $group_plugins, $deactivate_plugin ) ? ' block' : ''; ?>">
                                                    <span><?= $deactivate_plugin; ?></span>
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


				if ( $posts_chidren ) :
					foreach ( $posts_chidren as $post_chidren ) :
						$children_group_plugins = get_post_meta( $post_chidren->ID, 'group_plugins', true );
						?>

                        <tr class="block_info block_children">
                            <td><input type="checkbox" id="<?= $post_chidren->ID; ?>"></td>
                            <td> — <?= $post_chidren->post_title; ?></td>
                            <td><?= get_post_meta( $post_chidren->ID, 'type_group', true ); ?></td>
                            <td><?= $children_group_plugins; ?></td>
                            <td><?= substr_count( $children_group_plugins, ',' ) + 1; ?></td>
                        </tr>
                        <tr class="hidden_info">
                            <td colspan="6">
                                <div class="content-filter">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="header">
                                                <div class="title">
													<?php
													$count_block_plugins = 0;
													foreach ( $activate_plugins as $activate_plugin ) {
														if ( substr_count( $children_group_plugins, $activate_plugin ) ) {
															$count_block_plugins ++;
														}
													}
													?>
                                                    Plugins <span
                                                            class="disabled">- Disabled: <?= $count_block_plugins ?>/<?= count( $activate_plugins ); ?></span>
                                                </div>
                                                <span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
                                            </div>
											<?php
											if ( $activate_plugins ):
												?>
                                                <div class="plugin-wrapper wrapper-group-plugins">
													<?php
													foreach ( $activate_plugins as $activate_plugin ):
														?>
                                                        <div class="content <?= substr_count( $children_group_plugins, $activate_plugin ) ? 'block' : ''; ?>">
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
		} else {
			?>
            <tr>
                <td colspan="5">No Groups</td>
            </tr>
		<?php
		}
	}

	/**
	 * Content for filters categories
	 */
	function content_filters_categories( $categories ) {
		if ( $categories ){
			foreach ( $categories as $cat ){ ?>
                <tr class="block_info" id="cat-<?= $cat->cat_ID ?>">
                    <td><input type="checkbox" id="<?= $cat->cat_ID ?>"></td>
                    <td class="data-title-category"><?= $cat->cat_name; ?></td>
                </tr>
                <tr class="hidden_info">
                    <td colspan="2">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            Name
                                        </div>
                                    </div>
                                    <div class="content-description">
                                        <span class="data-interaction data-title-cat" cat_id="<?= $cat->cat_ID ?>"
                                              contenteditable>
                                            <?= $cat->cat_name; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row description">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
                                            Description
                                        </div>
                                    </div>
                                    <div class="content-description">
                                        <span class="data-interaction data-description-cat" cat_id="<?= $cat->cat_ID ?>"
                                              contenteditable>
                                            <?= $cat->category_description ? $cat->category_description : 'None description'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
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
													if ( has_term( $cat->cat_ID, 'сategories_filters', $post->ID ) ) {
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
                                                <div class="content <?= has_term( $cat->cat_ID, 'сategories_filters', $post->ID ) ? 'block' : ''; ?>"
                                                     id="<?= $post->ID; ?>" cat_id="cat_<?= $cat->cat_ID; ?>">
                                                    <span><?= $post->post_title; ?></span>
                                                </div>
											<?php
											endforeach;
										endif;
										?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
				<?php
				$difference    = $cat->cat_ID;
				$subcategories = get_categories( array(
					'parent'     => $difference,
					'taxonomy'   => 'сategories_filters',
					'type'       => 'sos_filter',
					'hide_empty' => 0,
				) );
				if ( $subcategories ) :
					foreach ( $subcategories as $subcategory ) :?>
                        <tr class="block_info block_children">
                            <td><input type="checkbox" id="<?= $subcategory->cat_ID ?>"></td>
                            <td> — <?= $subcategory->cat_name; ?></td>
                        </tr>
                        <tr class="hidden_info">
                            <td colspan="2">
                                <div class="content-filter">
                                    <div class="row">
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
															if ( has_term( $subcategory->cat_ID, 'сategories_filters', $post->ID ) ) {
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
                                                        <div class="content <?= has_term( $subcategory->cat_ID, 'сategories_filters', $post->ID ) ? 'block' : ''; ?>"
                                                             id="<?= $post->ID; ?>"
                                                             cat_id="cat_<?= $subcategory->cat_ID; ?>">
                                                            <span><?= $post->post_title; ?></span>
                                                        </div>
													<?php
													endforeach;
												endif;
												?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
					<?php endforeach;
				endif;
				?>
			<?php }
        } else {
		?>
            <tr>
                <td colspan="5">No categories</td>
            </tr>
		<?php
        }
	}

	/**
	 * Content for filters
	 */
	function content_filters( $posts ) {
		if ( $posts ){
			foreach ( $posts as $post ){
                $blocking_plugins = get_post_meta( $post->ID, 'block_plugins', true );
                sort( $blocking_plugins );
				?>
                <tr class="block_info" id="filter-<?= $post->ID; ?>">
                    <td><input type="checkbox" id="<?= $post->ID; ?>"></td>
                    <td><?= $post->post_title; ?></td>
                    <td><?= get_post_meta( $post->ID, 'category_filter', true ); ?></td>
                    <td class="data-type-filter"><?= get_post_meta( $post->ID, 'type_filter', true ); ?></td>
                    <td class="data-link-filter"><?= get_post_meta( $post->ID, 'selected_page', true ); ?></td>
                    <td class="expandable"><span class="no_hover"><?= count( $blocking_plugins ) ?></span><span class="yes_hover"><?= implode( ',<br/>', $blocking_plugins ); ?></span></td>
                </tr>
                <tr class="hidden_info">
                    <td colspan="6">
                        <div class="content-filter">
                            <div class="row">
                                <div class="col-12">
									<?php
									$type_filter = get_post_meta( $post->ID, 'type_filter', true );
									if ( $type_filter === 'none' ):
										?>
                                        <div class="header">Permalinks</div>
                                        <div class="content-permalinks">
                                            <div class="link">
                                            <span class="data-interaction data-link" filter_id="<?= $post->ID ?>"
                                                  contenteditable>
                                            <?= get_post_meta( $post->ID, 'selected_page', true ) ?>
                                            </span>
                                            </div>
                                        </div>
									<?php

									else:
										?>
                                        <div class="header">Type</div>
                                        <div>
                                            <div class="content">
                                            <span class="data-interaction data-type" filter_id="<?= $post->ID ?>"
                                                  contenteditable>
                                                <?= $type_filter ?>
                                            </span>
                                            </div>
                                        </div>

									<?php
									endif;
									?>
                                </div>

                            </div>
                            <div class="row content-plugins">
								<?php
								$this->content_plugin_to_filter( $post );
								?>
                            </div>
                            <div class="row group-wrapper">
                                <div class="col-12">
                                    <div class="header">
                                        <div class="title">
											<?php
											$groups_plugins = get_post_meta( $post->ID, 'block_group_plugins', true );
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
                                                <div id="<?= $group->ID; ?>" value="<?= $post->ID; ?>"
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
										$this->ajax_create_category( $post );
										?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
			<?php
			}
        } else {
			?>
            <tr>
                <td colspan="6">No filters</td>
            </tr>
		<?php
		}
	}

}

