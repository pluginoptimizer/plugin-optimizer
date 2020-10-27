<?php

/**
 * The plugin bootstrap file
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin Optimizer
 * Plugin URI:        plugin-uri.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Web Dev
 * Author URI:        author-uri.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-online-systems
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Use SemVer - https://semver.org
 */
define( 'SIMPLE_ONLINE_SYSTEMS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-simple-online-systems-activator.php
 */
function activate_simple_online_systems() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-online-systems-activator.php';
	Simple_Online_Systems_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-simple-online-systems-deactivator.php
 */
function deactivate_simple_online_systems() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-online-systems-deactivator.php';
	Simple_Online_Systems_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simple_online_systems' );
register_deactivation_hook( __FILE__, 'deactivate_simple_online_systems' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-simple-online-systems.php';

/**
 * Begins execution of the plugin.
 */
function run_simple_online_systems() {

	$plugin = new Simple_Online_Systems();
	$plugin->run();

}
run_simple_online_systems();
