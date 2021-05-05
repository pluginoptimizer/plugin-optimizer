<?php
/**
 * Plugin Name:       Plugin Optimizer MU
 * Plugin URI:        https://pluginoptimizer.com
 * Description:       This MU plugin is required by the Plugin Optimizer plugin. It will be removed upon deactivation.
 * Version:           1.0.8
 * Author:            Plugin Optimizer
 * Author URI:        https://pluginoptimizer.com/about/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 */

class SOSPO_MU {

    public $version                 = "1.0.8-4";

    protected static $instance      = null;

    public $current_url             = false;
    public $wp_relative_url         = false;

    public $po_plugins              = [];
    protected $po_pages             = [];
    protected $po_post_types        = [];
    protected $po_ajax_actions      = [];

    public $is_po_default_page      = false;
    public $is_being_filtered       = false;
    public $is_skipped              = false;

    public $all_plugins             = [];
    public $original_active_plugins = [];
    public $filtered_active_plugins = [];
    public $plugins_to_block        = [];
    public $blocked_plugins         = [];
    public $filters_in_use          = [];

    public $has_premium             = false;
    public $has_agent               = false;

    private function __construct() {

        $this->current_full_url         = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->current_wp_relative_url  = str_replace( site_url(), "", $this->current_full_url );

        $this->po_ajax_actions = [
            // Free
            'po_save_filter',
            'po_save_group',
            'po_save_category',
            'po_create_category',
            'po_delete_elements',
            'po_publish_elements',
            'po_turn_filter_on',
            'po_turn_filter_off',
            'po_mark_tab_complete',
            'po_save_option_alphabetize_menu',
            'po_turn_off_filter',
            'po_save_original_menu',
            // 'po_get_post_types',// excluded because it wouldn't work
            'po_scan_prospector',
            // Agent
            'po_retrieve_filters_func',
            'po_compile_filters_func',
            'po_submit_filters_func',
            'po_send_approval_func',
            'po_delete_filter_func',
            // Premium
            'po_retrieve_filters_func',
            'po_compile_filters_func',
        ];
        $this->po_plugins = [
            "plugin-optimizer/plugin-optimizer.php",
            "plugin-optimizer-agent/plugin-optimizer-agent.php",
            "plugin-optimizer-premium/plugin-optimizer-premium.php",
        ];
        $this->po_pages = [
            "/wp-admin/admin.php?page=plugin_optimizer",
            "/wp-admin/admin.php?page=plugin_optimizer_filters",
            "/wp-admin/admin.php?page=plugin_optimizer_add_filters",
            "/wp-admin/admin.php?page=plugin_optimizer_filters_categories",
            "/wp-admin/admin.php?page=plugin_optimizer_add_categories",
            "/wp-admin/admin.php?page=plugin_optimizer_groups",
            "/wp-admin/admin.php?page=plugin_optimizer_add_groups",
            "/wp-admin/admin.php?page=plugin_optimizer_worklist",
            "/wp-admin/admin.php?page=plugin_optimizer_settings",
            "/wp-admin/admin.php?page=plugin_optimizer_support",
            "/wp-admin/admin.php?page=plugin_optimizer_agent",
            "/wp-admin/admin.php?page=plugin_optimizer_pending",
            "/wp-admin/admin.php?page=plugin_optimizer_approved",
            "/wp-admin/admin.php?page=plugin_optimizer_premium"
        ];
        $this->po_post_types = [
            "plgnoptmzr_filter",
            "plgnoptmzr_group",
            "plgnoptmzr_work",
        ];

        if( $this->should_abort() ){
            return;
        }

        $this->set_hooks();

    }

    private function should_abort(){

        if( wp_doing_cron() ){

            return true;
        }

        if( wp_doing_ajax() ){

            if( empty( $_POST["action"] ) ){

                return true;
            }


            if( ! in_array( $_POST["action"], $this->po_ajax_actions ) ){

                // $this->write_log( $_POST["action"], "mu_plugin-should_abort-doing_ajax-not_po_action" );
                return true;
            }

            // $this->write_log( $_POST["action"], "mu_plugin-should_abort-doing_ajax-_POST" );

        }

        return false;
    }

