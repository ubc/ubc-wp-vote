<?php
/**
 * WordPress ajax handlers
 *
 * @package ubc_wp_vote
 */

namespace UBC\CTLT\WPVote\Ajax;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Hooks.
add_action( 'wp_ajax_ubc_ctlt_wp_vote_upvote', __NAMESPACE__ . '\\ubc_ctlt_wp_vote_upvote' );
add_action( 'wp_ajax_ubc_ctlt_wp_vote_downvote', __NAMESPACE__ . '\\ubc_ctlt_wp_vote_downvote' );\
add_action( 'wp_ajax_ubc_ctlt_wp_vote_rating', __NAMESPACE__ . '\\ubc_ctlt_wp_vote_rating' );

/**
 * Ajax handler for upvote toggle action.
 *
 * @return void
 */
function ubc_ctlt_wp_vote_upvote() {
	check_ajax_referer( 'ubc', 'security' );

	$object_id = isset( $_POST['object_id'] ) ? intval( $_POST['object_id'] ) : null;
	$object_type = isset( $_POST['object_type'] ) ? sanitize_text_field( wp_unslash( $_POST['object_type'] ) ) : null;

	$success = \UBC\CTLT\WPVote\WP_Vote::do_current_user_up_vote(
		array(
			'object_id' => $object_id,
			'object_type' => $object_type,
		)
	);

	if ( false === $success ) {
		wp_die( esc_html( __( 'System Error' ) ), 'Error', array( 'response' => 400 ) );
	}

	wp_die();
}

/**
 * Ajax handler for downvote toggle action.
 *
 * @return void
 */
function ubc_ctlt_wp_vote_downvote() {
	check_ajax_referer( 'ubc', 'security' );

	$object_id = isset( $_POST['object_id'] ) ? intval( $_POST['object_id'] ) : null;
	$object_type = isset( $_POST['object_type'] ) ? sanitize_text_field( wp_unslash( $_POST['object_type'] ) ) : null;

	$success = \UBC\CTLT\WPVote\WP_Vote::do_current_user_down_vote(
		array(
			'object_id' => $object_id,
			'object_type' => $object_type,
		)
	);

	if ( false === $success ) {
		wp_die( esc_html( __( 'System Error' ) ), 'Error', array( 'response' => 400 ) );
	}

	wp_die();
}

/**
 * Ajax handler for rating action.
 *
 * @return void
 */
function ubc_ctlt_wp_vote_rating() {
	check_ajax_referer( 'ubc', 'security' );

	$object_id = isset( $_POST['object_id'] ) ? intval( $_POST['object_id'] ) : null;
	$object_type = isset( $_POST['object_type'] ) ? sanitize_text_field( wp_unslash( $_POST['object_type'] ) ) : null;
	$vote_data = isset( $_POST['vote_data'] ) ? intval( $_POST['vote_data'] ) : null;

	$success = \UBC\CTLT\WPVote\WP_Vote::do_current_user_rating(
		array(
			'object_id' => $object_id,
			'object_type' => $object_type,
			'vote_data' => $vote_data,
		)
	);

	if ( false === $success ) {
		wp_die( esc_html( __( 'System Error' ) ), 'Error', array( 'response' => 400 ) );
	}

	$args = array(
		'rating' => $vote_data,
		'type' => 'rating',
	);
	wp_star_rating( $args );
	wp_die();
}
