<?php
/**
 * The template for UBC Vote home page
 *
 * @package ubc_wp_vote
 */

require_once 'wp-admin/includes/template.php';
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

	$is_rating_valid = 'comment' !== $object_type ? \UBC\CTLT\WPVote\WP_Vote_Settings::is_object_rubric_valid( 'rating' ) : \UBC\CTLT\WPVote\WP_Vote_Settings::is_object_rubric_valid( 'rating', 0, true );
	?>
	<div>
		<h2><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h2>
		<?php
			$args = array(
				'rating' => $total_rating ? floatval( $total_rating ) : 0,
				'type'   => 'rating',
			);
			if ( $is_rating_valid ) {
				wp_star_rating( $args );
			}
			?>
		<p><i>Posted on <?php echo esc_html( get_the_date( 'l F j, Y' ) ); ?> by <strong><?php echo esc_html( get_the_author() ); ?></strong></i></p>
		<p><?php echo esc_html( get_the_excerpt() ); ?></p>
		<a href="<?php echo esc_url( get_the_permalink() ); ?>">Read More</a>
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
			<p>Categories: <?php echo join( ', ', $terms ); ?></p>
		<?php endif; ?>
	</div>
<?php endwhile; ?>
