<?php

$should_alphabetize = get_option("po_should_alphabetize_menu");

$checked = $should_alphabetize ? ' checked="checked"' : '';

?>

<div class="sos-wrap">

    <?php PO_Admin_Helper::content_part__header("Settings", "settings"); ?>
    
    <div class="sos-content">
        <div id="settings_general" class="">
        
            <div class="row">
                
                <div class="col-2">
                    <span>Alphabetize the menu</span>
                </div>
                
                <div class="col-3">
                    <label>
                        <span class="switch">
                            <input id="should_alphabetize_menu" type="checkbox"<?php echo $checked ?>/>
                            <span class="slider round"></span>
                        </span>
                    </label>
                </div>
                
            </div>
            
            <div class="row">
            
                <div class="col-2">
                    <span>Recreate the menu</span>
                </div>
                
                <div class="col-3">
                    <a href="<?php echo admin_url( 'admin.php?page=plugin_optimizer_settings&po_original_menu=get&redirect_to=' . urlencode( site_url( $_SERVER["REQUEST_URI"] ) ) ) ?>">
                        <button class="po_green_button">Go!</button>
                    </a>
                </div>
                
            </div>
            
        </div>
    </div>
    
</div>
