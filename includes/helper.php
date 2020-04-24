<?php
/**
 * Helper functions
 *
 * @package ubc_wp_vote
 * @since 0.0.1
 */

namespace UBC\CTLT\WPVote\Helpers;

/**
 * Log to error log file
 *
 * @param mixed $log object/string to print to log file.
 */
function write_log( $log ) {
	if ( is_array( $log ) || is_object( $log ) ) {
		error_log( print_r( $log, true ) );
	} else {
		error_log( $log );
	}
}

/**
 * Round float to half integer, eg, 2.7 round to 2.5.
 *
 * @param [float] $number number to be rounded.
 * @return float
 */
function round_to_half_integer( $number ) {
	return round( $number * 2 ) / 2;
}//end round_to_half_integer()

/**
 * Sort array of objects based on object property.
 *
 * @param [array]  $array array to be sorted.
 * @param [string] $property object property name to be sorted by.
 * @param string   $order order, either 'ASC' or 'DESC.
 * @return array
 */
function sort_array_of_objects_by_property( $array, $property, $order = 'ASC' ) {
	if ( ! $array || ! is_array( $array ) ) {
		return $array;
	}

	usort(
		$array,
		function( $a, $b ) use ( $property, $order ) {
			if ( $a->$property === $b->$property ) {
				return 0;
			}

			if ( $a->$property < $b->$property && 'ASC' === $order ) {
				return -1;
			}

			if ( $a->$property > $b->$property && 'ASC' === $order ) {
				return 1;
			}

			if ( $a->$property < $b->$property && 'DESC' === $order ) {
				return 1;
			}

			if ( $a->$property > $b->$property && 'DESC' === $order ) {
				return -1;
			}
		}
	);

	return $array;
}
