<?php
$plugins = PO_Helper::get_plugins_with_status();

// defaults
$page_title        = "Create a new Filter group";
$group_title       = "";
$plugins_to_block  = [];

// Editing existing group?

$group_id = ! empty( $_GET["group_id"] )  ? $_GET["group_id"]        : false;
$group    = $group_id                     ? get_post( $group_id )    : false;

if( $group ){
    
    $page_title         = "Editing group: " . $group->post_title;
    $group_title        = $group->post_title;
    $plugins_to_block   = get_post_meta( $group->ID, "group_plugins", true );

    if( ! empty( $plugins_to_block ) ){
        $plugins_to_block   = array_keys( $plugins_to_block );
    }
    
    // $filter_title       = $filter->post_title;
    // $filter_type        = get_post_meta( $filter->ID, "filter_type", true );
    // $plugins_to_block   = get_post_meta( $filter->ID, "plugins_to_block", true );
    // $groups_to_block    = get_post_meta( $filter->ID, "groups_used", true );
    // $filter_categories  = get_post_meta( $filter->ID, "categories", true );
    
    // if( ! empty( $groups_to_block ) ){
        // $groups_to_block    = array_keys( $groups_to_block );
    // }
    
    // if( ! empty( $filter_categories ) ){
        // $filter_categories  = array_keys( $filter_categories );
    // }
    
    // if( $filter_type == "_endpoint" || ! in_array( $filter_type, $post_types ) ){
        
        // $filter_type = "_endpoint";
        // $endpoints   = PO_Admin_Helper::get_filter_endpoints( $filter );
        
    // } else {
        
        // $show_endpoints_wrapper = ' style="display: none;"';
    // }
    
}
?>
<div class="wrap wrapper-filter">

    <div class="sos-wrap container">
    
        <?php PO_Admin_Helper::content_part__header( $page_title, "add-groups" ); ?>
        
        <div id="edit_group" class="row sos-content">
            <div class="row content-new-element">
                <div class="col-12">
                    <table>
                        <tr>
                            <td colspan="6">
                                <div class="content-filter">
                                
                                <?php if( $group ){ ?>
                                    <input type="hidden" name="PO_filter_data[ID]" value="<?= $group->ID ?>"/>
                                <?php } ?>
                        
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="header">Title</div>
                                            <div>
                                                <div class="content">
                                                    <span><input class="content-text" id="set_title" type="text" name="PO_filter_data[title]" value="<?= $group_title ?>" placeholder="The title of this group"/></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row block-plugin-wrapper">
                                        <div class="col-12">
                                            
                                            <div class="header">
                                                <div class="title">Select plugins <span class="disabled">- <?= count( $plugins["all"] ); ?></span></div>
                                                <span class="all-check toggle_plugins">Disable All</span>
                                                <span class="count-plugin">( Active: <?= count( $plugins["active"] ); ?>   |   Inactive: <?= count( $plugins["inactive"] ); ?> )</span>
                                            </div>
                                            
                                            <?php PO_Admin_Helper::content_part__plugins( [ "plugins" => $plugins["all"], "inactive" => $plugins["inactive"], "blocked" => $plugins_to_block ] ); ?>
                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <button id="save_group" class="po_green_button"><span class="pluse">+</span> Save new group
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
