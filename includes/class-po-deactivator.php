<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    PluginOptimizer
 * @subpackage PluginOptimizer/includes
 * @author     Web Dev <some@some.com>
 */
class PO_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 */
	public static function deactivate() {
		unlink( WPMU_PLUGIN_DIR . '/class-po-mu.php' );
	}

}
