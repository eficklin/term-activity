<?php
/**
 * Term_Activity_Maps class file
 */

namespace Term_Activity;

/**
 * Retrieve term activity maps.
 */
final class Term_Activity_Maps {
	/**
	 * Meta key prefix for storing activity snapshots.
	 *
	 * @var string
	 */
	private string $meta_key_prefix = '';

	/**
	 * Time periods for the activity snapshot.
	 *
	 * @var int[]
	 */
	private array $periods = [];

	/**
	 * Constructor.
	 *
	 * @param string $meta_key_prefix Meta key prefix.
	 * @param array  $periods         Time periods.
	 */
	public function __construct( string $meta_key_prefix, array $periods ) {
		$this->meta_key_prefix = $meta_key_prefix;
		$this->periods         = $periods;
	}

	/**
	 * Get activity map for a term and given post type.
	 *
	 * @param int    $term_id    The term ID.
	 * @param string $post_type  The post type.
	 * @return array Activity map data structure.
	 */
	public function get_single_activity_map( int $term_id, string $post_type ) {
		return get_term_meta( $term_id, $this->meta_key_prefix . $post_type, true );
	}

	/**
	 * Get merged activity maps for a term and given post type(s).
	 *
	 * @param int      $term_id    The term ID.
	 * @param string[] $post_types The post types.
	 * @return array Merged activity map for the post types.
	 */
	public function get_merged_activity_map( int $term_id, array $post_types ) {
		$temp_periods = [];
		$generated_at = null;

		foreach ( $post_types as $post_type ) {
			$activity = $this->get_single_activity_map( $term_id, $post_type );

			if ( $activity ) {
				foreach ( $activity['periods'] as $period ) {
					$temp_periods[ $period['days_prior'] ] ??= 0;
					$temp_periods[ $period['days_prior'] ]  += (int) ( isset( $period['posts'] ) ? count( $period['posts'] ) : ( $period['count'] ?? 0 ) );
				}

				/*
				 * 'generated_at' for the merged map is the earliest of all the dates in the single maps.
				 * We need to be sure that date queries based off it are early enough to return the
				 * number of posts stated in the count.
				 */
				$generated_at ??= $activity['generated_at'];
				$generated_at   = min( $generated_at, $activity['generated_at'] );
			}
		}

		$out = [
			'generated_at' => $generated_at,
			'periods'      => [],
		];

		foreach ( $this->periods as $period ) {
			$out['periods'][] = [
				'days_prior' => $period,
				'count'      => $temp_periods[ $period ] ?? 0,
			];
		}

		return $out;
	}
}
