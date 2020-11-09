<?php
$posts = get_posts( array(
	'post_type' => 'sos_work',
	'numberposts' => -1,
) );
?>
<div class="wrap">
	<h1 class="wp-heading-inline">Worklist</h1>

	<hr class="wp-header-end">

	<h2 class="screen-reader-text">Filter posts list</h2>
	<ul class="subsubsub">
		<li class="all"><a href="edit.php?post_type=sos_work" class="current" aria-current="page">All <span class="count">(<?= count($posts); ?>)</span></a> |</li>
		<li class="publish"><a href="edit.php?post_status=publish&amp;post_type=sos_work">Published <span class="count">(<?= wp_count_posts('sos_work')->publish; ?>)</span></a> |</li>
		<li class="trash"><a href="edit.php?post_status=trash&amp;post_type=sos_work">Trash <span class="count">(<?= wp_count_posts('sos_work')->trash; ?>)</span></a></li>
	</ul>
	<form id="posts-filter" method="get">

		<p class="search-box">
			<input type="search" id="search_works" name="s" value="" placeholder="Search work">
		</p>

		<input type="hidden" name="post_status" class="post_status_page" value="all">
		<input type="hidden" name="post_type" class="post_type_page" value="sos_work">



		<input type="hidden" id="_wpnonce" name="_wpnonce" value="1107e74268"><input type="hidden" name="_wp_http_referer" value="/wp-admin/edit.php?post_type=sos_work">
		<div class="tablenav top">

			<div class="alignleft actions bulkactions">
				<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label><select name="action" id="bulk-action-selector-top">
					<option value="-1">Bulk actions</option>
					<option value="edit" class="hide-if-no-js">Edit</option>
					<option value="trash">Move to Trash</option>
				</select>
				<input type="submit" id="doaction" class="button action" value="Apply">
			</div>
			<div class="tablenav-pages one-page"><span class="displaying-num"><?= count($posts); ?> item</span>
				<span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                    <span class="paging-input"><label for="current-page-selector" class="screen-reader-text">Current Page</label><input class="current-page" id="current-page-selector" type="text" name="paged" value="1" size="1" aria-describedby="table-paging"><span class="tablenav-paging-text"> of <span class="total-pages">1</span></span></span>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span>
			</div>
			<br class="clear">
		</div>
		<h2 class="screen-reader-text">Posts list</h2>
		<table class="wp-list-table widefat fixed striped table-view-list posts">
			<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
				<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href="/wp-admin/edit.php?post_type=sos_work&amp;orderby=title&amp;order=asc"><span>Title</span><span class="sorting-indicator"></span></a></th>
				<th scope="col" id="date" class="manage-column column-date sortable asc"><a href="/wp-admin/edit.php?post_type=sos_work&amp;orderby=date&amp;order=desc"><span>Date</span><span class="sorting-indicator"></span></a></th>
			</tr>
			</thead>

			<tbody id="the-list">


			<?php
			foreach( $posts as $post ):
			?>
			<tr id="post-205" class="iedit author-self level-0 post-205 type-sos_work status-publish hentry pmpro-has-access">
				<th scope="row" class="check-column"> <label class="screen-reader-text" for="cb-select-205">
						Select <?= $post->post_title; ?> </label>
					<input id="cb-select-205" type="checkbox" name="post[]" value="205">
					<div class="locked-indicator">
						<span class="locked-indicator-icon" aria-hidden="true"></span>
						<span class="screen-reader-text">
                                “<?= $post->post_title; ?>” is locked </span>
					</div>
				</th>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
					<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
					<strong>
						<a class="row-title" href="<?= get_admin_url(null, 'admin.php?page=simple_online_systems_filters&work_title=' . urlencode(str_replace(' ', '_', str_replace('Add filter to ', '', $post->post_title))) . '&work_link=' . urlencode(implode( '', get_metadata( 'post', $post->ID, 'post_link' )))); ?>" aria-label="“<?= $post->post_title; ?>” (Edit)">
							<?= $post->post_title; ?>
						</a>
					</strong>

					<div class="row-actions">
						<span class="edit">
							<a href="<?= get_edit_post_link($post->ID); ?>" aria-label="Edit “<?= $post->post_title; ?>”">Edit</a> |
						</span>
						<span class="trash">
							<a href="<?= get_delete_post_link($post->ID); ?>" class="submitdelete" aria-label="Move “<?= $post->post_title; ?>” to the Trash">
								Trash
							</a>
						</span>
						<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td class="date column-date" data-colname="Date">Published<br><?= substr(str_replace( '-', '/', str_replace(" ", " at ", $post->post_date)), 0 , -3) . ' pm'; ?></td>
			</tr>
				<?php
			endforeach;
			?>



			</tbody>

			<tfoot>
			<tr>
				<td class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2">Select All</label><input id="cb-select-all-2" type="checkbox"></td>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="/wp-admin/edit.php?post_type=sos_work&amp;orderby=title&amp;order=asc"><span>Title</span><span class="sorting-indicator"></span></a></th>
				<th scope="col" class="manage-column column-date sortable asc"><a href="/wp-admin/edit.php?post_type=sos_work&amp;orderby=date&amp;order=desc"><span>Date</span><span class="sorting-indicator"></span></a></th>
			</tr>
			</tfoot>

		</table>
		<div class="tablenav bottom">

			<div class="alignleft actions bulkactions">
				<label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label><select name="action2" id="bulk-action-selector-bottom">
					<option value="-1">Bulk actions</option>
					<option value="edit" class="hide-if-no-js">Edit</option>
					<option value="trash">Move to Trash</option>
				</select>
				<input type="submit" id="doaction2" class="button action" value="Apply">
			</div>
			<div class="alignleft actions">
			</div>
			<div class="tablenav-pages one-page"><span class="displaying-num"><?= count($posts); ?> item</span>
				<span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                    <span class="screen-reader-text">Current Page</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">1 of <span class="total-pages">1</span></span></span>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span>
			</div>
			<br class="clear">
		</div>

	</form>
</div>


