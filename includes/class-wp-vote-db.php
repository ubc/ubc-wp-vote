<?php
/**
 * WP_Vote_DB class
 *
 * @package ubc_wp_vote
 * @since 0.0.1
 */

namespace UBC\CTLT\WPVote;

/**
 * Database wrapper class for UBC WP Vote plugin.
 * This class talks directly to WordPress database and provide API for other logic layer.
 *
 * @since 0.0.1
 */
class WP_Vote_DB {

	/**
	 * Create global database tables that required by this plugin
	 *
	 * @since 0.0.1
	 */
	public static function create_tables() {
		global $wpdb;
		$charset_collate = sanitize_text_field( $wpdb->get_charset_collate() );
		$table_name = sanitize_key( $wpdb->base_prefix . 'ubc_wp_vote' );

		$sql = "CREATE TABLE IF NOT EXISTS $table_name ( id INT NOT NULL AUTO_INCREMENT, user_id INT NOT NULL, site_id INT NOT NULL, object_id INT NOT NULL, object_type VARCHAR(10) NOT NULL, rubric_id INT NOT NULL, vote_data LONGTEXT NOT NULL, UNIQUE KEY id (id) ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( esc_sql( $sql ) );
	}//end create_tables()
}
