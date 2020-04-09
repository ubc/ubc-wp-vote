<?php
/**
 * This file will run during plugin deactivation.
 *
 * @package ubc_wp_vote
 */

namespace UBC\CTLT\WPVote\Deactivation;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Remove rubrics belongs to plugin that were created during plugin activation.
 *
 * @return void
 */
function remove_required_rubrics() {
	foreach ( UBC_WP_VOTE_DEFAULT_RUBRICS as $key => $rubric_title ) {
		$rubric_found = get_page_by_title( $rubric_title, 'OBJECT', 'ubc_wp_vote_rubric' );
		// If rubric is found, delete it.
		if ( $rubric_found ) {
			wp_delete_post( $rubric_found->ID );
		}
	}
}//end remove_required_rubrics()

remove_required_rubrics();
