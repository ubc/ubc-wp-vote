<?php
/**
 * The template for UBC Vote home page
 *
 * @package ubc_wp_vote
 */

require_once 'wp-admin/includes/template.php';

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
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<?php
		$args = array(
			'rating' => $total_rating ? floatval( $total_rating ) : 0,
			'type'   => 'rating',
		);
		if ( $is_rating_valid ) {
			wp_star_rating( $args );
		}
		?>
	<p><i>Posted on <?php the_date( 'l F j, Y' ); ?> by <strong><?php the_author(); ?></strong></i></p>
	<p><?php the_excerpt(); ?></p>
	<a href="<?php the_permalink(); ?>">Read More</a>
	<?php
	if ( 'post' === get_post_type() ) :
		$terms = get_the_terms( get_the_ID(), 'category' );
		$terms = array_map(
			function( $term ) {
				return esc_html( $term->name );
			},
			$terms
		);
		?>
		<p>Categories: <?php echo join( ', ', $terms ); ?></p>
	<?php endif; ?>
<?php endwhile; ?>


<script>
// Scripts from facetwp to add facet labels above filters https://facetwp.com/add-labels-above-each-facet/.
(function( $ ) {
	$(document).on('facetwp-loaded', function() {
		$('.facetwp-facet').each(function() {
			var $facet = $(this);
			var facet_name = $facet.attr('data-name');
			var facet_label = FWP.settings.labels[facet_name];

			if ($facet.closest('.facet-wrap').length < 1 && $facet.closest('.facetwp-flyout').length < 1) {
				$facet.wrap('<div class="facet-wrap"></div>');
				$facet.before('<span class="facet-label">' + facet_label + '</span>');
			}
		});
	});
})(jQuery);
</script>

<style>
	.facet-wrap{
		display: inline-block;
	}

	.facetwp-facet select{
		border-radius: 0;
	}
</style>
