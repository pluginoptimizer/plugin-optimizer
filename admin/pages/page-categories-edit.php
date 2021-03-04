<?php
// defaults
$page_title = "Create a new category";
$cat_title  = "";
$cat_desc   = "";


$cat_id = ! empty( $_GET["cat_id"] )  ? $_GET["cat_id"]                             : false;
$cat    = $cat_id                     ? get_term( $cat_id, "сategories_filters" )   : false;

if( $cat ){
    
    $page_title = "Editing category: " . $cat->name;
    $cat_title  = $cat->name;
    $cat_desc   = $cat->description;
    
}

?>
<div class="wrap wrapper-filter">

    <div class="sos-wrap container">
    
        <?php PO_Admin_Helper::content_part__header( $page_title, "filters_categories"); ?>
        
        <div id="edit_category" class="row sos-content">
            <div class="row content-new-element">
                <div class="col-12">
                    <div class="content-filter">
                        
                        <input type="hidden" name="PO_filter_data[ID]" value="<?= $cat ? $cat->term_id : "" ?>"/>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="header">Title</div>
                                <div>
                                    <div class="content">
                                        <span><input class="content-text" id="set_title" type="text" name="PO_filter_data[title]" value="<?= $cat_title ?>"></span>
                                    </div>
                                </div>
                                <div class="header">Description</div>
                                <div>
                                    <div class="content">
                                        <span><textarea id="set_description" name="PO_filter_data[description]" name="text"><?= $cat_desc ?></textarea></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <button id="save_category" class="po_green_button"><span class="pluse">+</span> Save Category</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>




