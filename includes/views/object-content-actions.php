<?php
	/**
	 * The template to render rubric actions.
	 * The template requires $object_id and $object_type to be passed in.
	 *
	 * @package ubc_wp_vote
	 */

if ( ! isset( $object_id ) || ! isset( $object_type ) ) {
	return;
}

require_once 'wp-admin/includes/template.php';

$object_id   = intval( $object_id );
$object_type = sanitize_text_field( $object_type );

$current_user_thumbs_up = \UBC\CTLT\WPVote\WP_Vote::get_object_current_user_up_vote(
	array(
		'object_type' => $object_type,
		'object_id'   => $object_id,
	)
);

$total_thumbs_up = \UBC\CTLT\WPVote\WP_Vote::get_object_current_user_up_vote(
	array(
		'object_type' => $object_type,
		'object_id'   => $object_id,
	)
);

$current_user_thumbs_down = \UBC\CTLT\WPVote\WP_Vote::get_object_current_user_down_vote(
	array(
		'object_type' => $object_type,
		'object_id'   => $object_id,
	)
);

$current_user_rating = \UBC\CTLT\WPVote\WP_Vote::get_object_current_user_rate(
	array(
		'object_type' => $object_type,
		'object_id'   => $object_id,
	)
);

$total_rating = \UBC\CTLT\WPVote\WP_Vote::get_object_rate_average(
	array(
		'object_type' => $object_type,
		'object_id'   => $object_id,
	)
);

$total_thumbs_up = \UBC\CTLT\WPVote\WP_Vote::get_object_total_up_vote(
	array(
		'object_type' => $object_type,
		'object_id'   => $object_id,
	)
);

?>
<?php if ( is_user_logged_in() ) : ?>
	<div class="ubc-wp-vote__thumbs" data-id="<?php echo esc_attr( $object_id ); ?>" data-type="<?php echo esc_attr( $object_type ); ?>">
		<button class="ubc_wp-vote__action ubc-wp-vote__thumbs-up<?php echo 1 === intval( $current_user_thumbs_up ) ? ' active' : ''; ?>">
			<span class="dashicons dashicons-thumbs-up"></span>
		</button>
		<button class="ubc_wp-vote__action ubc-wp-vote__thumbs-down<?php echo 1 === intval( $current_user_thumbs_down ) ? ' active' : ''; ?>">
			<span class="dashicons dashicons-thumbs-down"></span>
		</button>
		<span>( <?php echo ! empty( $total_thumbs_up ) ? intval( $total_thumbs_up ) : 0; ?> )</span>
	</div>
<?php endif; ?>
<div class="ubc-wp-vote__star-rating" data-id="<?php echo esc_attr( $object_id ); ?>" data-type="<?php echo esc_attr( $object_type ); ?>">

	<?php if ( is_user_logged_in() ) : ?>
		<div class="ubc-wp-vote_star_rating--current">
		<?php
			$args = array(
				'rating' => $current_user_rating ? floatval( $current_user_rating ) : 0,
				'type'   => 'rating',
			);
			wp_star_rating( $args );
			?>
			</div>
	<?php endif; ?>


	<div class="ubc-wp-vote__star-rating--overall">
		<?php
			$args = array(
				'rating' => $total_rating ? floatval( $total_rating ) : 0,
				'type'   => 'rating',
			);
			wp_star_rating( $args );
			?>
	</div>
</div>
