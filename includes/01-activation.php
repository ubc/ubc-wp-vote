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

\UBC\CTLT\WPVote\WP_Vote_DB::create_tables();
