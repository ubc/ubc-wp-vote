<?php
/**
 * Register custom post types and custom taxonomies
 *
 * @package ubc_wp_vote
 */

namespace UBC\CTLT\WPVote;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}


add_action( 'init', __NAMESPACE__ . '\\register_post_types' );

/**
 * Register custom post types
 *
 * @return void
 */
function register_post_types() {
	register_post_type(
		'ubc_wp_vote_rubric',
		array(
			'labels'       => array(
				'name'          => __( 'Rubrics' ),
				'singular_name' => __( 'Rubric' ),
			),
			'public' => true,
			'show_in_menu' => 'ubc_wp_vote',
		)
	);
}//end register_post_types()
