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
 * Text Domain:       plugin-optimizer
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
 * This action is documented in includes/class-plugin-optimizer-activator.php
 */
function activate_plugin_optimizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-optimizer-activator.php';
	Plugin_Optimizer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-optimizer-deactivator.php
 */
function deactivate_plugin_optimizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-optimizer-deactivator.php';
	Plugin_Optimizer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_optimizer' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_optimizer' );

/**
 * Composer Integration
 */
require plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plugin-optimizer.php';

/**
 * Begins execution of the plugin.
 */
function run_plugin_optimizer() {

	$plugin = new Plugin_Optimizer();
	$plugin->run();
	$plugin->appsero_init_tracker_plugin_optimizer();

}

run_plugin_optimizer();