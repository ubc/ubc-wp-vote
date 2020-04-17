<?php
/**
 * Helper functions
 *
 * @package ubc_wp_vote
 * @since 0.0.1
 */

namespace UBC\CTLT\WPVote\Helpers;

/**
 * Round float to half integer, eg, 2.7 round to 2.5.
 *
 * @param [float] $number number to be rounded.
 * @return float
 */
function round_to_half_integer( $number ) {
	return round( $number * 2 ) / 2;
}//end round_to_half_integer()
