<?php
/**
 * The template for UBC Vote home page
 *
 * @package ubc_wp_vote
 */

do_action( 'ubc_wp_vote_template_home' );

?>
<div class="facet-template__toggle"><button class="btn btn-primary"><?php esc_html_e( 'Filter content' ); ?></button></div>
<div class="facet-template--with-feature">
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
			<div class="facetwp-template__single--overview">
				<div class="facetwp-template__single--avatar">
					<img src="<?php echo esc_url( get_avatar_url( get_the_author_meta( 'ID' ) ) ); ?>" alt="post author avatar image">
				</div>
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
			</div>
			<h2 class="facetwp-template__single--title"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h2>
			<?php
			if ( 'post' === get_post_type() ) :
				$terms = get_the_terms( get_the_ID(), 'category' );
				// Escape array.
				$terms = array_map(
					function( $term ) {
						$term = get_term_by( 'name', esc_html( $term->name ), 'category' );
						return '<a class="ubc-wp-vote__facet-category" href="' . esc_url( get_category_link( intval( $term->term_id ) ) ) . '" data-cat="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</a>';
					},
					$terms
				);
				?>
				<div class="facetwp-template__single--meta">
					<?php echo join( ' | ', $terms ); ?>
					<p><i><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>, <strong><?php the_author_posts_link(); ?></p></strong></i></p>
				</div>
			<?php endif; ?>

			<div class="facetwp-template__single--content">
				<p class="truncate-overflow"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<a class="btn btn-primary facetwp-template__single--readmore" href="<?php echo esc_url( get_the_permalink() ); ?>"><?php esc_html_e( 'Read More' ); ?></a>
			</div>
			<div class="facetwp-template__single--feature">
				<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
			</div>
		</div>
	<?php endwhile; ?>
</div>
