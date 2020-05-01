<?php
/**
 * Plugin Initialization including assets registration for WordPress.
 *
 * @package ubc_wp_vote
 */

namespace UBC\CTLT\WPVote;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\load_styles_scripts_single' );
add_action( 'ubc_wp_vote_setting_metabox', __NAMESPACE__ . '\\load_styles_scripts_meta_box' );
add_action( 'ubc_wp_vote_template_home', __NAMESPACE__ . '\\load_styles_scripts_home' );
add_action( 'wp-hybrid-clf_after_entry', __NAMESPACE__ . '\\render_post_content_actions' );
add_action( 'wp-hybrid-clf_after_comment', __NAMESPACE__ . '\\render_comment_content_actions' );
add_filter( 'facetwp_facet_html', __NAMESPACE__ . '\\render_rating_facet_filter', 10, 2 );
add_filter( 'facetwp_sort_options', __NAMESPACE__ . '\\facetwp_sort_options' );

/**
 * Enqueue styles and scripts for single template.
 *
 * @return void
 */
function load_styles_scripts_single() {
	if ( ! is_singular() ) {
		return;
	}

	wp_enqueue_script(
		'ctlt_wp_vote_object_content_actions_js',
		UBC_WP_VOTE_PLUGIN_URL . 'src/js/object-content-actions.js',
		array( 'jquery' ),
		filemtime( UBC_WP_VOTE_PLUGIN_DIR . 'src/js/object-content-actions.js' ),
		true
	);
	wp_localize_script(
		'ctlt_wp_vote_object_content_actions_js',
		'ubc_ctlt_wp_vote',
		array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'ubc' ),
		)
	);

	wp_register_style(
		'ctlt_wp_vote_object_content_actions_css',
		UBC_WP_VOTE_PLUGIN_URL . 'src/css/object-content-actions.css',
		false,
		filemtime( UBC_WP_VOTE_PLUGIN_DIR . 'src/css/object-content-actions.css' )
	);
	wp_enqueue_style( 'ctlt_wp_vote_object_content_actions_css' );
}//end load_styles_scripts_single()

/**
 * Enqueue styles and scripts for facet home page.
 *
 * @return void
 */
function load_styles_scripts_home() {
	wp_enqueue_script(
		'ctlt_wp_vote_home_js',
		UBC_WP_VOTE_PLUGIN_URL . 'src/js/facet-template.js',
		array( 'jquery' ),
		filemtime( UBC_WP_VOTE_PLUGIN_DIR . 'src/js/facet-template.js' ),
		true
	);

	wp_enqueue_script(
		'ctlt_wp_vote_home_polyfill_js',
		UBC_WP_VOTE_PLUGIN_URL . 'src/js/smoothscroll-polyfill.js',
		array( 'jquery' ),
		filemtime( UBC_WP_VOTE_PLUGIN_DIR . 'src/js/smoothscroll-polyfill.js' ),
		true
	);

	wp_register_style(
		'ctlt_wp_vote_home_css',
		UBC_WP_VOTE_PLUGIN_URL . 'src/css/facet-template.css',
		false,
		filemtime( UBC_WP_VOTE_PLUGIN_DIR . 'src/css/facet-template.css' )
	);
	wp_enqueue_style( 'ctlt_wp_vote_home_css' );
}//end load_styles_scripts_home()

/**
 * Enqueue styles and scripts for facet home page.
 *
 * @return void
 */
function load_styles_scripts_meta_box() {
	wp_register_style(
		'ctlt_wp_vote_metabox_css',
		UBC_WP_VOTE_PLUGIN_URL . 'src/css/metabox.css',
		false,
		filemtime( UBC_WP_VOTE_PLUGIN_DIR . 'src/css/metabox.css' )
	);
	wp_enqueue_style( 'ctlt_wp_vote_metabox_css' );
}//end load_styles_scripts_meta_box()

/**
 * Render rubric actions at the bottom of post content area.
 *
 * @return void
 */
function render_post_content_actions() {
	if ( ! is_singular() ) {
		return;
	}

	$object_type = get_post_type();
	$object_id   = get_the_ID();

	include UBC_WP_VOTE_PLUGIN_DIR . 'includes/views/object-content-actions.php';
}//end render_post_content_actions()

/**
 * Render rubric actions at the bottom of comment content area.
 *
 * @return void
 */
function render_comment_content_actions() {
	if ( ! is_singular() ) {
		return;
	}

	$object_type = 'comment';
	$object_id   = get_comment_ID();

	include UBC_WP_VOTE_PLUGIN_DIR . 'includes/views/object-content-actions.php';
}//end render_comment_content_actions()

