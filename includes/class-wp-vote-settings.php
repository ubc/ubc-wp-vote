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
		self::$default_rubrics       = array(
			array(
				'label'       => __( 'Upvote' ),
				'name'        => 'upvote',
				'description' => __( 'Provide ability to upvote a post or comment' ),
			),
			array(
				'label'       => __( 'Downvote' ),
				'name'        => 'downvote',
				'description' => __( 'Provide ability to downvote a post or comment' ),
			),
			array(
				'label'       => __( 'Rating' ),
				'name'        => 'rating',
				'description' => __( 'A 1-5 star rating system, which shows average rating.' ),
			),
		);
		self::$post_types_to_exclude = array( 'attachment', 'ubc_wp_vote_rubric' );

		if ( is_admin() ) {
			add_action( 'admin_init', '\UBC\CTLT\WPVote\WP_Vote_Settings::register_admin_settings' );
			add_action( 'add_meta_boxes', '\UBC\CTLT\WPVote\WP_Vote_Settings::add_meta_boxes' );
			add_action( 'save_post', '\UBC\CTLT\WPVote\WP_Vote_Settings::save_meta_boxes' );
		}
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
	 * Add metaboxes to post edit screen
	 *
	 * @return void
	 */
	public static function add_meta_boxes() {
		global $post;
		$post_types = self::get_object_types_options();

		// Quit if post type is not submission.
		if ( ! $post || ! in_array( $post->post_type, array_keys( $post_types ), true ) ) {
			return;
		}

		add_meta_box(
			'ubc_wp_vote_valid_post_type',
			'Rubrics',
			function() {
				// Post metafields.
				$option_name  = 'ubc-wp-vote-settings-';
				$object_title = 'POST';
				include UBC_WP_VOTE_PLUGIN_DIR . 'includes/views/object-rubrics-settings-template.php';

				// Comment metafields.
				$option_name  = 'ubc-wp-vote-settings-comment-';
				$object_title = 'COMMENT';
				include UBC_WP_VOTE_PLUGIN_DIR . 'includes/views/object-rubrics-settings-template.php';
			},
			null,
			'side'
		);
	}//end add_meta_boxes()

	/**
	 * Save metadata from post edit screen
	 *
	 * @return void
	 */
	public static function save_meta_boxes() {
		global $post;

		if ( ! isset( $post->ID ) || 1 !== check_admin_referer( 'ubc_wp_vote', 'ubc_wp_vote_rubric_metabox' ) ) {
			return;
		}

		if ( isset( $_POST['ubc-wp-vote-settings-rubrics'] ) ) {
			// Sanitizing array.
			$rubrics         = array_map(
				function( $rubric ) {
					return sanitize_key( $rubric );
				},
				$_POST['ubc-wp-vote-settings-rubrics']
			);

			update_post_meta( $post->ID, 'ubc-wp-vote-settings-rubrics', $rubrics );
		} else {
			update_post_meta( $post->ID, 'ubc-wp-vote-settings-rubrics', array() );
		}

		if ( isset( $_POST['ubc-wp-vote-settings-comment-rubrics'] ) ) {
			// Sanitizing array.
			$rubrics_comment = array_map(
				function( $rubric ) {
					return sanitize_key( $rubric );
				},
				$_POST['ubc-wp-vote-settings-comment-rubrics']
			);

			update_post_meta( $post->ID, 'ubc-wp-vote-settings-comment-rubrics', $rubrics_comment );
		} else {
			update_post_meta( $post->ID, 'ubc-wp-vote-settings-comment-rubrics', array() );
		}
	}//end save_meta_boxes()

	/**
	 * Get a list of default rubrics required by plugin.
	 *
	 * @return array
	 */
	public static function get_default_rubrics() {
		return self::$default_rubrics;
	}//end get_default_rubrics()

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
				return $formated;
			},
			$object_types
		);

		// As comment as an option.
		$comment                 = new \stdClass();
		$comment->label          = 'Comment';
		$object_types['comment'] = $comment;

		return $object_types;
	}//end get_object_types_options()

	/**
	 * Get a list of public available rubrics.
	 *
	 * @return array
	 */
	public static function get_rubrics_options() {
		$args    = array(
			'numberposts' => apply_filters( 'ubc_wp_vote_get_rubrics_options_numberposts', 30 ),
			'post_type'   => 'ubc_wp_vote_rubric',
			'post_status' => 'publish',
		);
		$rubrics = get_posts( $args );

		return array_map(
			function( $rubric ) {
				$formated              = new \stdClass();
				$formated->label       = $rubric->post_title;
				$formated->name        = $rubric->post_name;
				$formated->description = $rubric->post_content;
				return $formated;
			},
			$rubrics
		);
	}//end get_rubrics_options()

	/**
	 * Check if a object has specific rubric turned on.
	 *
	 * @param [string]  $rubric_name rubric to check if post has turned on.
	 * @param [number]  $post ID of post to check. default is 0.
	 * @param [boolean] $is_comment is the object_type 'comment', default is false.
	 * @return boolean
	 */
	public static function is_object_rubric_valid( $rubric_name, $post = 0, $is_comment = false ) {
		$post = get_post( $post );

		if ( ! $post->ID ) {
			return false;
		}

		$id        = intval( $post->ID );
		$post_type = sanitize_key( $post->post_type );

		// If the settings exist locally.
		$rubrics_meta_key = $is_comment ? 'ubc-wp-vote-settings-comment-rubrics' : 'ubc-wp-vote-settings-rubrics';

		if ( metadata_exists( $is_comment ? 'comment' : 'post', intval( $id ), $rubrics_meta_key ) ) {
			$active_rubrics = get_post_meta( intval( $id ), sanitize_key( $rubrics_meta_key ), true );
			$active_rubrics = is_array( $active_rubrics ) ? $active_rubrics : array();
			return in_array( $rubric_name, $active_rubrics, true );
		}

		// If settings does not exist locally, fetch global settings.
		$global_active_rubrics = get_option( 'ubc_wp_vote_valid_post_types' );
		if ( ! $global_active_rubrics || ! isset( $global_active_rubrics[ $rubric_name ] ) ) {
			return false;
		}

		return $is_comment ? in_array( 'comment', $global_active_rubrics[ $rubric_name ], true ) : in_array( $post_type, $global_active_rubrics[ $rubric_name ], true );
	}//end is_object_rubric_valid()
}
