<?php
	/**
	 * The template for rendering settings page
	 *
	 * @package ubc_wp_vote
	 */

	// Get post types.
	$object_types = \UBC\CTLT\WPVote\WP_Vote_Settings::get_object_types_options();

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
				<h2 class="group-fields-heading">Active post types</h2>
				<ul>
					<?php foreach ( $object_types as $single_object_name => $single_object_type ) : ?>
						<li>
							<label>
								<input
									type="checkbox"
									name="ubc_wp_vote_valid_post_types[]"
									value="<?php echo esc_attr( $single_object_name ); ?>"
									<?php echo in_array( $single_object_name, $types, true ) ? 'checked' : ''; ?>
								> <?php echo esc_html( $single_object_type->label ); ?>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
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
