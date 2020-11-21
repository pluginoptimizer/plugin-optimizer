<div class="wrap wrapper-filter">
    <h1>Filtres</h1>
    <form action="" class="created-filters">
        <p>Filter Title</p>
        <input type="text" placeholder="Enter filter title" name="title_filter">
        <p>Set Type</p>
        <input type="text" placeholder="Enter type" name="type_filter">
        <p>Select category</p>
        <input type="text" placeholder="Enter category" name="category_filter">
        <p>Add Permalinks</p>
        <input type="text" id="search_pages" placeholder="Enter name page" name="pages">

        <div id="result">
            <p class="popup-close">×</p>
            <div id="result_search"></div>
        </div>

        <p>Add post type</p>
        <select name="post_type" multiple>
            <?php
            $post_types           = get_post_types( [ 'publicly_queryable' => 1 ] );
            $post_types[ 'page' ] = 'page';
            unset( $post_types[ 'attachment' ], $post_types[ 'sos_filter' ], $post_types[ 'sos_group' ] );

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
            $plugins = Simple_Online_Systems_Helper::get_plugins_with_status();
            foreach ( $plugins as $plugin => $value ): ?>
                <option value="<?= str_replace( ' ', "_", $value[ 'name' ] ); ?>"><?= $value[ 'name' ]; ?></option>
            <?php endforeach; ?>
        </select>
        <p>Select block group plugins</p>
        <select name="block_group_plugins" multiple>
            <?php
            $posts = get_posts( array(
	            'post_type'   => 'sos_group',
	            'numberposts' => -1,
            ) );
            foreach( $posts as $post ){
	            ?>
                <option value="<?= str_replace( ' ', "_", $post->post_title ); ?>"><?= $post->post_title; ?></option>
	            <?php
            }
            ?>
        </select>
        <br><br>
        <input type="submit" value="Create new filter">
    </form>


</div>

<?php
$posts = get_posts( array(
    'post_type'   => 'sos_filter',
    'numberposts' => -1,
) );
?>

<div class="sos-wrap container">
    <div class="row">
        <div class="col-12">
            <h1>Plugin Optimizer</h1>
        </div>
        <div class="col-12">
            <h2 id="name_page">filters</h2>
        </div>


        <div class="row col-12 justify-content-between wrap-tabs">
            <div class="col-10 row">
                <div class="tabs col-2">filters</div>
                <div class="tabs col-2">categories</div>
                <div class="tabs col-2">groups</div>
                <div class="tabs col-2">worklist</div>
            </div>
            <div class="col-2">
                <input class="search" type="search" id="search_elements" name="s" value="" placeholder="Search filters">
            </div>
        </div>
        <div class="row sos-content">
            <div class="row col-12 justify-content-between global-information">
                <div class="col-3">
                    <button class="add-filter"><span class="pluse">+</span> add new filter</button>
                </div>
                <div class="col-2 quantity">
                    <span id="all_elements">all</span> (<span id="count_all_elements"><?= wp_count_posts('sos_filter')->publish; ?></span>) | <span id="trash_elements">TRASH</span> (<span id="count_trash_elements"><?= wp_count_posts('sos_filter')->trash; ?></span>)
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
						$this->content_filters($posts);
						?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
//var_dump(get_metadata( 'post', 3620, 'block_plugins' ));
//var_dump(get_metadata( 'post', 3620, 'block_group_plugins' ));

echo implode(', ', array_diff(explode(', ', implode(', ', get_metadata( 'post', 3620, 'block_group_plugins' ))), ["Duplicate Page"]));
$array = explode(', ', implode(', ', get_metadata( 'post', 3620, 'block_group_plugins' )));
array_push($array, "WP Rocket");
echo implode(', ', $array);
