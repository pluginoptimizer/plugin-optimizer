<?php
$plugins = SOSPO_Admin_Helper::get_plugins_with_status();

$groups = get_posts( [
	'post_type'   => 'plgnoptmzr_group',
	'numberposts' => - 1,
] );

if( $groups ){
    
    usort( $groups, "SOSPO_Admin_Helper::sort__by_post_title" );
}

$categories = get_categories( [
	'taxonomy'   => 'plgnoptmzr_categories',
	'type'       => 'plgnoptmzr_filter',
	'hide_empty' => 0,
] );

// sospo_mu_plugin()->write_log( $categories, "page-add-filters-categories" );

// defaults
$page_title        = "Create a new Filter";
$filter_title      = "";
$filter_type       = "_endpoint";
$plugins_to_block  = [];
$groups_to_block   = [];
$filter_categories = [];
$endpoints         = [];
$is_premium        = false;
$block_editing     = false;

// Editing existing filter?

$filter_id = ! empty( $_GET["filter_id"] )  ? intval( $_GET["filter_id"] )  : false;
$filter    = $filter_id                     ? get_post( $filter_id )        : false;

if( $filter ){
    
    $page_title = "Editing filter: " . $filter->post_title;

    $filter_title       = $filter->post_title;
    $filter_type        = get_post_meta( $filter->ID, "filter_type", true );
    $plugins_to_block   = get_post_meta( $filter->ID, "plugins_to_block", true );
    $groups_to_block    = get_post_meta( $filter->ID, "groups_used", true );
    $filter_categories  = get_post_meta( $filter->ID, "categories", true );
    $endpoints          = SOSPO_Admin_Helper::get_filter_endpoints( $filter );
    
    $premium_filter     = get_post_meta( $filter->ID, 'premium_filter',   true );
    $is_premium         = $premium_filter === "true";
    
    $block_editing      = ( $is_premium && ! sospo_mu_plugin()->has_agent );
    
    if( ! empty( $plugins_to_block ) ){
        $plugins_to_block   = array_keys( $plugins_to_block );
    }
    
    if( ! empty( $groups_to_block ) ){
        $groups_to_block    = array_keys( $groups_to_block );
    }
    
    if( ! empty( $filter_categories ) ){
        $filter_categories  = array_keys( $filter_categories );
    }
    
} elseif( ! empty( $_GET["work_title"] ) && ! empty( $_GET["work_link"] ) ){
    
    $filter_title = sanitize_text_field( $_GET["work_title"] );
    $endpoints    = [ esc_url( $_GET["work_link"] ) ];
    
}

$title_class = 'col-9';

if( sospo_mu_plugin()->has_agent ){
    
    $title_class = 'col-6';
    
    $belongs_to  =  $filter ? get_post_meta( $filter->ID, "belongs_to", true ) : '';
    
    $belongs_to_core_selected = $belongs_to === "_core" ? ' selected="selected"' : '';
    
    // write_log( sospo_mu_plugin(), "sospo_mu_plugin()-page-filters-edit" );
    
    $plugin_select_options = '';
    
    foreach( sospo_mu_plugin()->all_plugins as $plugin_id => $plugin ){
        
        $selected = ! $belongs_to || $belongs_to !== $plugin_id ? '' : ' selected="selected"';
        
        $plugin_select_options .= '<option value="' . $plugin_id . '"' . $selected . '>';
        $plugin_select_options .=      $plugin["Name"];
        $plugin_select_options .= '</option>';
        $plugin_select_options .= PHP_EOL;
    }
    
}

?>

<style>
.additional_endpoint_wrapper:before{
    content: "<?php echo home_url() ?>";
}
</style>

