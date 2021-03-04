<?php
$categories = get_categories( [
	'taxonomy'   => 'сategories_filters',
	'type'       => 'sos_filter',
	'parent'     => 0,
	'hide_empty' => 0,
] );

?>
<div class="wrap wrapper-filter">

    <div class="sos-wrap container">
    
        <?php PO_Admin_Helper::content_part__header("Filter categories", "filters_categories"); ?>
        
        <div class="row sos-content">
            <div class="row col-12 justify-content-between global-information">
            
                <div class="col-3">
                    <button class="po_green_button" id="add_elements"><span class="pluse">+</span> Add new Category</button>
                </div>
                
                <?php PO_Admin_Helper::content_part__bulk_actions( $categories ); ?>
                
                <div class="col-3 quantity">
                    <span id="all_elements">all</span> (<span id="count_all_elements"><?= wp_count_terms( 'сategories_filters' ); ?></span>)
                </div>
            </div>
            
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
                                                    <span><input class="content-text" id="set_title" type="text"></span>
                                                </div>
                                            </div>
                                            <div class="header">Description</div>
                                            <div>
                                                <div class="content">
                                                    <span><textarea id="set_description" name="text"></textarea></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row parent-category-wrapper">
                                        <div class="col-12">
                                            <div class="header">
                                                <div class="title">
                                                    Categories
                                                </div>
                                            </div>
                                            <div class="plugin-wrapper">
                                                <div class="content block none_parent select_parent_to_category">
                                                    <span value="None">None</span>
                                                </div>
												<?php

												if ( $categories ):
													foreach ( $categories as $cat ):
														?>
                                                        <div class="content select_parent_to_category">
                                                            <span value="<?= $cat->cat_ID; ?>"><?= $cat->cat_name; ?></span>
                                                        </div>
													<?php
													endforeach;
												endif;
												?>
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
            
            <div class="row col-12">
                <div class="col-12">
                    <table>
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check_all"></th>
                                <th>TITLE</th>
                            </tr>
                        </thead>
                        <tbody id="the-list" class="filter_on__status_publish">
                            <?php PO_Admin_Helper::list_content__categories( $categories ); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




