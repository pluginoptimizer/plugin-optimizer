<?php
$plugins = Plugin_Optimizer_Helper::get_plugins_with_status();
?>
<div class="wrap">

    <div class="sos-wrap container">
    
        <?php Plugin_Optimizer_Admin_Helper::content_part__header("Settings", "settings"); ?>
        
        <div class="row sos-content">
            <div class="row col-12 justify-content-end">
                <div class="col-6 quantity">
                    <span id="show_settings_general">General</span> | <span
                            id="show_settings_plugins">Plugin Search</span>
                    | <span id="show_settings_debug">Debug SOS</span>
                </div>
            </div>
            <div id="settings_general" class="row col-12">
                <div class="col-1">
                    <!-- Rounded switch -->
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-11">
                    <span>Display Debug Messages</span>
                </div>
            </div>
            <div id="settings_plugins" class="col-12">
                <div class="row col-12 justify-content-end">
                    <div class="col-8 quantity">
                        <span class="active-plugin" id="activate_plugins">Active</span> (<span
                              class="count-active-plugin"><?= count( $plugins["active"] ); ?>)</span> | <span
                              class="inactive-plugin" id="deactivate_plugins">Inactive</span> (<span
                              class="count-trash-plugin"><?= count( $plugins["inactive"] ); ?></span>)
                    </div>
                </div>
                
                <div class="row col-12">
                    <div class="col-12">
                        <table>
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="check_all"></th>
                                <th>Name Plugin</th>
                            </tr>
                            </thead>
                            <tbody id="the-list">
							<?php Plugin_Optimizer_Helper::content_list_plugins( $plugins["active"] ); ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div id="settings_debug" class="row col-12">
                <div class="col-1">
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-11">
                    <span>Debug: SOS will need to provide additional details on this item</span>
                </div>
            </div>
        </div>
    </div>

</div>
