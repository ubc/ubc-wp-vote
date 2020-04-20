<?php
/**
 * WP_Vote_Settings class
 *
 * @package ubc_wp_vote
 * @since 0.0.1
 */

namespace UBC\CTLT\WPVote;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Plugin settings
 *
 * @since 0.0.1
 */
class WP_Vote_Settings {
	/**
	 * $default_rubrics.
	 *
	 * @since 0.0.1
	 * @var array $var Default rubrics required by plugin.
	 */
	private static $default_rubrics;

	/**
	 * $post_types_to_exclude.
	 *
	 * @since 0.0.1
	 * @var array $var Post types to be excluded to activate plugin functionality.
	 */
	private static $post_types_to_exclude;

	/**
	 * Initiate class.
	 *
	 * @return void
	 */
	public static function init() {
		self::$default_rubrics       = array( 'Upvote', 'Downvote', 'Rate' );
		self::$post_types_to_exclude = array( 'attachment', 'ubc_wp_vote_rubric' );

		add_action( 'admin_init', '\UBC\CTLT\WPVote\WP_Vote_Settings::register_admin_settings' );
	}//end init()

	/**
	 * Register plugin settings.
	 *
	 * @return void
	 */
	public static function register_admin_settings() {
		register_setting( 'ubc_wp_vote', 'ubc_wp_vote_valid_post_types' );
	}//end register_admin_settings()

	/**
	 * Get a list of default rubrics required by plugin.
	 *
	 * @return array
	 */
	public static function get_default_rubrics() {
		return self::$default_rubrics;
	}

	/**
	 * Get all object types available for user to choose.
	 *
	 * @return array
	 */
	public static function get_object_types_options() {
		// Get all public post types.
		$object_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		// Exclude post types defined in plugin file.
		$object_types = array_filter(
			$object_types,
			function( $post_type ) {
				return ! in_array( $post_type->name, self::$post_types_to_exclude, true );
			}
		);

		// Transform each post type object to standard object which only include label and name.
		$object_types = array_map(
			function( $post_type ) {
				$formated        = new \stdClass();
				$formated->label = $post_type->label;
				$formated->name  = $post_type->name;
				return $formated;
			},
			$object_types
		);

		// As comment as an option.
		$comment                  = new \stdClass();
		$comment->label           = 'Comments';
		$object_types['comments'] = $comment;

		\UBC\CTLT\WPVote\Helpers\write_log( $object_types );

		return $object_types;
	}
}
