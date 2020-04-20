<?php
/**
 * WP_Vote class
 *
 * @package ubc_wp_vote
 * @since 0.0.1
 */

namespace UBC\CTLT\WPVote;

/**
 * WP_Vote class provides a series of functions to respond to action performed.
 *
 * @since 0.0.1
 */
class WP_Vote {

	/**
	 * Get current object total number of up vote.
	 *
	 * @param [array] $args array of args related to up vote.
	 * @return int
	 */
	public static function get_object_total_up_vote( $args ) {
		if ( ! isset( $args['object_type'] ) || ! isset( $args['object_id'] ) ) {
			return false;
		}
		$object_id   = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );
		$rubric      = get_page_by_title( 'Upvote', 'OBJECT', 'ubc_wp_vote_rubric' );
		if ( ! $rubric ) {
			return false;
		}
		$rubric_id      = intval( $rubric->ID );
		$meta_key_total = 'ubc_wp_vote_' . $rubric_id . '_total';

		return 'comment' === $object_type ? intval( get_comment_meta( $object_id, $meta_key_total, true ) ) : intval( get_post_meta( $object_id, $meta_key_total, true ) );
	}//end get_object_total_up_vote()

	/**
	 * Get current object up vote status for current user.
	 *
	 * @param [array] $args array of args related to up vote.
	 * @return int
	 */
	public static function get_object_current_user_up_vote( $args ) {
		if ( ! isset( $args['object_type'] ) || ! isset( $args['object_id'] ) ) {
			return false;
		}

		$user_id     = intval( get_current_user_id() );
		$site_id     = intval( get_current_blog_id() );
		$object_id   = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );
		$rubric      = get_page_by_title( 'Upvote', 'OBJECT', 'ubc_wp_vote_rubric' );
		if ( ! $rubric ) {
			return false;
		}
		$rubric_id      = intval( $rubric->ID );
		$meta_key_total = 'ubc_wp_vote_' . $rubric_id . '_total';

		$vote_data = \UBC\CTLT\WPVote\WP_Vote_DB::get_vote_meta(
			array(
				'user_id'     => $user_id,
				'site_id'     => $site_id,
				'rubric_id'   => $rubric_id,
				'object_id'   => $object_id,
				'object_type' => $object_type,
			)
		);

		return is_array( $vote_data ) && 1 === count( $vote_data ) ? intval( $vote_data[0]->vote_data ) : 0;
	}//end get_object_current_user_up_vote()

	/**
	 * Update current user vote status on current object.
	 *
	 * @since 0.0.1
	 * @param [array] $args array of args related to up vote.
	 * @return {boolean|array} array of mixd values on success, false on fail
	 */
	public static function do_current_user_up_vote( $args ) {
		if ( ! isset( $args['object_type'] ) || ! isset( $args['object_id'] ) ) {
			return false;
		}

		$user_id     = intval( get_current_user_id() );
		$site_id     = intval( get_current_blog_id() );
		$rubric      = get_page_by_title( 'Upvote', 'OBJECT', 'ubc_wp_vote_rubric' );
		$object_id   = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );

		if ( ! $rubric ) {
			return false;
		}

		$rubric_id = intval( $rubric->ID );
		return self::update_meta_type_toggle( $user_id, $site_id, $rubric_id, $object_id, $object_type );
	}//end do_current_user_up_vote()

	/**
	 * Get current object total number of down vote.
	 *
	 * @param [array] $args array of args related to up vote.
	 * @return int
	 */
	public static function get_object_total_down_vote( $args ) {
		if ( ! isset( $args['object_type'] ) || ! isset( $args['object_id'] ) ) {
			return false;
		}
		$object_id   = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );
		$rubric      = get_page_by_title( 'Downvote', 'OBJECT', 'ubc_wp_vote_rubric' );
		if ( ! $rubric ) {
			return false;
		}
		$rubric_id      = intval( $rubric->ID );
		$meta_key_total = 'ubc_wp_vote_' . $rubric_id . '_total';

		return 'comment' === $object_type ? intval( get_comment_meta( $object_id, $meta_key_total, true ) ) : intval( get_post_meta( $object_id, $meta_key_total, true ) );
	}//end get_object_total_down_vote()

	/**
	 * Get current object down vote status for current user.
	 *
	 * @param [array] $args array of args related to up vote.
	 * @return int
	 */
	public static function get_object_current_user_down_vote( $args ) {
		if ( ! isset( $args['object_type'] ) || ! isset( $args['object_id'] ) ) {
			return false;
		}

		$user_id     = intval( get_current_user_id() );
		$site_id     = intval( get_current_blog_id() );
		$object_id   = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );
		$rubric      = get_page_by_title( 'Downvote', 'OBJECT', 'ubc_wp_vote_rubric' );
		if ( ! $rubric ) {
			return false;
		}
		$rubric_id      = intval( $rubric->ID );
		$meta_key_total = 'ubc_wp_vote_' . $rubric_id . '_total';

		$vote_data = \UBC\CTLT\WPVote\WP_Vote_DB::get_vote_meta(
			array(
				'user_id'     => $user_id,
				'site_id'     => $site_id,
				'rubric_id'   => $rubric_id,
				'object_id'   => $object_id,
				'object_type' => $object_type,
			)
		);

		return is_array( $vote_data ) && 1 === count( $vote_data ) ? intval( $vote_data[0]->vote_data ) : 0;
	}//end get_object_current_user_down_vote()

	/**
	 * Update current user vote status on current object.
	 *
	 * @since 0.0.1
	 * @param [array] $args array of args related to down vote.
	 * @return {boolean|array} array of mixd values on success, false on fail
	 */
	public static function do_current_user_down_vote( $args ) {
		if ( ! isset( $args['object_type'] ) || ! isset( $args['object_id'] ) ) {
			return false;
		}

		$user_id     = intval( get_current_user_id() );
		$site_id     = intval( get_current_blog_id() );
		$rubric      = get_page_by_title( 'Downvote', 'OBJECT', 'ubc_wp_vote_rubric' );
		$object_id   = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );

		if ( ! $rubric ) {
			return false;
		}

		$rubric_id = intval( $rubric->ID );
		return self::update_meta_type_toggle( $user_id, $site_id, $rubric_id, $object_id, $object_type );
	}//end do_current_user_down_vote()

	/**
	 * Get current object overall average rating.
	 *
	 * @param [array] $args array of args related to up vote.
	 * @return float
	 */
	public static function get_object_rate_average( $args ) {
		if ( ! isset( $args['object_type'] ) || ! isset( $args['object_id'] ) ) {
			return false;
		}
		$object_id   = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );
		$rubric      = get_page_by_title( 'Rate', 'OBJECT', 'ubc_wp_vote_rubric' );
		if ( ! $rubric ) {
			return false;
		}
		$rubric_id        = intval( $rubric->ID );
		$meta_key_average = 'ubc_wp_vote_' . $rubric_id . '_average';

		return 'comment' === $object_type ? floatval( get_comment_meta( $object_id, $meta_key_average, true ) ) : floatval( get_post_meta( $object_id, $meta_key_average, true ) );
	}//end get_object_rate_average()

	/**
	 * Get object total count of rating.
	 *
	 * @param [array] $args array of args related to up vote.
	 * @return int
	 */
	public static function get_object_rate_count( $args ) {
		if ( ! isset( $args['object_type'] ) || ! isset( $args['object_id'] ) ) {
			return false;
		}
		$object_id   = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );
		$rubric      = get_page_by_title( 'Rate', 'OBJECT', 'ubc_wp_vote_rubric' );
		if ( ! $rubric ) {
			return false;
		}
		$rubric_id      = intval( $rubric->ID );
		$meta_key_count = 'ubc_wp_vote_' . $rubric_id . '_count';

		return 'comment' === $object_type ? intval( get_comment_meta( $object_id, $meta_key_count, true ) ) : intval( get_post_meta( $object_id, $meta_key_count, true ) );
	}//end get_object_rate_count()

	/**
	 * Get object rating by current user.
	 *
	 * @param [array] $args array of args related to up vote.
	 * @return float
	 */
	public static function get_object_current_user_rate( $args ) {
		if ( ! isset( $args['object_type'] ) || ! isset( $args['object_id'] ) ) {
			return false;
		}

		$user_id     = intval( get_current_user_id() );
		$site_id     = intval( get_current_blog_id() );
		$object_id   = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );
		$rubric      = get_page_by_title( 'Rate', 'OBJECT', 'ubc_wp_vote_rubric' );
		if ( ! $rubric ) {
			return false;
		}
		$rubric_id      = intval( $rubric->ID );
		$meta_key_total = 'ubc_wp_vote_' . $rubric_id . '_total';

		$vote_data = \UBC\CTLT\WPVote\WP_Vote_DB::get_vote_meta(
			array(
				'user_id'     => $user_id,
				'site_id'     => $site_id,
				'rubric_id'   => $rubric_id,
				'object_id'   => $object_id,
				'object_type' => $object_type,
			)
		);

		return is_array( $vote_data ) && 1 === count( $vote_data ) ? floatval( $vote_data[0]->vote_data ) : 0;
	}//end get_object_current_user_rate()

	/**
	 * Function to be run after rate actions received.
	 *
	 * @since 0.0.1
	 * @param [array] $args array of args related to rate.
	 * @return {boolean|array} array of mixd values on success, false on fail
	 */
	public static function do_current_user_rating( $args ) {
		if ( ! isset( $args['object_type'] ) || ! isset( $args['object_id'] ) || ! isset( $args['vote_data'] ) ) {
			return false;
		}

		$user_id     = intval( get_current_user_id() );
		$site_id     = intval( get_current_blog_id() );
		$rubric      = get_page_by_title( 'Rate', 'OBJECT', 'ubc_wp_vote_rubric' );
		$object_id   = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );
		$vote_data   = intval( $args['vote_data'] );

		if ( ! $rubric ) {
			return false;
		}

		$rubric_id = intval( $rubric->ID );
		return self::update_meta_type_average( $user_id, $site_id, $rubric_id, $object_id, $object_type, $vote_data );
	}//end do_current_user_rating()

	/**
	 * Generic function to provide functionality to update AVERAGE type of rubrics.
	 * When 'Vote Average' action is received, there are few things to be done:
	 * 1. insert this entry in wp_ubc_wp_vote global table
	 * 2. if object_type is 'post', update post_meta table for key ubc_wp_vote_{rate_rubric_id}_total and key ubc_wp_vote_{rate_rubric_id}_average
	 * 3. if object_type is 'comment', update comment_meta for key ubc_wp_vote_{rate_rubric_id}_total and key ubc_wp_vote_{rate_rubric_id}_average
	 *
	 * @param [number] $user_id ID of the user who performed the action.
	 * @param [number] $site_id ID of the site which action has been triggered on.
	 * @param [number] $rubric_id ID of the rubric to attached this action to.
	 * @param [number] $object_id ID of the object to attached metadata to.
	 * @param [string] $object_type type of the object( post or comment ).
	 * @param [string] $vote_data value of vote data.
	 * @return {boolean|array} array of mixd values on success, false on fail
	 */
	private static function update_meta_type_average( $user_id, $site_id, $rubric_id, $object_id, $object_type, $vote_data ) {
		$meta_key_count   = 'ubc_wp_vote_' . $rubric_id . '_count';
		$meta_key_total   = 'ubc_wp_vote_' . $rubric_id . '_total';
		$meta_key_average = 'ubc_wp_vote_' . $rubric_id . '_average';
		$vote_data        = intval( $vote_data );

		// Insert entry into global table.
		$existing_vote = \UBC\CTLT\WPVote\WP_Vote_DB::get_vote_meta(
			array(
				'user_id'     => $user_id,
				'site_id'     => $site_id,
				'rubric_id'   => $rubric_id,
				'object_id'   => $object_id,
				'object_type' => $object_type,
			)
		);

		$is_current_user_voted = false !== $existing_vote && 1 === count( $existing_vote );

		$update_global_meta = \UBC\CTLT\WPVote\WP_Vote_DB::update_vote_meta(
			array(
				'user_id'     => $user_id,
				'site_id'     => $site_id,
				'rubric_id'   => $rubric_id,
				'object_id'   => $object_id,
				'object_type' => $object_type,
				'vote_data'   => $vote_data,
			)
		);

		if ( false === $update_global_meta ) {
			return false;
		}

		// Insert to meta table based on object_type.
		$total_vote_count = intval(
			call_user_func(
				'comment' === $object_type ? 'get_comment_meta' : 'get_post_meta',
				$object_id,
				$meta_key_count,
				true
			)
		);
		$total_vote_total = intval(
			call_user_func(
				'comment' === $object_type ? 'get_comment_meta' : 'get_post_meta',
				$object_id,
				$meta_key_total,
				true
			)
		);

		// Add the metafields with default value if no one has voted before.
		if ( 0 === $total_vote_count ) {
			call_user_func(
				'comment' === $object_type ? 'update_comment_meta' : 'update_post_meta',
				$object_id,
				$meta_key_count,
				1
			);

			call_user_func(
				'comment' === $object_type ? 'update_comment_meta' : 'update_post_meta',
				$object_id,
				$meta_key_total,
				$vote_data
			);

			call_user_func(
				'comment' === $object_type ? 'update_comment_meta' : 'update_post_meta',
				$object_id,
				$meta_key_average,
				$vote_data
			);
		}
		// If metadata already exist and current user haven't rated for this object before.
		if ( 0 < $total_vote_count && ! $is_current_user_voted ) {
			call_user_func(
				'comment' === $object_type ? 'update_comment_meta' : 'update_post_meta',
				$object_id,
				$meta_key_count,
				$total_vote_count + 1
			);

			call_user_func(
				'comment' === $object_type ? 'update_comment_meta' : 'update_post_meta',
				$object_id,
				$meta_key_total,
				$total_vote_total + $vote_data
			);

			call_user_func(
				'comment' === $object_type ? 'update_comment_meta' : 'update_post_meta',
				$object_id,
				$meta_key_average,
				\UBC\CTLT\WPVote\Helpers\round_to_half_integer( ( $total_vote_total + $vote_data ) / ( $total_vote_count + 1 ) )
			);
		}

		if ( 0 < $total_vote_count && $is_current_user_voted ) {
			$existing_vote_data = $existing_vote[0]->vote_data;

			if ( intval( $existing_vote_data ) === intval( $vote_data ) ) {
				return true;
			}

			call_user_func(
				'comment' === $object_type ? 'update_comment_meta' : 'update_post_meta',
				$object_id,
				$meta_key_total,
				$total_vote_total + $vote_data - $existing_vote_data
			);

			call_user_func(
				'comment' === $object_type ? 'update_comment_meta' : 'update_post_meta',
				$object_id,
				$meta_key_average,
				\UBC\CTLT\WPVote\Helpers\round_to_half_integer( ( $total_vote_total + $vote_data - $existing_vote_data ) / $total_vote_count )
			);
		}

		return true;
	}//end update_meta_type_average()

	/**
	 * When 'Vote Total' action is received, there are few things to be done:
	 * 1. insert this entry in wp_ubc_wp_vote global table
	 * 2. if object_type is 'post', update post_meta table for key ubc_wp_vote_{up_vote_rubric_id}_total
	 * 3. if object_type is 'comment', update comment_meta for key ubc_wp_vote_{up_vote_rubric_id}_total
	 *
	 * @param [number] $user_id ID of the user who performed the action.
	 * @param [number] $site_id ID of the site which action has been triggered on.
	 * @param [number] $rubric_id ID of the rubric to attached this action to.
	 * @param [number] $object_id ID of the object to attached metadata to.
	 * @param [string] $object_type type of the object( post or comment ).
	 * @return {boolean} True on success, false on fail
	 */
	private static function update_meta_type_toggle( $user_id, $site_id, $rubric_id, $object_id, $object_type ) {
		$meta_key = 'ubc_wp_vote_' . $rubric_id . '_total';

		// Toggle the value found in the database.
		$vote_data = \UBC\CTLT\WPVote\WP_Vote_DB::get_vote_meta(
			array(
				'user_id'     => $user_id,
				'site_id'     => $site_id,
				'rubric_id'   => $rubric_id,
				'object_id'   => $object_id,
				'object_type' => $object_type,
			)
		);

		// Insert to meta table based on object_type.
		$total_vote = call_user_func( 'comment' === $object_type ? 'get_comment_meta' : 'get_post_meta', $object_id, $meta_key, true );
		$vote_data  = is_array( $vote_data ) && 1 === count( $vote_data ) && '1' === $vote_data[0]->vote_data ? '1' : '0';

		// Otherwise.
		call_user_func(
			'comment' === $object_type ? 'update_comment_meta' : 'update_post_meta',
			$object_id,
			$meta_key,
			'1' === $vote_data ? intval( $total_vote ) - 1 : intval( $total_vote ) + 1
		);

		\UBC\CTLT\WPVote\WP_Vote_DB::update_vote_meta(
			array(
				'user_id'     => $user_id,
				'site_id'     => $site_id,
				'rubric_id'   => $rubric_id,
				'object_id'   => $object_id,
				'object_type' => $object_type,
				'vote_data'   => '1' === $vote_data ? '0' : '1',
			)
		);

		return true;
	}//end update_meta_type_toggle()
}
