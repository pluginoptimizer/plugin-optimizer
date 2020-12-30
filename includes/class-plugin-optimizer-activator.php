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
	 * Activate plugin
	 *
	 * By inserting our file into a folder mu-plugins
	 *
	 */
	public static function activate() {
		copy(__DIR__ . '/class-plugin-optimizer-mu.php', WPMU_PLUGIN_DIR.'/class-plugin-optimizer-mu.php' );
		self::create_table();
	}

	/**
	 * Activate mu-plugin
	 *
	 * By inserting our file into a folder mu-plugins
	 *
	 */
	public static function create_table() {
		global $wpdb;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$table_name = $wpdb->get_blog_prefix() . 'filter_optimize';
		$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

		$sql = "CREATE TABLE {$table_name} (
	    id  bigint(20) unsigned NOT NULL auto_increment,
	    audit varchar(20) NOT NULL default '',
	    name_filter longtext NOT NULL default '',
	    type_filter longtext NOT NULL default '',
	    permalinks_filter longtext NOT NULL default '',
	    plugins_name_filter longtext NOT NULL default '',
	    plugins_link_filter longtext NOT NULL default '',
	    groups_filter longtext NOT NULL default '',
	    categories_filter longtext NOT NULL default '',
	    PRIMARY KEY  (id),
		KEY audit (audit)
		)
		{$charset_collate};";

		dbDelta($sql);
	}

}
