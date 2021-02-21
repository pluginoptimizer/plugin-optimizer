<?php
$all_plugins      = Plugin_Optimizer_Helper::get_plugins_with_status();

$active_plugins   = array();
$inactive_plugins = array();

foreach ( $all_plugins as $plugin ) {
    
    if ( $plugin['name'] == 'Plugin Optimizer' ) {
        continue;
    }
    
    if ( $plugin['is_active'] ) {
        $active_plugins[ $plugin['file'] ]   = $plugin['name'];
    } else {
        $inactive_plugins[ $plugin['file'] ] = $plugin['name'];
    }
    
}

// po_mu_plugin()->write_log( $all_plugins, "page-add-filters-all_plugins" );
// po_mu_plugin()->write_log( $active_plugins, "page-add-filters-active_plugins" );
// po_mu_plugin()->write_log( $inactive_plugins, "page-add-filters-inactive_plugins" );

$groups = get_posts( [
	'post_type'   => 'sos_group',
	'numberposts' => - 1,
] );

$categories = get_categories( [
	'taxonomy'   => 'сategories_filters',
	'type'       => 'sos_filter',
	'hide_empty' => 0,
] );

?>
<div class="wrap wrapper-filter">

	<div class="sos-wrap container">
		<div class="row col-12">
			<h1>Plugin Optimizer</h1>
		</div>
		<div class="row col-12">
			<h2 id="name_page" class="add-filters">Create a new Filter</h2>
		</div>

		<div class="row col-12 justify-content-between wrap-tabs">
			<div class="col-10 row">
                <div id="window_filters"    class="tabs col-2">Filters</div>
                <div id="window_categories" class="tabs col-2">Categories</div>
                <div id="window_groups"     class="tabs col-2">Groups</div>
                <div id="window_worklist"   class="tabs col-2">Worklist</div>
                <div id="window_settings"   class="tabs col-2">Settings</div>
			</div>
			<div class="row col-2">
				<input class="search" type="search" id="search_elements" name="s" value="" placeholder="Search filters">
			</div>
		</div>
		<div class="row sos-content">

			<div class="row content-new-element">
				<div class="col-12">
					<table>
						<tr>
							<td colspan="6">
								<div class="content-filter">
                                
									<div class="row filter_title">
                                    
										<div class="col-12">
											<div class="header">Title</div>
											<div>
												<div class="content enter-data">
													<span><input class="content-text" id="set_title" type="text"></span>
												</div>
											</div>
										</div>
                                        
									</div>
									
                                    <div class="row select_trigger">
                                    
										<div class="col-3">
											<div class="header">Type</div>
											<div>
												<div class="content enter-data">
                                                    <span>
                                                        <select name="" id="set_type">
                                                        <option value="none">Choose type post</option>
                                                        <?php
                                                        $post_types         = get_post_types( [ 'publicly_queryable' => 1 ] );
                                                        $post_types['page'] = 'page';
                                                        // unset( $post_types[ 'attachment' ], $post_types[ 'sos_filter' ], $post_types[ 'sos_group' ], $post_types[ 'sos_work' ] );

                                                        foreach ( $post_types as $post_type ) {
	                                                        ?>
	                                                        <option value="<?= str_replace( ' ', "_", $post_type ); ?>"><?= $post_type; ?></option>
	                                                        <?php
                                                        }

                                                        ?>
                                                    </select>
                                                    </span>
												</div>
											</div>
										</div>
                                        
										<div class="col-9">
											<div class="header">Permalinks</div>
											<div class="content-permalinks">
												<div class="set_link">
													<input id="search_pages" type="text">
													<button class="add-filter add-permalink"><span
															class="pluse">+</span>
														Permalink
													</button>
												</div>
											</div>
										</div>
                                        
									</div>
									
                                    <div class="row block-plugin-wrapper">
										<div class="col-12">
                                        
											<div class="header">
												<div class="title">Plugins <span class="disabled">- <?= count( $all_plugins ) - 1; ?></span></div>
												<span class="count-plugin">( Active: <?= count( $active_plugins ); ?>   |   Inactive: <?= count( $inactive_plugins ); ?> )</span>
												<span class="all-check">Disable All</span>
											</div>
                                            
                                            <div class="header attribute-plugin">Active plugins</div>
                                                
											<?php Plugin_Optimizer_Admin_Helper::content_part__plugins( [ "plugins" => $active_plugins ] ) ?>
                                            
                                            <div class="header attribute-plugin">Inactive plugins</div>
                                            
											<?php Plugin_Optimizer_Admin_Helper::content_part__plugins( [ "plugins" => $inactive_plugins, "inactive" => $inactive_plugins ] ) ?>
                                            
										</div>
									</div>
                                    
									<div class="row block-group-plugin-wrapper">
										<div class="col-12">
											<div class="header">
												<div class="title">Groups <span class="disabled">- <?= count( $groups ); ?></span>
												</div>
												<span class="all-check">Disable All</span>
											</div>
											<div class="plugin-wrapper">
												<?php
												if ( $groups ){
													foreach ( $groups as $group ){
														?>
														<?php $block_plugins_in_group = explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) ); ?>
														<div class="toggle_group content" data-plugins="<?= htmlspecialchars(json_encode($block_plugins_in_group)) ?>">
															<span><?= $group->post_title; ?></span>
															<?php foreach ( $block_plugins_in_group as $block_plugin_in_group ){ ?>
																<div class="hidden_content">
																	- <span><?= $block_plugin_in_group; ?></span>
																</div>
															<?php } ?>
														</div>
													<?php
                                                    }
                                                }
												?>
											</div>
										</div>
									</div>
                                    
									<div class="row category-wrapper">
										<div class="col-12">
											<div class="header">
												<div class="title">Categories</div>
											</div>
											<div class="plugin-wrapper">
												<?php
												if ( $categories ):
													foreach ( $categories as $cat ):
														?>
														<div class="content">
															<span value="<?= $cat->cat_ID; ?>"><?= $cat->cat_name; ?></span>
														</div>
													<?php
													endforeach;
												endif;
												?>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<button class="add-filter save save-filter" id="add_elements"><span
											class="pluse">+</span> save new filter
									</button>
								</div>

							</td>
						</tr>
					</table>
				</div>


			</div>
		</div>
	</div>
</div>


