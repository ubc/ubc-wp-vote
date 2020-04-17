<?php
/**
 * Register plugin settings
 *
 * @package ubc_wp_vote
 */

namespace UBC\CTLT\WPVote;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Hooks.
add_action( 'admin_init', __NAMESPACE__ . '\\register_admin_settings' );

/**
 * Register plugin settings.
 *
 * @return void
 */
function register_admin_settings() {
	register_setting( 'ubc_wp_vote', 'ubc_wp_vote_valid_post_types' );
}//end register_admin_settings()
