<?php
$plugins     = PO_Helper::get_plugins_with_status();
?>
<div class="wrap wrapper-filter">

    <div class="sos-wrap container">
    
        <?php PO_Admin_Helper::content_part__header("Create a new filter group", "add-groups"); ?>
        
        <div class="row sos-content">
            <div class="row content-new-element">
                <div class="col-12">
                    <table>
                        <tr>
                            <td colspan="6">
                                <div class="content-filter">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="header">Title</div>
                                            <div>
                                                <div class="content">
                                                    <span><input class="content-text" id="set_title" type="text"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row block-plugin-wrapper">
                                        <div class="col-12">
                                            
                                            <div class="header">
                                                <div class="title">Select plugins <span class="disabled">- <?= count( $plugins["all"] ); ?></span></div>
                                                <span class="all-check">Disable All</span>
                                                <span class="count-plugin">( Active: <?= count( $plugins["active"] ); ?>   |   Inactive: <?= count( $plugins["inactive"] ); ?> )</span>
                                            </div>
                                            
                                            <?php PO_Admin_Helper::content_part__plugins( [ "plugins" => $plugins["all"], "inactive" => $plugins["inactive"] ] ); ?>
                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <button class="po_green_button save save-group" id="add_elements"><span class="pluse">+</span> Save new group
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
