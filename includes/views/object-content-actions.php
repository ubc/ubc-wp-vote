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

$total_thumbs_up = \UBC\CTLT\WPVote\WP_Vote::get_object_total_up_vote(
	array(
		'object_type' => $object_type,
		'object_id'   => $object_id,
	)
);

$total_thumbs_down = \UBC\CTLT\WPVote\WP_Vote::get_object_total_down_vote(
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

$total_rating_count = \UBC\CTLT\WPVote\WP_Vote::get_object_rate_count(
	array(
		'object_type' => $object_type,
		'object_id'   => $object_id,
	)
);

$is_thumb_up_valid   = 'comment' !== $object_type ? \UBC\CTLT\WPVote\WP_Vote_Settings::is_object_rubric_valid( 'upvote' ) : \UBC\CTLT\WPVote\WP_Vote_Settings::is_object_rubric_valid( 'upvote', 0, true );
$is_thumb_down_valid = 'comment' !== $object_type ? \UBC\CTLT\WPVote\WP_Vote_Settings::is_object_rubric_valid( 'downvote' ) : \UBC\CTLT\WPVote\WP_Vote_Settings::is_object_rubric_valid( 'downvote', 0, true );
$is_rating_valid     = 'comment' !== $object_type ? \UBC\CTLT\WPVote\WP_Vote_Settings::is_object_rubric_valid( 'rating' ) : \UBC\CTLT\WPVote\WP_Vote_Settings::is_object_rubric_valid( 'rating', 0, true );

$summary = array();
if ( $is_thumb_up_valid ) {
	$thumb_up  = ! empty( $total_thumbs_up ) ? intval( $total_thumbs_up ) : 0;
	$summary[] = '<span class="ubc-wp-vote_thumbs-up-total">' . $thumb_up . '</span> ' . __( 'upvotes' );
}
if ( $is_thumb_down_valid ) {
	$thumb_down = ! empty( $total_thumbs_down ) ? intval( $total_thumbs_down ) : 0;
	$summary[]  = '<span class="ubc-wp-vote_thumbs-down-total">' . $thumb_down . '</span> ' . __( 'downvotes' );
}
?>

<?php if ( $is_thumb_up_valid || $is_thumb_down_valid || $is_rating_valid ) : ?>
	<hr>
<?php endif; ?>

<div class="ubc-wp-vote__thumbs" data-id="<?php echo esc_attr( $object_id ); ?>" data-type="<?php echo esc_attr( $object_type ); ?>">
	<?php if ( is_user_logged_in() && ( \UBC\CTLT\WPVote\WP_Vote_Settings::is_allow_vote_own_object() || ! \UBC\CTLT\WPVote\WP_Vote::is_current_user_object( $object_type, $object_id ) ) ) : ?>
<?php if ( $is_thumb_up_valid ) : ?><button class="ubc_wp-vote__action ubc-wp-vote__thumbs-up<?php echo 1 === intval( $current_user_thumbs_up ) ? ' active' : ''; ?>"><span class="dashicons dashicons-thumbs-up"></span></button><?php endif; ?><?php if ( $is_thumb_down_valid ) : ?><button class="ubc_wp-vote__action ubc-wp-vote__thumbs-down<?php echo 1 === intval( $current_user_thumbs_down ) ? ' active' : ''; ?>"><span class="dashicons dashicons-thumbs-down"></span></button><?php endif; ?>
	<?php endif; ?>
	<?php if ( $is_thumb_up_valid || $is_thumb_down_valid ) : ?>
<span>( <?php echo wp_kses( join( ' and ', $summary ), array( 'span' => array( 'class' => array() ) ) ); ?> )</span>
	<?php endif; ?>
</div>

<?php if ( $is_rating_valid ) : ?>
	<div class="ubc-wp-vote__star-rating" data-id="<?php echo esc_attr( $object_id ); ?>" data-type="<?php echo esc_attr( $object_type ); ?>">
		<?php if ( is_user_logged_in() && ( \UBC\CTLT\WPVote\WP_Vote_Settings::is_allow_vote_own_object() || ! \UBC\CTLT\WPVote\WP_Vote::is_current_user_object( $object_type, $object_id ) ) ) : ?>
			<p><?php esc_html_e( 'Rate this content' ); ?>:</p>
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
<span class="ubc-wp-vote__star-rating--overall">(&nbsp;<?php esc_html_e( 'Average Rating' ); ?>: <span class="ubc-wp-vote__rating" data-current_average="<?php echo $current_user_rating ? floatval( $current_user_rating ) : 0; ?>" data-overall_count="<?php echo $total_rating_count ? intval( $total_rating_count ) : 0; ?>" data-overall_average="<?php echo $total_rating ? floatval( $total_rating ) : 0; ?>"><?php echo floatval( $total_rating ); ?></span>&nbsp;)</span>
	</div>
<?php endif; ?>
