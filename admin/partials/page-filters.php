<div class="wrap wrapper-filter">
    <div id="create_elements" class="create_filter">
        <div class="wrapper_create-elements">
            <p class="popup-close">×</p>
            <form action="" class="created-filters">
                <p>Filter Title</p>
                <input type="text" placeholder="Enter filter title" name="title_filter">
                <p>Set Type</p>
                <input type="text" placeholder="Enter type" name="type_filter">
                <p>Select category</p>
                <!--                <input type="text" placeholder="Enter category" name="category_filter">-->
                <select name="category_filter">
					<?php
					$categories = get_categories( [
						'taxonomy'   => 'сategories_filters',
						'type'       => 'sos_filter',
						'hide_empty' => 0,
					] );

					if ( $categories ):
						foreach ( $categories as $cat ):
							?>
                            <option value="<?= str_replace( ' ', "_", $cat->cat_ID ); ?>"><?= $cat->cat_name; ?></option>
						<?php
						endforeach;
					endif;
					?>
                </select>
                <p>Add Permalinks</p>
                <input type="text" id="search_pages" placeholder="Enter name page" name="pages">

                <div id="result">
                    <p class="popup-close">×</p>
                    <div id="result_search"></div>
                </div>

                <p>Add post type</p>
                <select name="post_type" multiple>
					<?php
					$post_types         = get_post_types( [ 'publicly_queryable' => 1 ] );
					$post_types['page'] = 'page';
					unset( $post_types['attachment'], $post_types['sos_filter'], $post_types['sos_group'] );

					foreach ( $post_types as $post_type ) {
						?>
                        <option value="<?= str_replace( ' ', "_", $post_type ); ?>"><?= $post_type; ?></option>
						<?php
					}

					?>
                </select>
                <p>Select block plugins</p>
                <select name="block_plugins" multiple>
					<?php
					$all_plugins        = Simple_Online_Systems_Helper::get_plugins_with_status();
					$activate_plugins   = array();
					$deactivate_plugins = array();
					foreach ( $all_plugins as $plugin ) {
						foreach ( $plugin as $key => $value ) {
							if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
								if ( $value ) {
									$activate_plugins[ $plugin['name'] ] = $plugin['file'];
								} else {
									array_push( $deactivate_plugins, $plugin['name'] );
								}
							}
						}
					}
					if ( $activate_plugins ):
						foreach ( $activate_plugins as $activate_plugin => $activate_plugin_link ):
							?>
                            <option value="<?= $activate_plugin_link; ?>"><?= $activate_plugin ?></option>
						<?php
						endforeach;
					endif;
					?>
                </select>
                <div class="select-filter">
		            <?php
		            if ( $activate_plugins ):
			            foreach ( $activate_plugins as $activate_plugin => $activate_plugin_link ):
				            ?>
                            <span class="content select_plugins_to_filter" value="<?= $activate_plugin_link; ?>"><?= $activate_plugin ?></span>
			            <?php
			            endforeach;
		            endif;
		            ?>
                </div>
                <p>Select block group plugins</p>
                <select name="block_group_plugins" multiple>
                    <option value="none" selected>None</option>
					<?php
					$posts = get_posts( array(
						'post_type'   => 'sos_group',
						'numberposts' => - 1,
					) );
					foreach ( $posts as $post ) {
						?>
                        <option value="<?= str_replace( ' ', "_", $post->post_title ); ?>"><?= $post->post_title; ?></option>
						<?php
					}
					?>
                </select>
                <div class="select-filter">
                    <span class="content select_groups_to_filter none_group block" value="none">None</span>
                    <?php
	                $posts = get_posts( array(
		                'post_type'   => 'sos_group',
		                'numberposts' => - 1,
	                ) );
	                foreach ( $posts as $post ) {
		                ?>
                        <span class="content select_groups_to_filter" value="<?= str_replace( ' ', "_", $post->post_title ); ?>"><?= $post->post_title; ?></span>
		                <?php
	                }
	                ?>
                </div>

                <input type="submit" value="Create new filter">
            </form>
        </div>
    </div>


	<?php
	$posts = get_posts( array(
		'post_type'   => 'sos_filter',
		'numberposts' => - 1,
	) );
	?>

    <div class="sos-wrap container">
        <div class="row">
            <div class="col-12">
                <h1>Plugin Optimizer</h1>
            </div>
            <div class="col-12">
                <h2 id="name_page" class="filters">filters</h2>
            </div>


            <div class="row col-12 justify-content-between wrap-tabs">
                <div class="col-10 row">
                    <div id="window_filters" class="tabs col-2">filters</div>
                    <div id="window_categories" class="tabs col-2">categories</div>
                    <div id="window_groups" class="tabs col-2">groups</div>
                    <div id="window_worklist" class="tabs col-2">worklist</div>
                </div>
                <div class="col-2">
                    <input class="search" type="search" id="search_elements" name="s" value=""
                           placeholder="Search filters">
                </div>
            </div>
            <div class="row sos-content">
                <div class="row col-12 justify-content-between global-information">
                    <div class="col-3">
                        <button class="add-filter" id="add_elements"><span class="pluse">+</span> add new filter
                        </button>
                    </div>
                    <div class="col-2 quantity">
                        <span id="all_elements">all</span> (<span
                                id="count_all_elements"><?= wp_count_posts( 'sos_filter' )->publish; ?></span>) | <span
                                id="trash_elements">TRASH</span> (<span
                                id="count_trash_elements"><?= wp_count_posts( 'sos_filter' )->trash; ?></span>)
                    </div>
                </div>
                <div class="row col-12 ">
                    <div class="col-3">
                        <select id="check_all_elements">
                            <option value="default">Bulk actions</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button id="btn_apply">Apply</button>
                    </div>
                    <div class="col-3">
                        <select id="filter_all_elements">
                            <option value="default">All dates</option>
                            <option value="delete">November</option>
                        </select>
                        <button id="btn_filter">Filter</button>
                    </div>
                </div>
                <div class="row col-12">
                    <div class="col-12">
                        <table>
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="check_all"></th>
                                <th>TITLE</th>
                                <th>cATEGORIES</th>
                                <th>type</th>
                                <th>permalinks</th>
                                <th>Block plugins</th>
                            </tr>
                            </thead>
                            <tbody id="the-list">
							<?php
							$this->content_filters( $posts );
							?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<pre>


