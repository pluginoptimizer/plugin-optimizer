<?php
$plugins = PO_Helper::get_plugins_with_status();

// po_mu_plugin()->write_log( $plugins, "page-add-filters-plugins" );

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
    
        <?php PO_Admin_Helper::content_part__header("Create a new Filter", "add-filters"); ?>
        
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
													<button class="po_green_button add-permalink">
                                                        <span class="pluse">+</span>
														Permalink
													</button>
												</div>
											</div>
										</div>
                                        
									</div>
									
                                    <div class="row block-plugin-wrapper">
										<div class="col-12">
                                        
											<div class="header">
												<div class="title">Plugins <span class="disabled">- <?= count( $plugins["all"] ); ?></span></div>
												<span class="count-plugin">( Active: <?= count( $plugins["active"] ); ?>   |   Inactive: <?= count( $plugins["inactive"] ); ?> )</span>
												<span class="all-check">Disable All</span>
											</div>
                                            
                                            <div class="header attribute-plugin">Active plugins</div>
                                                
											<?php PO_Admin_Helper::content_part__plugins( [ "plugins" => $plugins["active"] ] ); ?>
                                            
                                            <div class="header attribute-plugin">Inactive plugins</div>
                                            
											<?php PO_Admin_Helper::content_part__plugins( [ "plugins" => $plugins["inactive"], "inactive" => $plugins["inactive"] ] ); ?>
                                            
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
														<div class="single_group content" data-plugins="<?= htmlspecialchars(json_encode($block_plugins_in_group)) ?>">
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
														?>
														<div class="content">
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
									<button class="po_green_button save save-filter" id="add_elements"><span
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


