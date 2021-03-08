<?php
$plugins = PO_Admin_Helper::get_plugins_with_status();
?>

<div class="sos-wrap">

    <?php PO_Admin_Helper::content_part__header("Settings", "settings"); ?>
    
    <div class="sos-content">
        <div id="settings_general" class="row col-12">
        
            <div class="col-12">
                <!-- Rounded switch -->
                <label>
                    <span class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                    </span>
                    <span>Alphabetize the menu</span>
                </label>
            </div>
        </div>
    </div>
</div>
