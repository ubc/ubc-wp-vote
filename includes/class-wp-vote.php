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
	 * Get object total number of up vote.
	 *
	 * @param [array] $args array of args related to up vote.
	 * @return boolean|string
	 */
	public static function get_up_vote( $args ) {
		if ( ! $args || ! isset( $args['object_type'] ) || isset( $args['object_id'] ) ) {
			return false;
		}
		$object_id = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );
		$rubric = get_page_by_title( 'Upvote', 'OBJECT', 'ubc_wp_vote_rubric' );
		if ( ! $rubric ) {
			return false;
		}
		$rubric_id = intval( $rubric->ID );
		$meta_key_total = 'ubc_wp_vote_' . $rubic_id . '_total';

		return 'comment' === $object_type ? get_comment_meta( $object_id, $meta_key_total, true ) : get_post_meta( $object_id, $meta_key_total, true );
	}

	/**
	 * Get object total number of down vote.
	 *
	 * @param [array] $args array of args related to up vote.
	 * @return boolean|string
	 */
	public static function get_down_vote( $args ) {
		if ( ! $args || ! isset( $args['object_type'] ) || isset( $args['object_id'] ) ) {
			return false;
		}
		$object_id = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );
		$rubric = get_page_by_title( 'Downvote', 'OBJECT', 'ubc_wp_vote_rubric' );
		if ( ! $rubric ) {
			return false;
		}
		$rubric_id = intval( $rubric->ID );
		$meta_key_total = 'ubc_wp_vote_' . $rubic_id . '_total';

		return 'comment' === $object_type ? get_comment_meta( $object_id, $meta_key_total, true ) : get_post_meta( $object_id, $meta_key_total, true );
	}

	/**
	 * Get object total count of rate and average rate.
	 *
	 * @param [array] $args array of args related to up vote.
	 * @return boolean|object
	 */
	public static function get_rate( $args ) {
		if ( ! $args || ! isset( $args['object_type'] ) || isset( $args['object_id'] ) ) {
			return false;
		}
		$object_id = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );
		$rubric = get_page_by_title( 'Rate', 'OBJECT', 'ubc_wp_vote_rubric' );
		if ( ! $rubric ) {
			return false;
		}
		$rubric_id = intval( $rubric->ID );
		$meta_key_total = 'ubc_wp_vote_' . $rubic_id . '_total';
		$meta_key_average = 'ubc_wp_vote_' . $rubic_id . '_average';

		return 'comment' === $object_type ?
			array(
				'total' => get_comment_meta( $object_id, $meta_key_total, true ),
				'average' => get_comment_meta( $object_id, $meta_key_average, true ),
			) :
			array(
				'total' => get_post_meta( $object_id, $meta_key_total, true ),
				'average' => get_post_meta( $object_id, $meta_key_average, true ),
			);
	}

	/**
	 * Function to be run after up vote actions received.
	 *
	 * @since 0.0.1
	 * @param [array] $args array of args related to up vote.
	 * @return {boolean} True on success, false on fail
	 */
	public static function do_up_vote( $args ) {
		if ( ! $args || ! isset( $args['object_type'] ) || isset( $args['object_id'] ) ) {
			return false;
		}

		$user_id = intval( get_current_user_id() );
		$site_id = intval( get_current_blog_id() );
		$rubric = get_page_by_title( 'Upvote', 'OBJECT', 'ubc_wp_vote_rubric' );
		$object_id = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );

		if ( ! $rubric ) {
			return false;
		}
		$rubric_id = intval( $rubric->ID );
		self::update_meta_total( $user_id, $site_id, $rubric_id, $object_id, $object_type );
	}//end do_up_vote()

	/**
	 * Function to be run after down vote actions received.
	 *
	 * @since 0.0.1
	 * @param [array] $args array of args related to down vote.
	 * @return {boolean} True on success, false on fail
	 */
	public static function do_down_vote( $args ) {
		if ( ! $args || ! isset( $args['object_type'] ) || isset( $args['object_id'] ) ) {
			return false;
		}

		$user_id = intval( get_current_user_id() );
		$site_id = intval( get_current_blog_id() );
		$rubric = get_page_by_title( 'Downvote', 'OBJECT', 'ubc_wp_vote_rubric' );
		$object_id = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );

		if ( ! $rubric ) {
			return false;
		}

		$rubric_id = intval( $rubric->ID );
		self::update_meta_total( $user_id, $site_id, $rubric_id, $object_id, $object_type );
	}//end do_down_vote()

	/**
	 * Function to be run after rate actions received.
	 *
	 * @since 0.0.1
	 * @param [array] $args array of args related to rate.
	 * @return {boolean} True on success, false on fail
	 */
	public static function do_rate( $args ) {
		if ( ! $args || ! isset( $args['object_type'] ) || isset( $args['object_id'] ) || isset( $args['vote_data'] ) ) {
			return false;
		}

		$user_id = intval( get_current_user_id() );
		$site_id = intval( get_current_blog_id() );
		$rubric = get_page_by_title( 'Downvote', 'OBJECT', 'ubc_wp_vote_rubric' );
		$object_id = intval( $args['object_id'] );
		$object_type = sanitize_key( $args['object_type'] );
		$vote_data = sanitize_meta( $args['vote_data'] );

		if ( ! $rubric ) {
			return false;
		}

		$rubric_id = intval( $rubric->ID );
		self::update_meta_average( $user_id, $site_id, $rubric_id, $object_id, $object_type, $vote_data );
	}//end do_rate()

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
	 * @return {boolean} True on success, false on fail
	 */
	private static function update_meta_average( $user_id, $site_id, $rubric_id, $object_id, $object_type, $vote_data ) {
		$meta_key_total = 'ubc_wp_vote_' . $rubic_id . '_total';
		$meta_key_average = 'ubc_wp_vote_' . $rubic_id . '_average';
		$vote_data = intval( $vote_data );

		// Insert entry into global table.
		$existing_vote_data = \UBC\CTLT\WPVote\WP_Vote_DB::get_vote_meta(
			array(
				'user_id' => $user_id,
				'site_id' => $site_id,
				'rubric_id' => $rubric_id,
				'object_id' => $object_id,
				'object_type' => $object_type,
			)
		);

		// Break if provided vote data is the same with existing one.
		if ( $existing_vote_data === $vote_data ) {
			return false;
		}

		\UBC\CTLT\WPVote\WP_Vote_DB::update_vote_meta(
			array(
				'user_id' => $user_id,
				'site_id' => $site_id,
				'rubric_id' => $rubic_id,
				'object_id' => $object_id,
				'object_type' => $object_type,
				'vote_data' => $vote_data,
			)
		);

		// Insert to meta table based on object_type.
		if ( 'comment' === $object_type ) {
			$total_vote_count = intval( get_post_meta( $object_id, $meta_key_total, true ) );
			$average_vote = intval( get_post_meta( $object_id, $meta_key_average, true ) );

			if ( 'false' === $total_vote_count || 'false' === $average_vote ) {
				add_comment_meta(
					$object_id,
					$meta_key_total,
					'1',
					true
				);

				add_comment_meta(
					$object_id,
					$meta_key_average,
					'1',
					true
				);
			}

			// Update post meta.

			// Update vote total only if user haven't vote for this post before.
			if ( false === $existing_vote_data ) {
				update_comment_meta(
					$object_id,
					$meta_key_total,
					$total_vote_count + 1,
					true
				);

				update_comment_meta(
					$object_id,
					$meta_key_average,
					round( ( $average_vote * $total_vote_count + $vote_data ) / ( $total_vote_count + 1 ) ),
					true
				);
			}

			update_comment_meta(
				$object_id,
				$meta_key_average,
				round( ( $average_vote * $total_vote_count + $vote_data - intval( $existing_vote_data ) ) / ( $total_vote_count ) ),
				true
			);
		} else {
			$total_vote_count = intval( get_post_meta( $object_id, $meta_key_total, true ) );
			$average_vote = intval( get_post_meta( $object_id, $meta_key_average, true ) );

			if ( 'false' === $total_vote_count || 'false' === $average_vote ) {
				update_post_meta(
					$object_id,
					$meta_key_total,
					1,
					true
				);

				update_post_meta(
					$object_id,
					$meta_key_average,
					round( $vote_data ),
					true
				);
			}

			// Update post meta.

			// If user never voted for this post before.
			if ( false === $existing_vote_data ) {
				update_post_meta(
					$object_id,
					$meta_key_total,
					$total_vote_count + 1,
					true
				);

				update_post_meta(
					$object_id,
					$meta_key_average,
					round( ( $average_vote * $total_vote_count + $vote_data ) / ( $total_vote_count + 1 ) ),
					true
				);
			}

			// If user already voted for this post before.
			update_post_meta(
				$object_id,
				$meta_key_average,
				round( ( $average_vote * $total_vote_count + $vote_data - intval( $existing_vote_data ) ) / ( $total_vote_count ) ),
				true
			);
		}

		return true;
	}//end update_meta_average()

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
	private static function update_meta_total( $user_id, $site_id, $rubric_id, $object_id, $object_type ) {
		$meta_key = 'ubc_wp_vote_' . $rubic_id . '_total';

		// Insert entry into global table.
		$vote_data = \UBC\CTLT\WPVote\WP_Vote_DB::get_vote_meta(
			array(
				'user_id' => $user_id,
				'site_id' => $site_id,
				'rubric_id' => $rubric_id,
				'object_id' => $object_id,
				'object_type' => $object_type,
			)
		);

		\UBC\CTLT\WPVote\WP_Vote_DB::update_vote_meta(
			array(
				'user_id' => $user_id,
				'site_id' => $site_id,
				'rubric_id' => $rubic_id,
				'object_id' => $object_id,
				'object_type' => $object_type,
				'vote_data' => '1' === $vote_data ? '0' : '1',
			)
		);

		// Insert to meta table based on object_type.
		if ( 'comment' === $object_type ) {
			$total_vote = get_comment_meta( $object_id, $meta_key, true );

			// Create comment_meta entry if no one has upvoted this post.
			if ( 'false' === $total_vote && '0' === $vote_data ) {
				return false;
			}

			if ( 'false' === $total_vote && '1' === $vote_data ) {
				add_comment_meta( $object_id, $meta_key, $vote_data, true );
			}

			// Update comment meta.
			update_comment_meta(
				$object_id,
				$meta_key,
				'1' === $vote_data ? intval( $total_vote ) + 1 : intval( $total_vote ) - 1,
				true
			);
		} else {
			$total_vote = get_post_meta( $object_id, $meta_key, true );

			if ( 'false' === $total_vote && '0' === $vote_data ) {
				return false;
			}

			if ( 'false' === $total_vote && '1' === $vote_data ) {
				add_post_meta( $object_id, $meta_key, $vote_data, true );
			}

			// Update post meta.
			update_post_meta(
				$object_id,
				$meta_key,
				'1' === $vote_data ? intval( $total_vote ) + 1 : intval( $total_vote ) - 1,
				true
			);
		}
	}//end update_meta_total()
}