/**
 * Facet WP - change the output html for rating filter facet type.
 *
 * @param string $output original html generated for facet type.
 * @param array  $params parameters come with current facet.
 *
 * @return string
 */
function render_rating_facet_filter( $output, $params ) {
	$rubric = get_page_by_title( 'Rating', 'OBJECT', 'ubc_wp_vote_rubric' );
	if ( ! $rubric ) {
		return $output;
	}

	$rubric_id = intval( $rubric->ID );

	if ( 'number_range' !== $params['facet']['type'] || 'cf/ubc_wp_vote_' . $rubric_id . '_average' !== $params['facet']['source'] ) {
		return $output;
	}

	$selected_value = is_array( $params['facet']['selected_values'] ) && 2 === count( $params['facet']['selected_values'] ) ? $params['facet']['selected_values'][0] : '0';

	// Add hidden class to existing number range fields so we can hide with css.
	$output = preg_replace( '/(class=")/', 'class="hidden ', $output );

	// Add dropdown html.
	$options = array(
		'0' => array(
			'label'       => 'all',
			'is_selected' => '0' === $selected_value,
		),
		'1' => array(
			'label'       => '1 star and up',
			'is_selected' => '1' === $selected_value,
		),
		'2' => array(
			'label'       => '2 stars and up',
			'is_selected' => '2' === $selected_value,
		),
		'3' => array(
			'label'       => '3 stars and up',
			'is_selected' => '3' === $selected_value,
		),
		'4' => array(
			'label'       => '4 stars and up',
			'is_selected' => '4' === $selected_value,
		),
		'5' => array(
			'label'       => '5 stars',
			'is_selected' => '5' === $selected_value,
		),
	);

	$html = '<select class="ubc_wp_vote_dropdown_rating">';
	foreach ( $options as $value => $option ) {
		if ( $option['is_selected'] ) {
			$html .= '<option value=' . $value . ' selected>' . $option['label'] . '</option>';
		} else {
			$html .= '<option value=' . $value . '>' . $option['label'] . '</option>';
		}
	}
	$html .= '</select>';

	return $html . $output;
}//end render_rating_facet_filter()

/**
 * Add facetwp sorting options.
 *
 * @param [array] $options default sorting options.
 * @return array
 */
function facetwp_sort_options( $options ) {
	$rubric_rating   = get_page_by_title( 'Rating', 'OBJECT', 'ubc_wp_vote_rubric' );
	$rubric_upvote   = get_page_by_title( 'Upvote', 'OBJECT', 'ubc_wp_vote_rubric' );
	$meta_key_rating = 'ubc_wp_vote_' . intval( $rubric_rating->ID ) . '_average';
	$meta_key_upvote = 'ubc_wp_vote_' . intval( $rubric_upvote->ID ) . '_total';

	if ( ! $rubric_rating ) {
		return $options;
	}

	$options['rating_desc'] = array(
		'label'      => 'Rating(Highest)',
		'query_args' => array(
			'orderby'    => 'meta_value_num',
			'order'      => 'DESC',
			'meta_query' => array(
				'relation'  => 'OR',
				array(
					'key'     => $meta_key_rating,
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => $meta_key_rating,
					'compare' => 'EXISTS',
				),
			),
		),
	);

	$options['rating_asc'] = array(
		'label'      => 'Rating(Lowest)',
		'query_args' => array(
			'orderby'    => 'meta_value_num',
			'order'      => 'ASC',
			'meta_query' => array(
				'relation'  => 'OR',
				array(
					'key'     => $meta_key_rating,
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => $meta_key_rating,
					'compare' => 'EXISTS',
				),
			),
		),
	);

	$options['upvote_desc'] = array(
		'label'      => 'Thumbs Up(Highest)',
		'query_args' => array(
			'orderby'    => 'meta_value_num',
			'order'      => 'DESC',
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key'     => $meta_key_upvote,
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => $meta_key_upvote,
					'compare' => 'EXISTS',
				),
			),
		),
	);

	$options['upvote_asc'] = array(
		'label'      => 'Thumbs Up(Lowest)',
		'query_args' => array(
			'orderby'    => 'meta_value_num',
			'order'      => 'ASC',
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key'     => $meta_key_upvote,
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => $meta_key_upvote,
					'compare' => 'EXISTS',
				),
			),
		),
	);

	return $options;
}
