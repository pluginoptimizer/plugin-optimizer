<div class="wrap wrapper-filter">
	<?php
	$categories = get_categories( [
		'taxonomy'   => 'сategories_filters',
		'type'       => 'sos_filter',
		'parent'     => 0,
		'hide_empty' => 0,
	] );

	?>
    <div class="sos-wrap container">
        <div class="row col-12">
            <h1>Plugin Optimizer</h1>
        </div>
        <div class="row col-12">
            <h2 id="name_page" class="filters_categories">Filters categories</h2>
        </div>


        <div class="row col-12 justify-content-between wrap-tabs">
            <div class="col-10 row">
                <div id="window_filters" class="tabs col-2">filters</div>
                <div id="window_categories" class="tabs col-2">categories</div>
                <div id="window_groups" class="tabs col-2">groups</div>
                <div id="window_worklist" class="tabs col-2">worklist</div>
            </div>
            <div class="row col-2">
                <input class="search" type="search" id="search_elements" name="s" value="" placeholder="Search categories">
            </div>
        </div>
        <div class="row sos-content">
            <div class="row col-12 justify-content-between global-information">
                <div class="col-3">
                    <button class="add-filter" id="add_elements"><span class="pluse">+</span> add new category</button>
                </div>
                <div class="col-8 quantity">
                    <span id="all_elements">all</span> (<span
                            id="count_all_elements"><?= wp_count_terms( 'сategories_filters' ); ?></span>)
                </div>
            </div>
            <div class="row col-12 content-new-element">
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
                                                    <!--                                                    <span><textarea class="content-text" id="set_title" type="text"></span>-->
                                                    <span><textarea id="set_description" name="text"></textarea></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row parent-category-wrapper">
                                        <div class="col-12">
                                            <div class="header">
                                                <div class="title">
                                                    categories
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
                                    <button class="add-filter save save-category" id="add_elements"><span class="pluse">+</span>
                                        save new category
                                    </button>
                                </div>

                            </td>
                        </tr>
                    </table>
                </div>


            </div>
            <div class="row col-12 ">
                <div class="col-3">
                    <select id="check_all_elements">
                        <option value="default">Bulk actions</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button id="btn_apply">Apply</button>
                </div>
                <div class="col-3">
                    <select id="filter_all_elements">
                        <option value="default">All dates</option>
                        <option value="delete">November</option>
                    </select>
                    <button id="btn_filter">Filter</button>
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
                        <tbody id="the-list">
						<?php
						$this->content_filters_categories( $categories );
						?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




