<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Optimizer
 * @subpackage Plugin_Optimizer/includes
 * @author     Web Dev <some@some.com>
 */
class Plugin_Optimizer_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 */
	public static function activate() {
		copy(__DIR__ . '/class-plugin-optimizer-mu.php', WPMU_PLUGIN_DIR.'/class-plugin-optimizer-mu.php' );
	}

}
