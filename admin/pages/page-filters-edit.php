<?php
$plugins = PO_Helper::get_plugins_with_status();

$groups = get_posts( [
	'post_type'   => 'sos_group',
	'numberposts' => - 1,
] );

$categories = get_categories( [
	'taxonomy'   => 'сategories_filters',
	'type'       => 'sos_filter',
	'hide_empty' => 0,
] );

// TODO If all plugins are blocked on this page, this will return only posts and pages
$post_types = get_post_types( [ 'publicly_queryable' => 1 ] );
$post_types['page'] = 'page';
unset( $post_types[ 'attachment' ], $post_types[ 'sos_filter' ], $post_types[ 'sos_group' ], $post_types[ 'sos_work' ] );

natcasesort( $post_types );

// po_mu_plugin()->write_log( $post_types, "page-add-filters-post_types" );

$page_title = "Create a new Filter";
$plugins_to_block  = [];
$groups_to_block   = [];
$endpoint          = [];
$filter_categories = [];

// Editing existing filter?

$filter_id = ! empty( $_GET["filter_id"] )  ? $_GET["filter_id"]        : false;
$filter    = $filter_id                     ? get_post( $filter_id )    : false;

if( $filter ){
    
    // po_mu_plugin()->write_log( $filter, "page-add-filters-post_types" );
    
    $page_title = "Editing filter: " . $filter->post_title;

    $plugins_to_block   = get_post_meta( $filter->ID, "block_value_plugins", true );
    $groups_to_block    = get_post_meta( $filter->ID, "block_group_plugins", true );
    $filter_categories  = explode( ", ", get_post_meta( $filter->ID, "category_filter", true ) );
    
    $endpoints = PO_Admin_Helper::get_filter_endpoints( $filter );
    
}


?>
<div class="wrap wrapper-filter">

	<div class="sos-wrap container">
    
        <?php PO_Admin_Helper::content_part__header( $page_title, "add-filters"); ?>
        
		<div id="edit_filter" class="row sos-content">
			<div class="row content-new-element">
				<div class="col-12">
					<table>
						<tr>
							<td colspan="6">
								<div class="content-filter">
                                
									<div class="row filter_title">
                                    
										<div class="col-9">
											<div class="header">Title</div>
											<div>
												<div class="content enter-data">
													<span><input class="content-text" id="set_title" type="text"  name="[PO_filter_data][title]"value="<?= $filter ? $filter->post_title : "" ?>" placeholder="The title of this filter"></span>
												</div>
											</div>
										</div>
                                        
										<div class="col-3">
											<div class="header">Type</div>
											<div>
												<div class="content enter-data">
                                                    <span>
                                                        <select name="[PO_filter_data][type]" id="set_type">
                                                            <optgroup label="Default:">
                                                                <option value="endpoint">Endpoint(s)</option>
                                                            </optgroup>
                                                            <optgroup label="Edit page of a Post Type:">
                                                                <?php
                                                                foreach ( $post_types as $post_type ) {
                                                                    
                                                                    echo '<option value="' . $post_type . '">' . $post_type . '</option>';
                                                                    
                                                                }
                                                                ?>
                                                            </optgroup>
                                                        </select>
                                                    </span>
												</div>
											</div>
										</div>
                                        
									</div>
									
                                    <div class="row select_trigger" id="endpoints_wrapper">
                                    
										<div class="col-12">
											<div class="header">Endpoints</div>
										</div>
										<div class="col-9">
                                            <input id="search_pages" type="text" name="[PO_filter_data][endpoints][]" placeholder="Put your URL here" style="width: 100%;"/>
										</div>
										<div class="col-3">
                                            <button class="add-permalink po_green_button" style="width: 100%;">
                                                <span class="pluse">+</span> Endpoint
                                            </button>
										</div>
                                        
									</div>
									
                                    <div class="row block-plugin-wrapper">
										<div class="col-12">
                                        
											<div class="header">
												<div class="title">Plugins <span class="disabled">- <?= count( $plugins["all"] ); ?></span></div>
												<span class="count-plugin">( Active: <?= count( $plugins["active"] ); ?>   |   Inactive: <?= count( $plugins["inactive"] ); ?> )</span>
												<span class="all-check toggle_plugins">Disable All</span>
											</div>
                                            
                                            <div class="header attribute-plugin">Active plugins</div>
                                                
											<?php PO_Admin_Helper::content_part__plugins( [ "plugins" => $plugins["active"],   "inactive" => [],                   "blocked" => $plugins_to_block ] ); ?>
                                            
                                            <div class="header attribute-plugin">Inactive plugins</div>
                                            
											<?php PO_Admin_Helper::content_part__plugins( [ "plugins" => $plugins["inactive"], "inactive" => $plugins["inactive"], "blocked" => $plugins_to_block ] ); ?>
                                            
										</div>
									</div>
                                    
									<div class="row block-group-plugin-wrapper">
										<div class="col-12">
											<div class="header">
												<div class="title">Groups <span class="disabled">- <?= count( $groups ); ?></span>
												</div>
												<span class="all-check toggle_groups">Disable All</span>
											</div>
											<div class="plugin-wrapper">
												<?php
												if ( $groups ){
													foreach ( $groups as $group ){
                                                        $block_plugins_in_group = explode( ', ', get_post_meta( $group->ID, 'group_plugins', true ) );
                                                        $selected = in_array( $group->post_title, $groups_to_block );
                                                        $blocked  = $selected ? " blocked" : "";
                                                        $checked  = $selected ? ' checked="checked"' : '';
														?>
														<div class="single_group content<?= $blocked ?>" data-plugins="<?= htmlspecialchars(json_encode($block_plugins_in_group)) ?>">
                                                            <input class="noeyes" type="checkbox" name="[PO_filter_data][groups][<?= $group->ID ?>]" value="<?= $group->post_title ?>"<?= $checked ?>/>
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
												if ( $categories ){
													foreach ( $categories as $cat ){
                                                        $selected = in_array( $cat->cat_name, $filter_categories );
                                                        $checked  = $selected ? ' checked="checked"' : '';
														?>
														<div class="single_category content<?= $selected ? " blocked" : "" ?>">
                                                            <input class="noeyes" type="checkbox" name="[PO_filter_data][categories][<?= $cat->cat_ID ?>]" value="<?= $cat->cat_name ?>"<?= $checked ?>/>
															<span value="<?= $cat->cat_ID; ?>"><?= $cat->cat_name; ?></span>
														</div>
													<?php
													}
												}
												?>
											</div>
										</div>
									</div>

								</div>

								<div class="row">
									<button id="save_filter" class="po_green_button"><span class="pluse">+</span> Save Filter</button>
								</div>

							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


