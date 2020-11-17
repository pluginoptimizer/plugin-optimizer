<div class="wrap wrapper-filter">
	<h1>Groups plugin</h1>
	<form action="" class="created-groups">
		<p>Group Title</p>
		<input type="text" placeholder="Enter filter title" name="title_group">
		<p>Set Type</p>
		<input type="text" placeholder="Enter type" name="type_group">
		<p>Select plugins</p>
		<select name="group_plugins" multiple>
			<?php
			$plugins = Simple_Online_Systems_Helper::get_plugins_with_status();
			foreach ( $plugins as $plugin => $value ): ?>
				<option value="<?= str_replace( ' ', "_", $value[ 'name' ] ); ?>"><?= $value[ 'name' ]; ?></option>
			<?php endforeach; ?>
		</select>
		<br><br>
		<input type="submit" value="Create new group">
	</form>


</div>




<?php
$posts = get_posts( array(
	'post_type'   => 'sos_group',
	'numberposts' => -1,
) );
?>

<div class="sos-wrap container">
	<div class="row">
		<div class="col-12">
			<h1>Plugin Optimizer</h1>
		</div>
		<div class="col-12">
			<h2 id="name_page">groups</h2>
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
					<button class="add-filter"><span class="pluse">+</span> add new group</button>
				</div>
				<div class="col-2 quantity">
					<span id="all_elements">all</span> (<span id="count_all_elements"><?= wp_count_posts('sos_group')->publish; ?></span>) | <span id="trash_elements">TRASH</span> (<span id="count_trash_elements"><?= wp_count_posts('sos_group')->trash; ?></span>)
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
							<th>type</th>
							<th>Plugins</th>
							<th>Count</th>
						</tr>
						</thead>
						<tbody id="the-list">
						<?php
						$this->content_groups($posts);
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>








