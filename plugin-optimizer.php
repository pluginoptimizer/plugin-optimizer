<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Plugin Optimizer
 * Plugin URI:        https://pluginoptimizer.com
 * Description:       The Most Powerful Performance Plugin for WordPress is now available for FREE.
 * Version:           1.0.1
 * Author:            pluginoptimizer
 * Author URI:        https://pluginoptimizer.com/about/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-optimizer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_plugin_optimizer() {
    if ( ! class_exists( 'Appsero\Client' ) ) {
        // require_once __DIR__ . '/appsero/src/Client.php';
        require_once __DIR__ . '/vendor/autoload.php';
    }
    $client = new Appsero\Client( 'c5104b7b-7b26-4f52-b690-45ef58f9ba31', 'Plugin Optimizer', __FILE__ );
    // Active insights
    $client->insights()->init();
    // Active automatic updater
    $client->updater();
}
appsero_init_tracker_plugin_optimizer();

/**
 * Currently plugin version.
 * Use SemVer - https://semver.org
 */
define( 'SOS_PO_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-po-activator.php
 */
function activate_plugin_optimizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-po-activator.php';
	PO_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-po-deactivator.php
 */
function deactivate_plugin_optimizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-po-deactivator.php';
	PO_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_optimizer' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_optimizer' );

// TODO - let's copy the file there if it's missing
if( ! file_exists( WPMU_PLUGIN_DIR . '/class-po-mu.php') ){
    
    // TODO should we add the MU plugin at this point?
    // copy( __DIR__ . '/class-po-mu.php', WPMU_PLUGIN_DIR . '/class-po-mu.php' );
    
    return;
}


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-po.php';

/**
 * Begins execution of the plugin.
 */
function run_plugin_optimizer() {

	$plugin = new PluginOptimizer();

}

run_plugin_optimizer();

if( ! function_exists( 'write_log' ) ){//                                                           Write to debug.log
    
    function write_log ( $log, $text = "write_log: ", $file_name = "debug.log" )  {
        
        $file = WP_CONTENT_DIR . '/' . $file_name;
        
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( $text . PHP_EOL . print_r( $log, true ) . PHP_EOL, 3, $file );
        } else {
            error_log( $text . PHP_EOL . $log . PHP_EOL . PHP_EOL, 3, $file );
        }
        
    }

}


function test_overview_page_hook( $tabs ){
    
    // po_mu_plugin()->write_log( $tabs, "test_overview_page_hook-tabs" );
    
    $tabs[25] = [
        "title"     => "Added by a hook",
        "content"   => "And our content goes here"
    ];
    
    ksort( $tabs );
    
    return $tabs;
}
add_filter( "sos_po_overview_tabs", "test_overview_page_hook", 10, 1 );