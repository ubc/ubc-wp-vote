<?php
	/**
	 * The template to render admin active rubrics settings.
	 * The template requires $option_name and $object_title to be passed in.
	 *
	 * @package ubc_wp_vote
	 */

	$rubrics = \UBC\CTLT\WPVote\WP_Vote_Settings::get_rubrics_options();
?>

<section style="margin: 1rem 0;">
	<label for="">
		Override default <?php echo esc_html( $object_title ); ?> rubrics?
		<input
			type="checkbox"
			name="<?php echo esc_attr( $option_name ) . 'override'; ?>"
			onClick="onUBCWPVoteOverride( this )"
			id="<?php echo esc_attr( $option_name ) . 'override'; ?>"
			for="<?php echo esc_attr( $option_name ) . 'rubrics'; ?>"
			<?php if ( get_post_meta( intval( get_the_ID() ), esc_attr( $option_name ) . 'override', true ) ) : ?>
			checked
			<?php endif; ?>
		/>
	</label>

		<ul
			id="<?php echo esc_attr( $option_name ) . 'rubrics'; ?>"
			<?php if ( ! get_post_meta( intval( get_the_ID() ), esc_attr( $option_name ) . 'override', true ) ) : ?>
			class="hidden"
			<?php endif; ?>
		>
		<?php foreach ( $rubrics as $key => $rubric ) : ?>
			<li>
				<label>
					<input
						type="checkbox"
						name="<?php echo esc_attr( $option_name ) . 'rubrics[]'; ?>"
						value="<?php echo esc_attr( $rubric->name ); ?>"
						<?php if ( is_array( get_post_meta( intval( get_the_ID() ), esc_attr( $option_name ) . 'rubrics', true ) ) && in_array( $rubric->name, get_post_meta( intval( get_the_ID() ), esc_attr( $option_name ) . 'rubrics', true ) ) ) : ?>
						checked
						<?php endif; ?>
					/>
					<?php echo esc_html( $rubric->label ); ?>
				</label>
			</li>
		<?php endforeach; ?>
	</ul>
</section>

<?php wp_nonce_field( 'ubc_wp_vote', 'ubc_wp_vote_rubric_metabox' ); ?>

<script>
	if( typeof onUBCWPVoteOverride !== "function" ) {
		function onUBCWPVoteOverride( element ) {
			targetFieldID = element.getAttribute( 'for' );
			targetField = document.getElementById( targetFieldID );
			targetField.classList.toggle( 'hidden' );
		}
	}
</script>