<?php

//var_dump(explode(', ', implode(', ', get_metadata( 'post', 3682, 'selected_page'))));
//var_dump(implode(', ', get_metadata( 'post', 3682, 'block_group_plugins')));
//var_dump(get_metadata( 'post', 3718));
//var_dump(get_metadata( 'post', 3718, 'block_value_plugins'));
//print_r( get_post_meta( '3727', 'block_value_plugins' ) );
//var_dump(get_post_meta( '3719', 'block_group_plugins'));
//var_dump($plugins);


//if ( in_category( 70, 3683 ) ) {
/*if ( has_term( 70, 'сategories_filters', 3683 ) ) {
	echo 'block';
}*/

/*if ( get_permalink( substr(implode(', ', get_metadata( 'post', 3682, 'selected_page')), -1 )) == "http://wad/calendar/" ) {
	echo trim( $_SERVER["REQUEST_URI"] );
}*/

/*$block_plugins = array('BuddyPress', 'Classic Editor', 'Contact Form 7');

$all_plugins = Simple_Online_Systems_Helper::get_plugins_with_status();
$activate_plugins = array();
$deactivate_plugins = array();
foreach ($all_plugins as $plugin) {
	if(in_array($plugin['name'], $block_plugins)){
		array_push($activate_plugins, $plugin['file']);
	}
}
print_r($activate_plugins);*/

/*$array_plugins = explode(', ', implode(', ', get_metadata( 'post', 3721, 'block_plugins' )));
print_r($array_plugins);
$array_plugins = get_post_meta( 3721, 'block_plugins', true);
print_r($array_plugins);*/

/*$all_plugins        = Simple_Online_Systems_Helper::get_plugins_with_status();
$activate_plugins   = array();
$deactivate_plugins = array();
foreach ( $all_plugins as $plugin ) {
	foreach ( $plugin as $key => $value ) {
		if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
			if ( $value ) {
				$activate_plugins[$plugin['name']] = $plugin['file'];
			} else {
				array_push( $deactivate_plugins, $plugin['name'] );
			}
		}
	}
}

print_r($activate_plugins);
foreach ( $activate_plugins as $name => $link ) {
    echo $name . ' = ' . $link;
}*/

?>
</pre>
