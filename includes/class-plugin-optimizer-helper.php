<?php

/**
 * Class Plugin_Optimizer_Helper
 */
class Plugin_Optimizer_Helper {

	public static function get_plugins_with_status() {
		
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$plugins = [];

		$all_plugins    = get_plugins();
		$active_plugins = get_option( 'active_plugins' );

		foreach ( $active_plugins as $active_plugin_file ) {
			$plugins[] = [
				'name'      => $all_plugins[ $active_plugin_file ][ 'Name' ],
				'file'      => $active_plugin_file,
				'is_active' => 1,
			];
			unset( $all_plugins[ $active_plugin_file ] );
		}

		foreach ( $all_plugins as $file => $plugin_data ) {
			$plugins[] = [
				'name'      => $plugin_data[ 'Name' ],
				'file'      => $file,
				'is_active' => 0,
			];
		}

		return $plugins;

	}

}