<?php
/**
 * Plugin Initialization including assets registration for WordPress.
 *
 * @package ubc_wp_vote
 */

namespace UBC\CTLT\WPVote;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\load_styles_scripts' );
add_action( 'wp-hybrid-clf_after_entry', __NAMESPACE__ . '\\render_post_content_actions' );
add_action( 'wp-hybrid-clf_after_comment', __NAMESPACE__ . '\\render_comment_content_actions' );

/**
 * Enqueue application styles and scripts
 *
 * @return void
 */
function load_styles_scripts() {
	global $post;

	if ( ! is_singular() ) {
		return;
	}

	wp_enqueue_script(
		'ctlt_wp_vote_object_content_actions_js',
		UBC_WP_VOTE_PLUGIN_URL . 'src/js/object-content-actions.js',
		array( 'jquery' ),
		filemtime( UBC_WP_VOTE_PLUGIN_DIR . 'src/js/object-content-actions.js' ),
		true
	);
	wp_localize_script(
		'ctlt_wp_vote_object_content_actions_js',
		'ubc_ctlt_wp_vote',
		array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'ubc' ),
		)
	);

	wp_register_style(
		'ctlt_wp_vote_object_content_actions_css',
		UBC_WP_VOTE_PLUGIN_URL . 'src/css/object-content-actions.css',
		false,
		filemtime( UBC_WP_VOTE_PLUGIN_DIR . 'src/css/object-content-actions.css' )
	);
	wp_enqueue_style( 'ctlt_wp_vote_object_content_actions_css' );
}//end load_styles_scripts()

/**
 * Render rubric actions at the bottom of post content area.
 *
 * @return void
 */
function render_post_content_actions() {
	if ( ! is_singular() ) {
		return;
	}

	$object_type = get_post_type();
	$object_id   = get_the_ID();

	include UBC_WP_VOTE_PLUGIN_DIR . 'includes/views/object-content-actions.php';
}//end render_post_content_actions()

/**
 * Render rubric actions at the bottom of comment content area.
 *
 * @return void
 */
function render_comment_content_actions() {
	if ( ! is_singular() ) {
		return;
	}

	$object_type = 'comment';
	$object_id   = get_comment_ID();

	include UBC_WP_VOTE_PLUGIN_DIR . 'includes/views/object-content-actions.php';
}//end render_comment_content_actions()
