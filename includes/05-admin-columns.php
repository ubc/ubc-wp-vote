<?php
/**
 * WordPress admin listing columns
 *
 * @package ubc_wp_vote
 */

namespace UBC\CTLT\WPVote\AdminColumns;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$object_types = \UBC\CTLT\WPVote\WP_Vote_Settings::get_object_types_options();

if ( is_admin() ) {
	foreach ( $object_types as $key => $object_type ) {
		if ( 'comment' !== $key ) {
			add_filter( 'manage_' . $key . '_posts_columns', __NAMESPACE__ . '\\filter_posts_columns' );
			add_action( 'manage_' . $key . '_posts_custom_column', __NAMESPACE__ . '\\populated_custom_column_data', 10, 2 );
			add_filter( 'manage_edit-' . $key . '_sortable_columns', __NAMESPACE__ . '\\manage_sortable_columns' );
		}
	}
	add_filter( 'manage_users_columns', __NAMESPACE__ . '\\filter_user_columns' );
	add_filter( 'manage_users_custom_column', __NAMESPACE__ . '\\filter_user_columns_content', 10, 3 );

	add_action( 'restrict_manage_posts', __NAMESPACE__ . '\\admin_custom_post_filter_rating' );
	add_filter( 'parse_query', __NAMESPACE__ . '\\admin_custom_post_filter_rating_query' );
}

add_action( 'pre_get_posts', __NAMESPACE__ . '\\admin_custom_post_orderby_upvote' );
add_action( 'pre_get_posts', __NAMESPACE__ . '\\admin_custom_post_orderby_downvote' );
add_action( 'pre_get_posts', __NAMESPACE__ . '\\admin_custom_post_orderby_rating' );

/**
 * Add extra columns in WordPress listing page.
 *
 * @param array $columns existing columns.
 * @return array
 */
function filter_posts_columns( $columns ) {
	$current_screen = get_current_screen();

	if ( ! isset( $current_screen->post_type ) ) {
		return $columns;
	}

	$post_type             = sanitize_key( $current_screen->post_type );
	$global_active_rubrics = get_option( 'ubc_wp_vote_valid_post_types' );

	if ( ! $global_active_rubrics ) {
		return $columns;
	}

	if ( isset( $global_active_rubrics['upvote'] ) && in_array( $post_type, $global_active_rubrics['upvote'], true ) ) {
		$columns['upvote'] = 'Up Vote';
	}

	if ( isset( $global_active_rubrics['downvote'] ) && in_array( $post_type, $global_active_rubrics['downvote'], true ) ) {
		$columns['downvote'] = 'Down Vote';
	}

	if ( isset( $global_active_rubrics['rating'] ) && in_array( $post_type, $global_active_rubrics['rating'], true ) ) {
		$columns['rating'] = 'Rating';
	}

	return $columns;
}//end filter_posts_columns()

/**
 * Add extra columns in WordPress user listing page.
 *
 * @param array $columns existing columns.
 * @return array
 */
function filter_user_columns( $columns ) {
	$columns['num_comments']  = __( '# of comments' );
	$columns['num_ratings']   = __( '# of ratings' ) . '<div>( ' . __( 'posts & comments' ) . ' )</div>';
	$columns['num_upvotes']   = __( '# of upvotes' ) . '<div>( ' . __( 'posts & comments' ) . ' )</div>';
	$columns['num_downvotes'] = __( '# of downvotes' ) . '<div>( ' . __( 'posts & comments' ) . ' )</div>';
	return $columns;
}//end filter_user_columns()

/**
 * Polulated data for admin user page custom columns.
 *
 * @param [string] $output original output.
 * @param [string] $column_name name of the column to update value.
 * @param [number] $user_id ID of user attached.
 * @return string
 */
