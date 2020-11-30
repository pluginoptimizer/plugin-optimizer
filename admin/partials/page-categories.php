<?php
$categories = get_categories( [
	'taxonomy'      => 'сategories_filters',
	'type'          => 'sos_filter',
	'parent'       => 0,
	'hide_empty'    => 0,
] );

?>
<div class="wrap wrapper-filter">
	<h1>Filters categories</h1>
	<form action="" class="created-cat">
		<p>Group Title</p>
		<div id="group_name_error">
			<div class="wrapper_group_name_error">
				<p class="popup-close">×</p>
				<div id="result_search">This name is already in use</div>
			</div>
		</div>
		<input type="text" placeholder="Enter category title" name="title_cat" id="title_cat">
		<p>Select parent</p>
		<select name="cat_parents" id="cat_parents">
			<option value="none">None</option>
			<?php
			if( $categories ):
				foreach( $categories as $cat ):
					?>
					<option value="<?= $cat->cat_ID; ?>"><?= $cat->cat_name; ?></option>
				<?php
				endforeach;
			endif;
			?>
		</select>
		<br><br>
		<input type="submit" value="Create new category">
	</form>


</div>


<div class="sos-wrap container">
	<div class="row">
		<div class="col-12">
			<h1>Plugin Optimizer</h1>
		</div>
		<div class="col-12">
			<h2 id="name_page" class="filters_categories">Filters categories</h2>
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
					<button class="add-filter"><span class="pluse">+</span> add new category</button>
				</div>
				<div class="col-8 quantity">
					<span id="all_elements">all</span> (<span id="count_all_elements"><?= wp_count_terms( 'category' ); ?></span>)
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
						</tr>
						</thead>
						<tbody id="the-list">
						<?php
						$this->content_filters_categories($categories);
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>




