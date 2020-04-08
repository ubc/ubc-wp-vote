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

// Update plugin version number on options table.
update_option( 'ubc_wp_vote_db_id', UBC_WP_VOTE_VERSION );

// Create required database tables if not already exist.
\UBC\CTLT\WPVote\WP_Vote_DB::create_tables();
