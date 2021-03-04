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
        
        <div class="row sos-content">
        
            <div class="row col-12 content-new-element create-categories">
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
                                                    <span><input class="content-text" id="set_title" type="text" value="<?= $cat_title ?>"></span>
                                                </div>
                                            </div>
                                            <div class="header">Description</div>
                                            <div>
                                                <div class="content">
                                                    <span><textarea id="set_description" name="text"><?= $cat_desc ?></textarea></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="row">
                                    <button class="po_green_button save save-category" id="add_elements"><span class="pluse">+</span>
                                        Save new category
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




