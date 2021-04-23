<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Plugin Optimizer
 * Plugin URI:        https://pluginoptimizer.com
 * Description:       The Most Powerful Performance Plugin for WordPress is now available for FREE.
 * Version:           1.0.8
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
 * Current plugin version.
 * Use SemVer - https://semver.org
 */
define( 'SOSPO_VERSION', '1.0.8' );

// let's install the MU plugin if it's missing or outdated and refresh
if( ! file_exists( WPMU_PLUGIN_DIR . '/class-po-mu.php') || ! function_exists("sospo_mu_plugin") || sospo_mu_plugin()->version !== SOSPO_VERSION ){
    
    if( ! file_exists( WPMU_PLUGIN_DIR ) ){
        
        mkdir( WPMU_PLUGIN_DIR );
        chmod( WPMU_PLUGIN_DIR, 0755 );
    }

    copy( __DIR__ . '/includes/class-po-mu.php', WPMU_PLUGIN_DIR . '/class-po-mu.php' );
    
    header("Refresh:0");
    
    return;
}

/**
 * Initialize the plugin trackers
 *
 * @return void
 */
global $sospo_appsero;
function appsero_init_tracker_plugin_optimizer() {
    
    global $sospo_appsero;
    
    $sospo_appsero = [];
    
    if ( ! class_exists( 'Appsero\Client' ) ) {
        require_once __DIR__ . '/vendor/autoload.php';
    }
    
    $sospo_appsero["free"] = new Appsero\Client( 'c5104b7b-7b26-4f52-b690-45ef58f9ba31', 'Plugin Optimizer', __FILE__ );
    $sospo_appsero["free"]->insights()->init();// Activate insights
    $sospo_appsero["free"]->updater();//          Activate automatic updater
    
    
    $active_plugins = ! empty( sospo_mu_plugin()->original_active_plugins ) ? sospo_mu_plugin()->original_active_plugins : get_option('active_plugins');
    
    if( ! in_array( "plugin-optimizer-premium/plugin-optimizer-premium.php", $active_plugins ) ){
        
        return;
    }
    
    $sospo_appsero["premium"] = new Appsero\Client( 'ae74f660-483b-425f-9c31-eced50ca019f', 'Plugin Optimizer Premium', plugin_dir_path( __DIR__ ) . 'plugin-optimizer-premium/plugin-optimizer-premium.php' );
    $sospo_appsero["premium"]->insights()->init();// Activate insights
    $sospo_appsero["premium"]->updater();//          Activate automatic updater
    
    // Activate license page and checker
    $args = array(
        'type'        => 'submenu', // Can be: menu, options, submenu
        'menu_title'  => 'Premium Settings',
        'page_title'  => 'Plugin Optimizer Premium Settings',
        'menu_slug'   => 'plugin_optimizer_premium_settings',
        'parent_slug' => 'plugin_optimizer',
    );
    // $sospo_appsero["premium"]->license()->add_settings_page( $args );
}
appsero_init_tracker_plugin_optimizer();

/**
 * The code that runs during plugin activation.
 */
function activate_plugin_optimizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-po-activator.php';
	SOSPO_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_plugin_optimizer' );

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_plugin_optimizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-po-deactivator.php';
	SOSPO_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_plugin_optimizer' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-po.php';

new PluginOptimizer();

// ------------------------ Helpers and Testers

if( ! function_exists( 'write_log' ) ){
    
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
// add_filter( "plgnoptmzr_overview_tabs", "test_overview_page_hook", 10, 1 );


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


