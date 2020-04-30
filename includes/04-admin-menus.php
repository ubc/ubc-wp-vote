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
	// Settings as submenu.
	add_submenu_page(
		'options-general.php',
		__( 'UBC WP Vote', 'ubc' ),
		__( 'UBC WP Vote', 'ubc' ),
		'manage_options', // for admin user only.
		'ubc_wp_vote_settings',
		__NAMESPACE__ . '\\render_settings_page'
	);
}//end register_admin_menus()

/**
 * Render php template for plugin settings page.
 *
 * @return void
 */
function render_settings_page() {
	require_once UBC_WP_VOTE_PLUGIN_DIR . 'includes/views/settings.php';
}//end render_settings_page()

