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

// po_mu_plugin()->write_log( $categories, "page-add-filters-categories" );

// defaults
$page_title        = "Create a new Filter";
$filter_title      = "";
$filter_type       = "_endpoint";
$plugins_to_block  = [];
$groups_to_block   = [];
$filter_categories = [];
$endpoints         = [];

$show_endpoints_wrapper = '';

// Editing existing filter?

$filter_id = ! empty( $_GET["filter_id"] )  ? $_GET["filter_id"]        : false;
$filter    = $filter_id                     ? get_post( $filter_id )    : false;

if( $filter ){
    
    $page_title = "Editing filter: " . $filter->post_title;

    $filter_title       = $filter->post_title;
    $filter_type        = get_post_meta( $filter->ID, "filter_type", true );
    $plugins_to_block   = get_post_meta( $filter->ID, "plugins_to_block", true );
    $groups_to_block    = get_post_meta( $filter->ID, "groups_used", true );
    $filter_categories  = get_post_meta( $filter->ID, "categories", true );
    
    if( ! empty( $plugins_to_block ) ){
        $plugins_to_block   = array_keys( $plugins_to_block );
    }
    
    if( ! empty( $groups_to_block ) ){
        $groups_to_block    = array_keys( $groups_to_block );
    }
    
    if( ! empty( $filter_categories ) ){
        $filter_categories  = array_keys( $filter_categories );
    }
    
    // po_mu_plugin()->write_log( $filter_type, "page-filters-edit-filter_type" );
    
    if( $filter_type == "_endpoint" || ! in_array( $filter_type, $post_types ) ){
        
        $filter_type = "_endpoint";
        $endpoints   = PO_Admin_Helper::get_filter_endpoints( $filter );
        
    } else {
        
        $show_endpoints_wrapper = ' style="display: none;"';
    }
    
} elseif( ! empty( $_GET["work_title"] ) && ! empty( $_GET["work_link"] ) ){
    
    $filter_title = $_GET["work_title"];
    $endpoints    = [ $_GET["work_link"] ];
    
}


?>
<div class="wrap wrapper-filter">

	<div class="sos-wrap container">
    
        <?php PO_Admin_Helper::content_part__header( $page_title, "add-filters" ); ?>
        
		<div id="edit_filter" class="row sos-content">
			<div class="row content-new-element">
				<div class="col-12">
					<div class="content-filter">
                    
						<div class="row filter_title">
                            
                            <input type="hidden" name="PO_filter_data[ID]" value="<?= $filter ? $filter->ID : "" ?>"/>
                            
							<div class="col-9">
								<div class="header">Title</div>
								<div>
									<div class="content enter-data">
										<span><input class="content-text" id="set_title" type="text" name="PO_filter_data[title]" value="<?= $filter_title ?>" placeholder="The title of this filter"></span>
									</div>
								</div>
							</div>
                            
							<div class="col-3">
								<div class="header">Type</div>
								<div>
									<div class="content enter-data">
                                        <span>
                                            <select name="PO_filter_data[type]" id="set_type">
                                                <optgroup label="Default:">
                                                    <option value="_endpoint"<?= $filter_type == "_endpoint" ? ' selected="selected"' : "" ?>>Endpoint(s)</option>
                                                </optgroup>
                                                <optgroup label="Edit page of a Post Type:">
                                                    <?php
                                                    foreach ( $post_types as $post_type ) {
                                                        
                                                        $selected = $filter_type == $post_type ? ' selected="selected"' : "";
                                                        
                                                        echo '<option value="' . $post_type . '"' . $selected . '>' . $post_type . '</option>';
                                                        
                                                    }
                                                    ?>
                                                </optgroup>
                                            </select>
                                        </span>
									</div>
								</div>
							</div>
                            
						</div>
						
                        <div class="row select_trigger" id="endpoints_wrapper"<?= $show_endpoints_wrapper ?>>
                        
							<div class="col-12">
								<div class="header">Endpoints</div>
							</div>
                            
							<div class="col-12 additional_endpoint_wrapper">
                                <input id="first_endpoint" type="text" name="PO_filter_data[endpoints][]" placeholder="Put your URL here" value="<?= ! empty( $endpoints ) ? $endpoints[0] : "" ?>"/>
                                <div id="add_endpoint" class="circle_button add_something">+</div>
							</div>
                            
                            <?php for( $i = 1; $i < count( $endpoints ); $i++ ){ ?>
                            
                                <div class="col-12 additional_endpoint_wrapper">
                                    <input class="additional_endpoint" type="text" name="PO_filter_data[endpoints][]" placeholder="Put your URL here" value="<?= $endpoints[ $i ] ?>"/>
                                    <div class="remove_additional_endpoint circle_button remove_something">-</div>
                                </div>
                                
                            <?php } ?>
                            
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
                                            $block_plugins_in_group = get_post_meta( $group->ID, 'group_plugins', true );
                                            $selected = in_array( $group->ID, $groups_to_block );
                                            $blocked  = $selected ? " blocked" : "";
                                            $checked  = $selected ? ' checked="checked"' : '';
											?>
											<div class="single_group content<?= $blocked ?>" data-plugins="<?= htmlspecialchars(json_encode($block_plugins_in_group)) ?>">
                                                <input class="noeyes" type="checkbox" name="PO_filter_data[groups][<?= $group->ID ?>]" value="<?= $group->post_title ?>"<?= $checked ?>/>
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
                                            $selected = in_array( $cat->term_id, $filter_categories );
                                            $checked  = $selected ? ' checked="checked"' : '';
											?>
											<div class="single_category content<?= $selected ? " blocked" : "" ?>">
                                                <input class="noeyes" type="checkbox" name="PO_filter_data[categories][<?= $cat->term_id ?>]" value="<?= $cat->cat_name ?>"<?= $checked ?>/>
												<span value="<?= $cat->term_id; ?>"><?= $cat->cat_name; ?></span>
											</div>
										<?php
										}
									}
									?>
									<div class="create_category content before_add" id="add_category">
                                        <span class="circle_button add_something before_add">+</span><span class="before_add"> Create New</span>
                                        <input class="during_add" type="text" name="new_category_name" value="" placeholder="Category Name"/>
                                        <span class="circle_button remove_something during_add cancel">-</span>
                                        <span class="circle_button add_something during_add ok">&#10003;</span>
									</div>
								</div>
							</div>
						</div>

					</div>

					<div class="row">
						<button id="save_filter" class="po_green_button"><span class="pluse">+</span> Save Filter</button>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>


