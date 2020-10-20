<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Simple_Online_Systems
 * @subpackage Simple_Online_Systems/includes
 * @author     Web Dev <some@some.com>
 */
class Simple_Online_Systems_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'simple-online-systems',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
