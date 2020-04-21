<?php
/**
 * This file will run during plugin activation.
 *
 * @package ubc_wp_vote
 */

namespace UBC\CTLT\WPVote\Activation;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Create default rubrics that required by our plugin during plugin activation based on an array of rubric titles.
 * If rubric already exist and was drafted, publish it.
 *
 * @return void
 */
function generate_required_rubrics() {
	$object_types = \UBC\CTLT\WPVote\WP_Vote_Settings::get_default_rubrics();
	foreach ( $object_types as $key => $rubric_title ) {
		$rubric_found = get_page_by_title( $rubric_title, 'OBJECT', 'ubc_wp_vote_rubric' );
		// If rubric not found, create the rubric.
		// Slug will be generated based on title.
		if ( ! $rubric_found ) {
			wp_insert_post(
				array(
					'post_title'  => $rubric_title,
					'post_type'   => 'ubc_wp_vote_rubric',
					'post_status' => 'publish',
				)
			);
			// If rubric found but not published, publish the rubric.
		} elseif ( 'publish' !== $rubric_found->post_status ) {
			wp_update_post(
				array(
					'ID'          => $rubric_found->ID,
					'post_status' => 'publish',
				)
			);
		}
	}
}//end generate_required_rubrics()

// Update plugin version number as site option.
if ( ! add_site_option( 'ubc_wp_vote_db_id', UBC_WP_VOTE_VERSION ) ) {
	update_site_option( 'ubc_wp_vote_db_id', UBC_WP_VOTE_VERSION );
}

// Create required database tables if not already exist.
\UBC\CTLT\WPVote\WP_Vote_DB::create_tables();

// Create default rubrics required by plugin.
generate_required_rubrics();
