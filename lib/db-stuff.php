<?php

namespace jn;

if ( ! defined( '\\ABSPATH' ) ) {
	exit;
}

function db() {
	global $wpdb;
	return $wpdb;
}

/**
 * Used by the main plugin file to register a hook
 * for the activation process.
 * It will create a few tables needed for janitorial matters
 *
 * @param  string $plugin_file Nothing super useful. the plugin path
 * @return [type]              [description]
 */
function create_tables( $plugin_file ) {
	register_activation_hook( $plugin_file, 'jn\jurassic_ninja_create_table' );
}


function jurassic_ninja_create_table() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE sites (
		`id` INT NOT NULL AUTO_INCREMENT,
		username text not null,
		domain text not null,
		created datetime ,
		last_logged_in datetime ,
		checked_in datetime,
		shortlived boolean not null DEFAULT 0,
		PRIMARY KEY  (id)
	) $charset_collate;";

	$sql2 = "CREATE TABLE purged (
		`id` INT NOT NULL AUTO_INCREMENT,
		username text not null,
		domain text not null,
		created datetime ,
		last_logged_in datetime ,
		checked_in datetime,
		shortlived boolean not null DEFAULT 0,
		PRIMARY KEY  (id)
	) $charset_collate;";

	if ( ! function_exists( 'dbDelta' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	}

	try {
		@dbDelta( $sql );
		@dbDelta( $sql2 );

	} catch ( \Exception $e ) {
		push_error( new \WP_Error( 'error_creating_administartive_tables' ) );
	}
}
