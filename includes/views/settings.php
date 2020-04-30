<?php
	/**
	 * The template for rendering settings page
	 *
	 * @package ubc_wp_vote
	 */

	// Get post types.
	$object_types = \UBC\CTLT\WPVote\WP_Vote_Settings::get_object_types_options();
	$rubrics = \UBC\CTLT\WPVote\WP_Vote_Settings::get_rubrics_options();
?>

	<div class="wrap">
		<h1>UBC WP Vote Settings</h1>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'ubc_wp_vote' );
				do_settings_sections( 'ubc_wp_vote' );
				$types = get_option( 'ubc_wp_vote_valid_post_types' ) ? get_option( 'ubc_wp_vote_valid_post_types' ) : array();
			?>
			<section id="group_fields">
				<h2 class="group-fields-heading">Default rubrics for post types</h2>
				<p>These site-wide settings set the default status for each rubric. Checking a box next to a post type here means that particular rubric will be shown by default on single posts of that post type. You can override this site-wide setting for each individual post.</p>
				<table class="form-table">
					<tbody>
						<?php foreach ( $rubrics as $key => $rubric ) : ?>
							<tr>
								<th>
									<div style="margin-bottom: 1rem;"><?php echo esc_html( $rubric->label ); ?></div>
									<?php if ( 'upvote' === $rubric->name ) : ?>
										<div class="dashicons dashicons-thumbs-up"></div>
									<?php endif; ?>
									<?php if ( 'downvote' === $rubric->name ) : ?>
										<div class="dashicons dashicons-thumbs-down"></div>
									<?php endif; ?>
									<?php if ( 'rating' === $rubric->name ) : ?>
										<div class="dashicons dashicons-star-filled"></div>
									<?php endif; ?>
								</th>
								<td>
									<fieldset>
										<legend class="screen-reader-text">
											<?php if ( $rubric->screen_reader_text ) : ?>
												<span><?php echo esc_html( $rubric->screen_reader_text ); ?></span>
											<?php endif; ?>
										</legend>
										<?php foreach ( $object_types as $single_object_name => $single_object_type ) : ?>
											<label>
												<input
													type="checkbox"
													name="ubc_wp_vote_valid_post_types[<?php echo esc_attr( $rubric->name ); ?>][]"
													value="<?php echo esc_attr( $single_object_name ); ?>"
													<?php echo array_key_exists( $rubric->name, $types ) && in_array( $single_object_name, $types[ $rubric->name ], true ) ? 'checked' : ''; ?>
												> <?php echo esc_html( $single_object_type->label ); ?>
											</label>
											<br />
										<?php endforeach; ?>
										<?php if ( $rubric->description ) : ?>
											<p class="description"><?php echo esc_html( $rubric->description ); ?></p>
										<?php endif; ?>
									</fieldset>
								</td>
							</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</section>
			<?php submit_button(); ?>
		</form>
	</div>
