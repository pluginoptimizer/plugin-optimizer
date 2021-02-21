<?php
$posts = get_posts( array(
	'post_type'   => 'sos_group',
	'numberposts' => - 1,
) );
?>
<div class="wrap wrapper-filter">

    <div class="sos-wrap container">
    
        <?php Plugin_Optimizer_Admin_Helper::content_part__header("Create a new filter group", "add-groups"); ?>
        
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
												<?php
												$all_plugins        = Plugin_Optimizer_Helper::get_plugins_with_status();
												$activate_plugins   = array();
												$deactivate_plugins = array();
												foreach ( $all_plugins as $plugin ) {
													foreach ( $plugin as $key => $value ) {
														if ( $key === 'is_active' && $plugin['name'] !== 'Plugin Optimizer' ) {
															if ( $value ) {
																$activate_plugins[ $plugin['name'] ] = $plugin['file'];
															} else {
																$deactivate_plugins[ $plugin['name'] ] = $plugin['file'];
															}
														}
													}
												}
												?>
                                                <div class="title">Select plugins <span class="disabled">- <?= count( $all_plugins ) - 1; ?></span></div>
                                                <span class="all-check">Disable All</span>
                                                <span class="count-plugin">( Active: <?= count( $activate_plugins ); ?>   |   Inactive: <?= count( $deactivate_plugins ); ?> )</span>
                                            </div>
											<?php
											if ( $activate_plugins ):
												?>
                                                <div class="plugin-wrapper">
													<?php
													foreach ( $activate_plugins as $activate_plugin => $activate_plugin_link ):
														?>
                                                        <div class="content">
                                                            <span value="<?= $activate_plugin_link ?>"><?= $activate_plugin; ?></span>
                                                        </div>
													<?php
													endforeach;
													foreach ( $deactivate_plugins as $deactivate_plugin => $deactivate_plugin_link ):
														?>
                                                        <div class="content deactivate-plugin">
                                                            <span value="<?= $deactivate_plugin_link ?>"><?= $deactivate_plugin; ?></span>
                                                        </div>
													<?php
													endforeach;
													?>
                                                </div>
											<?php
											else:
												?>
                                                <div class="plugin-wrapper no-plugins">
                                                    <div class="content">
                                                        <span>No activate plugins for blocking</span>
                                                    </div>
                                                </div>
											<?php
											endif;
											?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <button class="add-filter save save-group" id="add_elements"><span class="pluse">+</span> save new group
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