function filter_user_columns_content( $output, $column_name, $user_id ) {
	switch ( $column_name ) {
		case 'num_comments':
			$args = array(
				'user_id' => $user_id,
				'count'   => true,
			);
			return get_comments( $args );
		case 'num_ratings':
			$rubric = get_page_by_title( 'Rating', 'OBJECT', 'ubc_wp_vote_rubric' );
			if ( ! $rubric ) {
				return 0;
			}
			$records = \UBC\CTLT\WPVote\WP_Vote_DB::get_vote_meta(
				array(
					'user_id'   => intval( $user_id ),
					'site_id'   => intval( get_current_blog_id() ),
					'rubric_id' => intval( $rubric->ID ),
				)
			);
			return is_array( $records ) ? count( $records ) : 0;
		case 'num_upvotes':
			$rubric = get_page_by_title( 'Upvote', 'OBJECT', 'ubc_wp_vote_rubric' );
			if ( ! $rubric ) {
				return 0;
			}
			$records = \UBC\CTLT\WPVote\WP_Vote_DB::get_vote_meta(
				array(
					'user_id'   => intval( $user_id ),
					'site_id'   => intval( get_current_blog_id() ),
					'rubric_id' => intval( $rubric->ID ),
				)
			);
			return is_array( $records ) ? count( $records ) : 0;
		case 'num_downvotes':
			$rubric = get_page_by_title( 'Downvote', 'OBJECT', 'ubc_wp_vote_rubric' );
			if ( ! $rubric ) {
				return 0;
			}
			$records = \UBC\CTLT\WPVote\WP_Vote_DB::get_vote_meta(
				array(
					'user_id'   => intval( $user_id ),
					'site_id'   => intval( get_current_blog_id() ),
					'rubric_id' => intval( $rubric->ID ),
				)
			);
			return is_array( $records ) ? count( $records ) : 0;
	}
	return $output;
}

/**
 * Polulated data for custom columns.
 *
 * @param [string] $column column name.
 * @param [number] $post_id post id.
 * @return void
 */
function populated_custom_column_data( $column, $post_id ) {
	if ( 'upvote' === $column ) {
		$upvote = \UBC\CTLT\WPVote\WP_Vote::get_object_total_up_vote(
			array(
				'object_type' => get_post_type( $post_id ),
				'object_id'   => $post_id,
			)
		);
		echo intval( $upvote );
	}

	if ( 'downvote' === $column ) {
		$downvote = \UBC\CTLT\WPVote\WP_Vote::get_object_total_down_vote(
			array(
				'object_type' => get_post_type( $post_id ),
				'object_id'   => $post_id,
			)
		);
		echo intval( $downvote );
	}

	if ( 'rating' === $column ) {
		$rating = \UBC\CTLT\WPVote\WP_Vote::get_object_rate_average(
			array(
				'object_type' => get_post_type( $post_id ),
				'object_id'   => $post_id,
			)
		);
		$args   = array(
			'rating' => $rating ? floatval( $rating ) : 0,
			'type'   => 'rating',
		);
		wp_star_rating( $args );
	}
}//end populated_custom_column_data()

/**
 * Transform static columns to sortable columns.
 *
 * @param [array] $columns active admin listing columns.
 * @return array
 */
function manage_sortable_columns( $columns ) {
	$current_screen = get_current_screen();

	if ( ! isset( $current_screen->post_type ) ) {
		return $columns;
	}

	$post_type             = sanitize_key( $current_screen->post_type );
	$global_active_rubrics = get_option( 'ubc_wp_vote_valid_post_types' );

	if ( ! $global_active_rubrics ) {
		return $columns;
	}

	if ( isset( $global_active_rubrics['upvote'] ) && in_array( $post_type, $global_active_rubrics['upvote'], true ) ) {
		$columns['upvote'] = 'upvote';
	}

	if ( isset( $global_active_rubrics['downvote'] ) && in_array( $post_type, $global_active_rubrics['downvote'], true ) ) {
		$columns['downvote'] = 'downvote';
	}

	if ( isset( $global_active_rubrics['rating'] ) && in_array( $post_type, $global_active_rubrics['rating'], true ) ) {
		$columns['rating'] = 'rating';
	}
	return $columns;
}//end manage_sortable_columns()

/**
 * Provide custom algorithem to sort posts by up vote total.
 *
 * @param WP_Query $query WP query object.
 * @return boolean|void
 */
function admin_custom_post_orderby_upvote( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return $query;
	}

	$rubric_upvote = get_page_by_title( 'Upvote', 'OBJECT', 'ubc_wp_vote_rubric' );

	if ( ! $rubric_upvote || 'upvote' !== $query->get( 'orderby' ) ) {
		return $query;
	}

	$query->set(
		'meta_query',
		array(
			'relation'  => 'OR',
			array(
				'key'     => 'ubc_wp_vote_' . intval( $rubric_upvote->ID ) . '_total',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'ubc_wp_vote_' . intval( $rubric_upvote->ID ) . '_total',
				'compare' => 'EXISTS',
			),
		)
	);

	$query->set( 'orderby', 'meta_value_num' );

	return $query;
}//end admin_custom_post_orderby_upvote()

