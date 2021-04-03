<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Plugin Optimizer
 * Plugin URI:        https://pluginoptimizer.com
 * Description:       The Most Powerful Performance Plugin for WordPress is now available for FREE.
 * Version:           1.0.6
 * Author:            Plugin Optimizer
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
define( 'SOSPO_VERSION', '1.0.6' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-po-activator.php
 */
function activate_plugin_optimizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-po-activator.php';
	SOSPO_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-po-deactivator.php
 */
function deactivate_plugin_optimizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-po-deactivator.php';
	SOSPO_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_optimizer' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_optimizer' );

// let's install the MU plugin if it's missing and refresh
$should_copy_mu = false;

if( ! file_exists( WPMU_PLUGIN_DIR . '/class-po-mu.php') || ! function_exists("sospo_mu_plugin") || sospo_mu_plugin()->version !== SOSPO_VERSION ){
    
    $should_copy_mu = true;
    
}

if( $should_copy_mu ){
    
    if( ! file_exists( WPMU_PLUGIN_DIR ) ){
        
        mkdir( WPMU_PLUGIN_DIR );
        chmod( WPMU_PLUGIN_DIR, 0755 );
    }

    copy( __DIR__ . '/includes/class-po-mu.php', WPMU_PLUGIN_DIR . '/class-po-mu.php' );
    
    header("Refresh:0");
    
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
    
    // sospo_mu_plugin()->write_log( $tabs, "test_overview_page_hook-tabs" );
    
    
    $tabs[25] = [
        "title"     => "Added by a hook",
        "content"   => "And our content goes here"
    ];
    
    ksort( $tabs );
    
    return $tabs;
}
// add_filter( "sos_po_overview_tabs", "test_overview_page_hook", 10, 1 );


function test_post_state( $post_states, $post ){
    
    sospo_mu_plugin()->write_log( $post_states, "test_post_state-post_id: " . $post->ID . ", post_type: " . $post->post_type );
    
    if( $post->ID / 2 != ceil( $post->ID / 2 ) ){
        
        $post_states["test"] = "PO Optimized";
    }
    
    return $post_states;
}
// add_filter( "display_post_states", "test_post_state", 10, 2 );

function test_temp(){
    
   sospo_mu_plugin()->write_log( print_r( $GLOBALS['wp_scripts']->registered, true ), "test_temp-globals-wp_scripts" );
}
// add_action( "shutdown", "test_temp" );


