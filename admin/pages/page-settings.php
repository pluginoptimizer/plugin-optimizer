<?php
$plugins = PO_Admin_Helper::get_plugins_with_status();
?>

<div class="sos-wrap">

    <?php PO_Admin_Helper::content_part__header("Settings", "settings"); ?>
    
    <div class="sos-content">
        <div id="settings_general" class="row col-12">
            <div class="col-1">
                <!-- Rounded switch -->
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                </label>
            </div>
            <div class="col-11">
                <span>Some checkbox option</span>
            </div>
        </div>
    </div>
</div>
