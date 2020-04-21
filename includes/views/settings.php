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
		<h1>Settings</h1>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'ubc_wp_vote' );
				do_settings_sections( 'ubc_wp_vote' );
				$types = get_option( 'ubc_wp_vote_valid_post_types' ) ? get_option( 'ubc_wp_vote_valid_post_types' ) : array();
			?>
			<section id="group_fields" class="settings">
				<h2 class="group-fields-heading">Active post types on each rubric</h2>
				<hr>
				<?php foreach ( $rubrics as $key => $rubric ) : ?>
					<h3><?php echo esc_html( $rubric->label ); ?></h3>
					<ul>
						<?php foreach ( $object_types as $single_object_name => $single_object_type ) : ?>
							<li>
								<label>
									<input
										type="checkbox"
										name="ubc_wp_vote_valid_post_types[<?php echo esc_attr( $rubric->name ); ?>][]"
										value="<?php echo esc_attr( $single_object_name ); ?>"
										<?php echo array_key_exists( $rubric->name, $types ) && in_array( $single_object_name, $types[ $rubric->name ], true ) ? 'checked' : ''; ?>
									> <?php echo esc_html( $single_object_type->label ); ?>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
					<hr>
				<?php endforeach; ?>
			</section>
			<?php submit_button(); ?>
		</form>
	</div>

	<style>
		.settings{
			background-color: white;
			padding: 10px 20px;
			margin: 10px 0;
		}
	</style>
