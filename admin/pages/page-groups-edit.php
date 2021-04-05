<?php
$plugins = SOSPO_Admin_Helper::get_plugins_with_status();

// defaults
$page_title        = "Create a new Filter group";
$group_title       = "";
$plugins_to_block  = [];

// Editing existing group?

$group_id = ! empty( $_GET["group_id"] )  ? intval( $_GET["group_id"] ) : false;
$group    = $group_id                     ? get_post( $group_id )       : false;

if( $group ){
    
    $page_title         = "Editing group: " . $group->post_title;
    $group_title        = $group->post_title;
    $plugins_to_block   = get_post_meta( $group->ID, "group_plugins", true );

    if( ! empty( $plugins_to_block ) ){
        $plugins_to_block   = array_keys( $plugins_to_block );
    }
    
}
?>

<div class="sos-wrap">

    <?php SOSPO_Admin_Helper::content_part__header( $page_title, "groups" ); ?>
    
    <div id="edit_group" class="sos-content">
        <div class="row content-new-element">
            <div class="col-12">
                <div class="content-filter">
                    
                    <input type="hidden" name="SOSPO_filter_data[ID]" value="<?php echo $group ? $group->ID : "" ?>"/>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="header">Title</div>
                            <div>
                                <div class="content">
                                    <span><input class="content-text" id="set_title" type="text" name="SOSPO_filter_data[title]" value="<?php echo $group_title ?>" placeholder="The title of this group"/></span>
                                </div>
                            </div>
                        </div>
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
                </div>
                <div class="row">
                    <button id="save_group" class="po_green_button">Save group</button>
                </div>

            </div>
        </div>
    </div>
    
</div>
