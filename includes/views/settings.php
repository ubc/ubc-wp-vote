<?php
	/**
	 * The template for rendering settings page
	 *
	 * @package ubc_wp_vote
	 */

	// Get post types.
	$post_types = get_post_types(
		array(
			'public' => true,
		),
		'objects'
	);
	$post_types = array_filter(
		$post_types,
		function( $post_type ) {
			return ! in_array( $post_type->name, UBC_WP_VOTE_POST_TYPES_TO_EXCLUDE );
		}
	);

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
					<?php foreach ( $post_types as $key => $single_post_type ) : ?>
						<li>
							<label>
								<input
									type="checkbox"
									name="ubc_wp_vote_valid_post_types[]"
									value="<?php echo esc_attr( $single_post_type->name ); ?>"
									<?php echo in_array( $single_post_type->name, $types ) ? 'checked' : ''; ?>
								> <?php echo esc_html( $single_post_type->label ); ?>
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
