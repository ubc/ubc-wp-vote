<?php
/**
 * Register admin menus and submenus for the plugin.
 * Current pattern is to have only one top level menu 'UBC WP Vote' and all cpt and settings pages as its submenus.
 *
 * @package ubc_wp_vote
 */

namespace UBC\CTLT\WPVote;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

add_action( 'admin_menu', __NAMESPACE__ . '\\register_admin_menus' );

/**
 * Register admin menus
 *
 * @return void
 */
function register_admin_menus() {
	// Main Menu.
	add_menu_page(
		__( 'UBC WP Vote' ),
		__( 'UBC WP Vote' ),
		'edit_posts',
		'ubc_wp_vote',
		'',
		'dashicons-admin-site',
		2
	);

	// Settings as submenu.
	add_submenu_page(
		'ubc_wp_vote',
		__( 'Settings', 'ubc' ),
		__( 'Settings', 'ubc' ),
		'edit_posts',
		'ubc_wp_vote_settings',
		__NAMESPACE__ . '\\render_settings_page'
	);
}

/**
 * Render php template for plugin settings page.
 *
 * @return void
 */
function render_settings_page() {
	require_once UBC_WP_VOTE_PLUGIN_DIR . 'includes/views/settings.php';
}

