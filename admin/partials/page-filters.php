<div class="wrap wrapper-filter">
    <h1>Filtres</h1>
    <form action="" class="created-filters">
        <p>Filter Title</p>
        <input type="text" placeholder="Enter filter title" name="title_filter">
        <p>Set Type</p>
        <input type="text" placeholder="Enter type" name="type_filter">
        <p>Add Permalinks</p>
        <input type="text" value="<?= get_home_url() . '/'; ?>" placeholder="Enter permalinks" name="pages">
        <p>Add post type</p>
        <select name="post_type">
            <?php
            $post_types           = get_post_types( [ 'publicly_queryable' => 1 ] );
            $post_types[ 'page' ] = 'page';       // встроенный тип не имеет publicly_queryable
            unset( $post_types[ 'attachment' ] );

            foreach ( $post_types as $post_type ) {
                ?>
                <option value="<?= str_replace( ' ', "_", $post_type ); ?>"><?= $post_type; ?></option>
                <?php
            }

            ?>
        </select>
        <p>Select block plugins</p>
        <select name="block_plugins">
            <?php
            $plugins = Simple_Online_Systems_Helper::get_plugins_with_status();
            foreach ( $plugins as $plugin => $value ): ?>
                <option value="<?= str_replace( ' ', "_", $value[ 'name' ] ); ?>"><?= $value[ 'name' ]; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <input type="submit" value="Create new filter">
    </form>


    <div id="col-right">
        <div class="col-wrap">
            <form id="posts-filter" method="post" class="showed-filter">
                <input type="hidden" name="taxonomy" value="Group">
                <input type="hidden" name="post_type" value="filters">

                <input type="hidden" id="_wpnonce" name="_wpnonce" value="70fff724a7"><input type="hidden" name="_wp_http_referer" value="/wp-admin/edit-tags.php?taxonomy=Group&amp;post_type=filters">
                <div class="tablenav top">

                    <div class="alignleft actions bulkactions">
                        <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label><select name="action" id="bulk-action-selector-top">
                            <option value="-1">Bulk Actions</option>
                            <option value="delete">Delete</option>
                        </select>
                        <input type="submit" id="doaction" class="button action" value="Apply">
                    </div>
                    <div class="tablenav-pages no-pages"><span class="displaying-num">0 items</span>
                        <span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                            <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                            <span class="paging-input"><label for="current-page-selector" class="screen-reader-text">Current Page</label><input class="current-page" id="current-page-selector" type="text" name="paged" value="1" size="1" aria-describedby="table-paging"><span class="tablenav-paging-text"> of <span class="total-pages">0</span></span></span>
                            <a class="next-page button" href="/wp-admin/edit-tags.php?taxonomy=Group&amp;post_type=filters&amp;paged=0"><span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
                            <a class="last-page button" href="/wp-admin/edit-tags.php?taxonomy=Group&amp;post_type=filters&amp;paged=0"><span class="screen-reader-text">Last page</span><span aria-hidden="true">»</span></a></span>
                    </div>
                    <!-- TODO: / ... Всюди юзай відносний шлях! -->
                    <br class="clear">
                </div>
                <h2 class="screen-reader-text">Categories list</h2>
                <table class="wp-list-table widefat fixed striped tags">
                    <thead>
                    <tr>
                        <td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
                        <th scope="col" id="name" class="manage-column column-name column-primary sortable desc"><a href="/wp-admin/edit-tags.php?taxonomy=Group&amp;post_type=filters&amp;orderby=name&amp;order=asc"><span>Name</span><span class="sorting-indicator"></span></a></th>
                        <th scope="col" id="description" class="manage-column column-description sortable desc"><a href="/wp-admin/edit-tags.php?taxonomy=Group&amp;post_type=filters&amp;orderby=description&amp;order=asc"><span>Selected pages</span><span class="sorting-indicator"></span></a></th>
                        <th scope="col" id="slug" class="manage-column column-slug sortable desc"><a href="/wp-admin/edit-tags.php?taxonomy=Group&amp;post_type=filters&amp;orderby=slug&amp;order=asc"><span>Block plugins</span><span class="sorting-indicator"></span></a></th>
                        <th scope="col" id="posts" class="manage-column column-posts num sortable desc"><a href="/wp-admin/edit-tags.php?taxonomy=Group&amp;post_type=filters&amp;orderby=count&amp;order=asc"><span>Count</span><span class="sorting-indicator"></span></a></th>
                    </tr>
                    </thead>

                    <tbody id="the-list" data-wp-lists="list:tag">
                    <?php
                    $posts = get_posts( array(
                        'post_type' => 'sos_filter',
                        'numberposts' => -1,
                    ) );
                    foreach( $posts as $post ){
                        ?>
                        <tr id="tag-7" class="level-0">
                            <th scope="row" class="check-column"><label class="screen-reader-text" for="cb-select-7">Select <?= $post->post_title; ?></label><input type="checkbox" name="delete_tags[]" value="7" id="cb-select-7"></th>
                            <td class="name column-name has-row-actions column-primary" data-colname="Name"><strong><a class="row-title" href="<?= get_edit_post_link(); ?>" aria-label="“<?= $post->post_title; ?>” (Edit)"><?= $post->post_title; ?></a></strong><br>
                                <div class="hidden" id="inline_7">
                                    <div class="name"><?= $post->post_title; ?></div>
                                    <div class="slug"><?= $post->post_title; ?></div>
                                    <div class="parent">0</div>
                                </div>
                                <div class="row-actions"><span class="edit"><a href="<?= get_edit_post_link(); ?>" aria-label="Edit “<?= $post->post_title; ?>”">Edit</a> | </span><span class="inline hide-if-no-js"><button type="button" class="button-link editinline" aria-label="Quick edit “<?= $post->post_title; ?>” inline" aria-expanded="false">Quick&nbsp;Edit</button> | </span><span class="delete"><a href="<?= get_delete_post_link(); ?>" class="delete-tag aria-button-if-js" aria-label="Delete “<?= $post->post_title; ?>”" role="button">Delete</a></span>
                                </div>
                                <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span>
                                </button>
                            </td>
                            <td class="description column-description" data-colname="Selected pages"><span aria-hidden="true"><?= implode( ",", get_metadata( 'post', $post->ID, 'selected_post_type' ) ); ?></span><span class="screen-reader-text">No description</span></td>
                            <td class="slug column-slug" data-colname="Block plugins"><?= implode( get_metadata( 'post', $post->ID, 'block_plugins' )); ?></td>
                            <td class="posts column-posts" data-colname="Count"><?= wp_count_posts()->publish; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>

                    <tfoot>
                    <tr>
                        <td class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2">Select All</label><input id="cb-select-all-2" type="checkbox"></td>
                        <th scope="col" class="manage-column column-name column-primary sortable desc"><a href="/wp-admin/edit-tags.php?taxonomy=Group&amp;post_type=filters&amp;orderby=name&amp;order=asc"><span>Name</span><span class="sorting-indicator"></span></a></th>
                        <th scope="col" class="manage-column column-description sortable desc"><a href="/wp-admin/edit-tags.php?taxonomy=Group&amp;post_type=filters&amp;orderby=description&amp;order=asc"><span>Selected pages</span><span class="sorting-indicator"></span></a></th>
                        <th scope="col" class="manage-column column-slug sortable desc"><a href="/wp-admin/edit-tags.php?taxonomy=Group&amp;post_type=filters&amp;orderby=slug&amp;order=asc"><span>Block plugins</span><span class="sorting-indicator"></span></a></th>
                        <th scope="col" class="manage-column column-posts num sortable desc"><a href="/wp-admin/edit-tags.php?taxonomy=Group&amp;post_type=filters&amp;orderby=count&amp;order=asc"><span>Count</span><span class="sorting-indicator"></span></a></th>
                    </tr>
                    </tfoot>

                </table>
                <div class="tablenav bottom">

                    <div class="alignleft actions bulkactions">
                        <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label><select name="action2" id="bulk-action-selector-bottom">
                            <option value="-1">Bulk Actions</option>
                            <option value="delete">Delete</option>
                        </select>
                        <input type="submit" id="doaction2" class="button action" value="Apply">
                    </div>
                    <div class="tablenav-pages no-pages"><span class="displaying-num">0 items</span>
                        <span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                            <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                            <span class="screen-reader-text">Current Page</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">1 of <span class="total-pages">0</span></span></span>
                            <a class="next-page button" href="/wp-admin/edit-tags.php?taxonomy=Group&amp;post_type=filters&amp;paged=0"><span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
                            <a class="last-page button" href="/wp-admin/edit-tags.php?taxonomy=Group&amp;post_type=filters&amp;paged=0"><span class="screen-reader-text">Last page</span><span aria-hidden="true">»</span></a></span>
                    </div>
                    <br class="clear">
                </div>

            </form>

        </div>
    </div>
</div>