/**
 * Provide custom algorithem to sort posts by down vote total.
 *
 * @param WP_Query $query WP query object.
 * @return boolean|void
 */
function admin_custom_post_orderby_downvote( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return $query;
	}

	$rubric_downvote = get_page_by_title( 'Downvote', 'OBJECT', 'ubc_wp_vote_rubric' );

	if ( ! $rubric_downvote || 'downvote' !== $query->get( 'orderby' ) ) {
		return $query;
	}

	$query->set(
		'meta_query',
		array(
			'relation'  => 'OR',
			array(
				'key'     => 'ubc_wp_vote_' . intval( $rubric_downvote->ID ) . '_total',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'ubc_wp_vote_' . intval( $rubric_downvote->ID ) . '_total',
				'compare' => 'EXISTS',
			),
		)
	);

	$query->set( 'orderby', 'meta_value_num' );

	return $query;
}//end admin_custom_post_orderby_downvote()

/**
 * Provide custom algorithem to sort posts by average rating.
 *
 * @param WP_Query $query WP query object.
 * @return boolean|void
 */
function admin_custom_post_orderby_rating( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return $query;
	}

	$rubric_rating = get_page_by_title( 'Rating', 'OBJECT', 'ubc_wp_vote_rubric' );

	if ( ! $rubric_rating || 'rating' !== $query->get( 'orderby' ) ) {
		return $query;
	}

	$query->set(
		'meta_query',
		array(
			'relation'  => 'OR',
			array(
				'key'     => 'ubc_wp_vote_' . intval( $rubric_rating->ID ) . '_average',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'ubc_wp_vote_' . intval( $rubric_rating->ID ) . '_average',
				'compare' => 'EXISTS',
			),
		)
	);

	$query->set( 'orderby', 'meta_value_num' );

	return $query;
}//end admin_custom_post_orderby_rating()

/**
 * Add rating dropdown to post admin list page as filter.
 *
 * @return void
 */
function admin_custom_post_filter_rating() {
	$current_screen = get_current_screen();

	if ( ! isset( $current_screen->post_type ) ) {
		return;
	}

	$post_type             = sanitize_key( $current_screen->post_type );
	$global_active_rubrics = get_option( 'ubc_wp_vote_valid_post_types' );
	$current_rating_filter = isset( $_GET['ubc_admin_listing_filter_rating'] ) ? intval( $_GET['ubc_admin_listing_filter_rating'] ) : 0;

	if ( ! $global_active_rubrics ) {
		return;
	}

	if ( isset( $global_active_rubrics['rating'] ) && in_array( $post_type, $global_active_rubrics['rating'], true ) ) : ?>
		<select name="ubc_admin_listing_filter_rating" id="ubc_admin_listing_filter_rating">
			<option value="">All</option>
			<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
				<option
					value="<?php echo intval( $i ); ?>"
					<?php if ( $current_rating_filter === $i ) : ?>
						selected
					<?php endif; ?>
				>
					<?php echo intval( $i ); ?> stars rating and up
				</option>
			<?php endfor; ?>
		</select>
		<?php
	endif;

	wp_nonce_field( 'ubc_wp_vote', 'ubc_wp_vote_admin_list_filter_rating' );
}//end admin_custom_post_filter_rating()

/**
 * Update the WP_Query object based on the value of rating filter.
 *
 * @param [WP_Query] $query current query object to get the posts.
 * @return void
 */
function admin_custom_post_filter_rating_query( $query ) {
	if ( ! isset( $_GET['ubc_admin_listing_filter_rating'] ) || '' === $_GET['ubc_admin_listing_filter_rating'] || ! check_admin_referer( 'ubc_wp_vote', 'ubc_wp_vote_admin_list_filter_rating' ) ) {
		return;
	}

	$rubric_rating   = get_page_by_title( 'Rating', 'OBJECT', 'ubc_wp_vote_rubric' );
	$rating_selected = intval( $_GET['ubc_admin_listing_filter_rating'] );

	if ( ! $rubric_rating ) {
		return;
	}

	$query->set(
		'meta_query',
		array(
			array(
				'key'     => 'ubc_wp_vote_' . intval( $rubric_rating->ID ) . '_average',
				'compare' => '>=',
				'value'   => $rating_selected,
			),
		)
	);
}//end admin_custom_post_filter_rating_query()