    static function get_instance() {

        if( self::$instance == null ){
            self::$instance = new self();
        }

        return self::$instance;

    }


    function set_hooks() {

        add_filter( 'option_active_plugins', [ $this, 'filter_active_plugins_option_value' ], 5 );

        add_action( 'plugins_loaded',        [ $this, 'complete_action_once_plugins_are_loaded' ], 5 );

        add_action( 'shutdown',              [ $this, 'update_worklist_if_needed' ] );

    }

    function complete_action_once_plugins_are_loaded(){

        remove_filter('option_active_plugins', [ $this, 'filter_active_plugins_option_value' ], 5 );

        if( in_array( "plugin-optimizer-premium/plugin-optimizer-premium.php", $this->original_active_plugins ) ){

            $this->has_premium = true;
        }

        if( in_array( "plugin-optimizer-agent/plugin-optimizer-agent.php", $this->original_active_plugins ) ){

            $this->has_agent = true;
        }

    }


    function filter_active_plugins_option_value( $active_plugins ) {

        if( ! empty( $this->all_plugins ) ){
            return $active_plugins;
        }

        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        remove_filter('option_active_plugins', [ $this, 'filter_active_plugins_option_value' ], 5 );
        $this->all_plugins              = get_plugins();
        add_filter( 'option_active_plugins', [ $this, 'filter_active_plugins_option_value' ], 5 );

        $this->original_active_plugins  = $active_plugins;

        $active_plugins_on_menu_save = get_option( "active_plugins_on_menu_save" );

        if( $active_plugins_on_menu_save != $active_plugins ){

            // this will trigger the script that recreates the menu:
            update_option( "active_plugins_on_menu_save", $active_plugins );
            $_GET["po_original_menu"] = "get";

        }

        $this->plugins_to_block         = $this->get_plugins_to_block_for_current_url();

        $this->filtered_active_plugins  = array_diff( $this->original_active_plugins, $this->plugins_to_block );

        $this->blocked_plugins          = array_intersect( $this->original_active_plugins, $this->plugins_to_block );

        return $this->filtered_active_plugins;
    }

    function should_block_all( $url ) {

        if( strpos( $url, 'plugin-install.php?tab=plugin-information&plugin=' ) !== false ){
            return true;
        }

    }

    function should_skip_url( $url ) {

        $skip_for = [
            '/favicon.ico',
        ];

        if( in_array( $url, $skip_for ) ){
            return true;
        } elseif( strpos( $url, 'wp-content/plugins' ) !== false ){
            return true;
        } elseif( strpos( $url, 'wp-content/themes' ) !== false ){
            return true;
        } elseif( strpos( $url, '/wp-cron.php' ) !== false ){
            return true;
        } elseif( strpos( $url, '/wp-json/' ) !== false ){
            return true;
        }

        return false;
    }

