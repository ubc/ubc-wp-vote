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

	/**
	 * Update vote meta based on args provided, insert new record in database if not exist.
	 *
	 * @param [array] $args array of args related to vote meta including user_id, site_id, object_id, rubric_id and object_type.
	 * @return {boolean} True on success, false on fail
	 */
	public static function update_vote_meta( $args ) {
		if ( ! $args || ! isset( $args['user_id'] ) || ! isset( $args['site_id'] ) || ! isset( $args['object_id'] ) || ! isset( $args['rubric_id'] ) || ! isset( $args['vote_data'] ) ) {
			return false;
		}

		global $wpdb;

		$table_name = sanitize_key( $wpdb->base_prefix . 'ubc_wp_vote' );
		$user_id = intval( $args['user_id'] );
		$site_id = intval( $args['site_id'] );
		$object_id = intval( $args['object_id'] );
		$rubric_id = intval( $args['rubric_id'] );
		$object_type = $args['object_type'] ? sanitize_key( $args['object_type'] ) : 'post';
		$vote_data = sanitize_meta( 'vote_data', $args['vote_data'], $object_type );

		$meta = self::get_vote_meta(
			array(
				'user_id' => $user_id,
				'site_id' => $site_id,
				'object_id' => $object_id,
				'rubric_id' => $rubric_id,
				'object_type' => $object_type,
			)
		);

		if ( false !== $meta ) {
			$result = $wpdb->query(
				$wpdb->prepare(
					"UPDATE $table_name SET vote_data = %s WHERE user_id = %d AND site_id = %d AND object_id = %d AND rubric_id = %d AND object_type = %s",
					$vote_data,
					$user_id,
					$site_id,
					$object_id,
					$rubric_id,
					$object_type
				)
			);
		} else {
			$result = $wpdb->query(
				$wpdb->prepare(
					"INSERT INTO $table_name (user_id, site_id, object_id, rubric_id, object_type, vote_data) VALUES (%d, %d, %d, %d, %s, %s)",
					$user_id,
					$site_id,
					$object_id,
					$rubric_id,
					$object_type,
					$vote_data
				)
			);
		}

		return false === $result ? false : true;
	}

	/**
	 * Retrieve vote data based on filters provided.
	 *
	 * @param [array] $args array of args provided to filter out the result. Available args are user_id, site_id, object_id, rubric_id and object_type.
	 * @return {boolean|array} Return FALSE if no results has been found, return array of objects if one or more results has been found.
	 */
	public static function get_vote_meta( $args ) {
		global $wpdb;

		$table_name = sanitize_key( $wpdb->base_prefix . 'ubc_wp_vote' );
		$query_param = array();

		if ( isset( $args['user_id'] ) ) {
			$query_param[] = 'user_id = ' . intval( $args['user_id'] );
		}

		if ( isset( $args['site_id'] ) ) {
			$query_param[] = 'site_id = ' . intval( $args['site_id'] );
		}

		if ( isset( $args['object_id'] ) ) {
			$query_param[] = 'object_id = ' . intval( $args['object_id'] );
		}

		if ( isset( $args['rubric_id'] ) ) {
			$query_param[] = 'rubric_id = ' . intval( $args['rubric_id'] );
		}

		if ( isset( $args['object_type'] ) ) {
			$query_param[] = 'object_type = "' . sanitize_key( $args['object_type'] ) . '"';
		}

		if ( 0 !== count( $query_param ) ) {
			$result = $wpdb->get_results( "SELECT vote_data from $table_name WHERE " . join( ' AND ', $query_param ) );
		} else {
			$result = $wpdb->get_results( "SELECT vote_data from $table_name" );
		}

		if ( 0 === count( $result ) ) {
			return false;
		}

		return $result;
	}//end get_vote_meta()
}
