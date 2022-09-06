<?php

namespace Redirection\Group;

class Filters {
	private $filters = [];

	public function __construct( $filter_params ) {
		global $wpdb;

		foreach ( $filter_params as $filter_by => $filter ) {
			if ( $filter_by === 'status' ) {
				if ( $filter === 'enabled' ) {
					$this->filters[] = "status='enabled'";
				} else {
					$this->filters[] = "status='disabled'";
				}
			} elseif ( $filter_by === 'module' ) {
				$this->filters[] = $wpdb->prepare( 'module_id=%d', intval( $filter, 10 ) );
			} elseif ( $filter_by === 'name' ) {
				$this->filters[] = $wpdb->prepare( 'name LIKE %s', '%' . $wpdb->esc_like( trim( $filter ) ) . '%' );
			}
		}
	}

	public function get_as_sql() {
		if ( count( $this->filters ) > 0 ) {
			return ' WHERE ' . implode( ' AND ', $this->filters );
		}

		return '';
	}
}
