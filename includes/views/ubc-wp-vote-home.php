<?php
/**
 * The template for UBC Vote home page
 *
 * @package ubc_wp_vote
 */

do_action( 'ubc_wp_vote_template_home' );

?>

<?php
while ( have_posts() ) :
	the_post();
	$object_type  = sanitize_key( get_post_type() );
	$object_id    = intval( get_the_ID() );
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

	$is_rating_valid = 'comment' !== $object_type ? \UBC\CTLT\WPVote\WP_Vote_Settings::is_object_rubric_valid( 'rating' ) : \UBC\CTLT\WPVote\WP_Vote_Settings::is_object_rubric_valid( 'rating', 0, true );
	?>
	<div class="facetwp-template__single">
		<div class="facetwp-template__single--status">
			<div>
				<?php
				echo floatval(
					get_comments(
						array(
							'post_id' => get_the_ID(),
							'count'   => true,
						)
					)
				);
				?>
			</div>
			<div>
				<span class="dashicons dashicons-admin-comments"></span>
			</div>
			<div>
				<?php echo floatval( $total_rating ); ?>
			</div>
			<div>
				<span class="dashicons dashicons-star-filled"></span>
			</div>
		</div>
		<div class="facetwp-template__single--content">
			<h2><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h2>
			<?php
			if ( 'post' === get_post_type() ) :
				$terms = get_the_terms( get_the_ID(), 'category' );
				// Escape array.
				$terms = array_map(
					function( $term ) {
						return esc_html( $term->name );
					},
					$terms
				);
				?>
				<span><?php echo join( ' | ', $terms ); ?></span>
			<?php endif; ?>
			<p><i><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>, <strong><?php echo esc_html( get_the_author() ); ?></strong></i></p>
			<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			<a href="<?php echo esc_url( get_the_permalink() ); ?>">Read More</a>
		</div>
	</div>
<?php endwhile; ?>
