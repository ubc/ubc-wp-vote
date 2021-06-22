<?php
/**
 * Plugin Name:       UBC WP Vote
 * Plugin URI:        https://github.com/ubc/ubc-wp-vote
 * Description:       The plugin provide mechanism for login user to like, dislike and rate posts and post comments.
 * Version:           0.0.1
 * Author:            Richard Tape, Kelvin Xu and Dhaneshwari Patel
 * Author URI:         https://ctlt.ubc.ca/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ubc-wp-vote
 *
 * @package ubc_wp_vote
 */

namespace UBC\CTLT\WPVote;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

define( 'UBC_WP_VOTE_VERSION', '0.0.2' );
define( 'UBC_WP_VOTE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'UBC_WP_VOTE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate' );

require_once UBC_WP_VOTE_PLUGIN_DIR . 'includes/class-wp-vote-db.php';
require_once UBC_WP_VOTE_PLUGIN_DIR . 'includes/class-wp-vote.php';
require_once UBC_WP_VOTE_PLUGIN_DIR . 'includes/class-wp-vote-settings.php';

WP_Vote_Settings::init();

require_once UBC_WP_VOTE_PLUGIN_DIR . 'includes/02-cpt-tax.php';
require_once UBC_WP_VOTE_PLUGIN_DIR . 'includes/03-init.php';
require_once UBC_WP_VOTE_PLUGIN_DIR . 'includes/06-ajax.php';
require_once UBC_WP_VOTE_PLUGIN_DIR . 'includes/helper.php';
require_once UBC_WP_VOTE_PLUGIN_DIR . 'includes/05-admin-columns.php';

if ( is_admin() ) {
	require_once UBC_WP_VOTE_PLUGIN_DIR . 'includes/04-admin-menus.php';
}

/**
 * Actions performs on plugin activation
 */
function activate() {
	require_once UBC_WP_VOTE_PLUGIN_DIR . 'includes/01-activation.php';
}//end activate()

/**
 * Actions performs on plugin deactivation
 */
function deactivate() {
	require_once UBC_WP_VOTE_PLUGIN_DIR . 'includes/01-deactivation.php';
}//end deactivate()