    function get_plugins_to_block_for_current_url() {


        // On PO Ajax requests we are blocking all plugins, except PO
        if( wp_doing_ajax() && ! empty( $_POST["action"] ) && in_array( $_POST["action"], $this->po_ajax_actions ) ){
            // $this->write_log( "blocking all plugins!", "get_plugins_to_block_for_current_url-po_ajax-request" );
            $this->is_skipped = true;
            return array_diff( $this->original_active_plugins, $this->po_plugins );
        }

        // some URLs just need all plugins to get blocked
        if( $this->should_block_all( $this->current_wp_relative_url ) ){
            $this->is_skipped = true;
            return $this->original_active_plugins;
        }

        // some URLs just need to be skipped
        if( $this->should_skip_url( $this->current_wp_relative_url ) ){
            $this->is_skipped = true;
            return [];
        }

        // when we want to disable blocking on the current page, we use ?disable_po=yes on any page
        if( ! empty( $_GET["disable_po"] ) && $_GET["disable_po"] == "yes" ){
            $this->is_skipped = true;
            return [];
        }

        // when we are recreating the menu
        if( ! empty( $_GET["po_original_menu"] ) && $_GET["po_original_menu"] == "get" ){
            $this->is_skipped = true;
            return [];
        }

        $editing_post_type = $this->is_editing_post_type( $this->current_wp_relative_url );

        // --- are we on any of the PO pages?
        if(
            strpos( $this->current_wp_relative_url, "wp-admin/admin.php?page=plugin_optimizer") !== false ||
            in_array( $this->current_wp_relative_url, $this->po_pages ) ||
            in_array( $editing_post_type, $this->po_post_types )
        ){

            $this->is_po_default_page   = true;
            $this->is_being_filtered    = true;
            $this->plugins_to_block     = array_diff( $this->original_active_plugins, $this->po_plugins );

            return $this->plugins_to_block;
        }

        // --- Get plugins to block from all the filters

        $filters = get_posts([
            'post_type'   => 'plgnoptmzr_filter',
            'numberposts' => - 1,
        ]);

        foreach( $filters as $filter ){

            if( $filter->turned_off ){

                continue;
            }

            // If we're on the edit post screen, filter by post type

            if( $filter->filter_type !== '_endpoint' && $editing_post_type && $editing_post_type == $filter->filter_type ){

                $this->use_filter( $filter );

                continue;
            }

            // Filter by URL

            $endpoints = is_array( $filter->endpoints ) ? $filter->endpoints : [ $filter->endpoints ];

            if( in_array( $this->current_wp_relative_url, $endpoints ) ){

                $this->use_filter( $filter );

            } else {

                foreach( $endpoints as $endpoint ){

                    if( fnmatch( $endpoint, $this->current_wp_relative_url, FNM_PATHNAME | FNM_CASEFOLD ) ){

                        $this->use_filter( $filter );

                        break;
                    }

                }

            }

        }

        return array_unique( $this->plugins_to_block );
    }

    function use_filter( $filter ){

        $this->is_being_filtered = true;

        $plugins_to_block = ! empty( $filter->plugins_to_block ) ? array_keys( $filter->plugins_to_block ) : [];

        $this->plugins_to_block = array_merge( $this->plugins_to_block, $plugins_to_block );

        $this->filters_in_use[ $filter->ID ] = $filter->post_title;

        return $plugins_to_block;

    }


    function update_worklist_if_needed(){

        if( $this->is_skipped === false && $this->is_being_filtered === false && ! $this->is_po_default_page ){

            if( ! is_admin() ){


                // TODO we need to add endpoints to the Worklist here

            }

            // $this->write_log( ( is_admin() ? "Back end" : "Front end" ) . ": " . var_export( trim( $this->current_wp_relative_url ), true ), "update_worklist_if_needed-REQUEST_URI" );
        }

    }


    function is_editing_post_type( $url ){

        $post_id   = $this->url_to_postid( $url );
        $post_type = false;

        if( $post_id !== 0 && strpos( $url, "post.php" ) !== false && strpos( $url, "action=edit" ) !== false ){

            $post_type = get_post_type( $post_id );
        }

        return $post_type;

    }

    function url_to_postid( $url ){

        parse_str( parse_url( $url, PHP_URL_QUERY ), $query_vars);

        $post_id =                   ! empty( $query_vars["post"] )    ? $query_vars["post"]    : 0;
        $post_id = $post_id === 0 && ! empty( $query_vars["post_id"] ) ? $query_vars["post_id"] : $post_id;

        return $post_id;

    }

    function write_log( $log, $text = "write_log: ", $file_name = "debug.log" )  {

        $file = WP_CONTENT_DIR . '/' . $file_name;

        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( $text . PHP_EOL . print_r( $log, true ) . PHP_EOL, 3, $file );
        } else {
            error_log( $text . PHP_EOL . $log . PHP_EOL . PHP_EOL, 3, $file );
        }

    }

    function get_names_list( $array_name, $key = "Name" ){

        $list = [];

        foreach( $this->$array_name as $plugin_id ){

            if( ! empty( $this->all_plugins[ $plugin_id ] ) ){

                $list[ $plugin_id ] = $this->all_plugins[ $plugin_id ][ $key ];
            }

        }

        // $list = array_map( function( $plugin_id ) use ( $key ){

            // return $this->all_plugins[ $plugin_id ][ $key ];

        // }, $this->$array_name );

        natcasesort( $list );

        return $list;

    }

}

function sospo_mu_plugin(){
    return SOSPO_MU::get_instance();
}
sospo_mu_plugin();
