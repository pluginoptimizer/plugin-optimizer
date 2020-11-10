<?php
$posts = get_posts( array(
	'post_type'   => 'sos_work',
	'numberposts' => -1,
) );
?>
<div class="sos-wrap container">
    <div class="row">
        <div class="col-12">
            <h1>Plugin Optimizer</h1>
        </div>
        <div class="col-12">
            <h2>worklist</h2>
        </div>


        <div class="row col-12 justify-content-between wrap-tabs">
            <div class="col-10 row">
                <div class="tabs col-2">filters</div>
                <div class="tabs col-2">categories</div>
                <div class="tabs col-2">groups</div>
                <div class="tabs col-2">worklist</div>
            </div>
            <div class="col-2">
                <div class="search">seARCH</div>
            </div>
        </div>
        <div class="row sos-content">
            <div class="row col-12 justify-content-end">
                <div class="col-2 quantity">
                    <b>all</b> (1) | TRASH (2)
                </div>
            </div>
            <div class="row col-12">
                <div class="col-3">
                    <select id="filter">
                        <option value="default">Bulk actions</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button>Apply</button>
                </div>
                <div class="col-3">
                    <select id="filter">
                        <option value="default">All dates</option>
                        <option value="delete">November</option>
                    </select>
                    <button>Filter</button>
                </div>
            </div>
            <div class="row col-12">
                <div class="col-12">
                    <table>
                        <thead>
                        <tr>
                            <th><input type="checkbox"></th>
                            <th>PAGE TITLE</th>
                            <th>permalink</th>
                            <th>Date Created</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach( $posts as $post ):
						?>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td><?= $post->post_title; ?></td>
                                <td><?= esc_url(implode( '', get_metadata( 'post', $post->ID, 'post_link' ))); ?></td>
                                <td><?= substr(str_replace( '-', '/', str_replace(" ", " at ", $post->post_date)), 0 , -3) . ' pm'; ?></td>
                                <td>
                                    <a class="row-title" href="<?= esc_url(get_admin_url(null, 'admin.php?page=simple_online_systems_filters&work_title=' . urlencode(str_replace(' ', '_', str_replace('Add filter to ', '', $post->post_title))) . '&work_link=' . urlencode(esc_url(implode( '', get_metadata( 'post', $post->ID, 'post_link' )))))); ?>" aria-label="“<?/*= $post->post_title; */?>” (Edit)">
                                        <button><span class="pluse">+</span> add new filter</button>
                                    </a>
                                </td>
                            </tr>
                        <?php
                            endforeach;
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>