<div class="sos-wrap">

    <?php SOSPO_Admin_Helper::content_part__header( $page_title, "filters" ); ?>
    
	<div id="edit_filter" class="sos-content">
    
    <?php if( $block_editing ){ ?>
        
        <div id="forbid_premium_edit">Premium filters can not be edited</div>
        
    <?php } else { ?>
        
		<div class="row content-new-element">
			<div class="col-12">
				<div class="content-filter">
                
					<div class="row filter_title">
                        
                        <input type="hidden" name="SOSPO_filter_data[ID]" value="<?php echo $filter ? $filter->ID : "" ?>"/>
                        
						<div class="<?php echo $title_class ?>">
							<div class="header">Title</div>
							<div>
								<div class="content enter-data">
									<span><input class="content-text" id="set_title" type="text" name="SOSPO_filter_data[title]" value="<?php echo $filter_title ?>" placeholder="The title of this filter"></span>
								</div>
							</div>
						</div>
                        
						<div class="col-3">
							<div class="header">Type</div>
							<div>
								<div class="content enter-data">
                                    <span>
                                        <select name="SOSPO_filter_data[type]" id="set_filter_type" data-selected="<?php echo $filter_type; ?>">
                                            <optgroup label="Default:">
                                                <option value="_endpoint">Endpoint(s)</option>
                                            </optgroup>
                                            <optgroup label="Edit page of a Post Type:" id="select_post_types"></optgroup>
                                        </select>
                                        <span id="loading_post_types">Loading..</span>
                                    </span>
								</div>
							</div>
						</div>
                        
                    <?php if( sospo_mu_plugin()->has_agent ){ ?>
                        
						<div class="col-3">
							<div class="header">Belongs to:</div>
							<div>
								<div class="content enter-data">
                                    <span>
                                        <select name="SOSPO_filter_data[belongs_to]" id="set_belongs_to" data-selected="<?php echo $belongs_to; ?>">
                                            <optgroup label="Default:">
                                                <option value="_core"<?php echo $belongs_to_core_selected; ?>>Core</option>
                                            </optgroup>
                                            <optgroup label="Plugin:">
                                                <?php echo $plugin_select_options; ?>
                                            </optgroup>
                                        </select>
                                    </span>
								</div>
							</div>
						</div>
                        
                    <?php } ?>
                        
					</div>
					
                    <div class="row select_trigger" id="endpoints_wrapper">
                    
						<div class="">
							<div class="header">Endpoints</div>
						</div>
                        
						<div class="additional_endpoint_wrapper">
                            <input id="first_endpoint" type="text" name="SOSPO_filter_data[endpoints][]" placeholder="Put your URL here" value="<?php echo ! empty( $endpoints ) ? $endpoints[0] : "" ?>"/>
                            <div id="add_endpoint" class="circle_button add_something">+</div>
						</div>
                        
                        <?php for( $i = 1; $i < count( $endpoints ); $i++ ){ ?>
                        
                            <div class="additional_endpoint_wrapper">
                                <input class="additional_endpoint" type="text" name="SOSPO_filter_data[endpoints][]" placeholder="Put your URL here" value="<?php echo $endpoints[ $i ] ?>"/>
                                <div class="remove_additional_endpoint circle_button remove_something">-</div>
                            </div>
                            
                        <?php } ?>
                        
					</div>
					
                    <div class="row block-plugin-wrapper">
						<div class="col-12">
                        
							<div class="header">
								<div class="title">Plugins <span class="disabled">- <?php echo count( $plugins["all"] ); ?></span></div>
								<span class="count-plugin">( Active: <?php echo count( $plugins["active"] ); ?>   |   Inactive: <?php echo count( $plugins["inactive"] ); ?> )</span>
								<span class="all-check toggle_plugins">Disable All</span>
							</div>
                            
                            <div class="header attribute-plugin">Active plugins</div>
                                
							<?php SOSPO_Admin_Helper::content_part__plugins( [ "plugins" => $plugins["active"],   "inactive" => [],                   "blocked" => $plugins_to_block ] ); ?>
                            
                            <div class="header attribute-plugin" style="margin-top: 10px;">Inactive plugins</div>
                            
							<?php SOSPO_Admin_Helper::content_part__plugins( [ "plugins" => $plugins["inactive"], "inactive" => $plugins["inactive"], "blocked" => $plugins_to_block ] ); ?>
                            
						</div>
					</div>
                    
					<div class="row block-group-plugin-wrapper">
						<div class="col-12">
							<div class="header">
								<div class="title">Groups <span class="disabled">- <?php echo count( $groups ); ?></span>
								</div>
								<span class="all-check toggle_groups">Disable All</span>
							</div>
							<div class="special_grid_list">
								<?php
								if ( $groups ){
									foreach ( $groups as $group ){
                                        $block_plugins_in_group = get_post_meta( $group->ID, 'group_plugins', true );
                                        $selected = in_array( $group->ID, $groups_to_block );
                                        $blocked  = $selected ? " blocked" : "";
                                        $checked  = $selected ? ' checked="checked"' : '';
										?>
										<div class="single_group content<?php echo $blocked ?>" data-plugins="<?php echo htmlspecialchars(json_encode($block_plugins_in_group)) ?>">
                                            <input class="noeyes" type="checkbox" name="SOSPO_filter_data[groups][<?php echo $group->ID ?>]" value="<?php echo $group->post_title ?>"<?php echo $checked ?>/>
											<span><?php echo $group->post_title; ?></span>
											<?php foreach ( $block_plugins_in_group as $block_plugin_in_group ){ ?>
												<div class="hidden_content">
													- <span><?php echo $block_plugin_in_group; ?></span>
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
							<div class="special_grid_list">
								<?php
								if ( $categories ){
									foreach ( $categories as $cat ){
                                        $selected = is_array($filter_categories) ? in_array( $cat->term_id, $filter_categories ) : false;
                                        $checked  = $selected ? ' checked="checked"' : '';
										?>
										<div class="single_category content<?php echo $selected ? " blocked" : "" ?>">
                                            <input class="noeyes" type="checkbox" name="SOSPO_filter_data[categories][<?php echo $cat->term_id ?>]" value="<?php echo $cat->cat_name ?>"<?php echo $checked ?>/>
											<span value="<?php echo $cat->term_id; ?>"><?php echo $cat->cat_name; ?></span>
										</div>
									<?php
									}
								}
								?>
								<div class="content before_add" id="add_category">
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
					<button id="save_filter" class="po_green_button">Save Filter</button>
				</div>

			</div>
		</div>
        
    <?php } ?>
	</div>
</div>
