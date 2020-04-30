<?php
	/**
	 * The template to render admin active rubrics settings.
	 * The template requires $option_name and $object_title to be passed in.
	 *
	 * @package ubc_wp_vote
	 */

	$rubrics = \UBC\CTLT\WPVote\WP_Vote_Settings::get_rubrics_options();
	do_action( 'ubc_wp_vote_setting_metabox' );
?>

<section class="ubc-wp-vote-metabox">
	<label for="">
		Rubrics for <?php echo esc_html( $object_title ); ?>?
	</label>

		<ul
			id="<?php echo esc_attr( $option_name ) . 'rubrics'; ?>"
		>
		<?php foreach ( $rubrics as $key => $rubric ) : ?>
			<li>
				<label>
					<?php echo esc_html( $rubric->label ); ?>
				</label>
				<input
					type="checkbox"
					name="<?php echo esc_attr( $option_name ) . 'rubrics[]'; ?>"
					value="<?php echo esc_attr( $rubric->name ); ?>"
					<?php if ( \UBC\CTLT\WPVote\WP_Vote_Settings::is_object_rubric_valid( $rubric->name, intval( get_the_ID() ), 'COMMENT' === $object_title ) ) : ?>
					checked
					<?php endif; ?>
				/>
			</li>
		<?php endforeach; ?>
	</ul>
</section>

<?php wp_nonce_field( 'ubc_wp_vote', 'ubc_wp_vote_rubric_metabox' ); ?>
