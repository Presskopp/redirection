<?php

namespace Redirection\Match;

use Redirection\Site;

/**
 * Check a HTTP request header
 */
class Header extends Match {
	use FromNotFrom_Match;

	/**
	 * HTTP header name
	 *
	 * @var String
	 */
	public $name = '';

	/**
	 * HTTP header value
	 *
	 * @var String
	 */
	public $value = '';

	/**
	 * Is this a regex?
	 *
	 * @var boolean
	 */
	public $regex = false;

	public function name() {
		return __( 'URL and HTTP header', 'redirection' );
	}

	public function save( array $details, $no_target_url = false ) {
		$data = array(
			'regex' => isset( $details['regex'] ) && $details['regex'] ? true : false,
			'name'  => isset( $details['name'] ) ? $this->sanitize_name( $details['name'] ) : '',
			'value' => isset( $details['value'] ) ? $this->sanitize_value( $details['value'] ) : '',
		);

		return $this->save_data( $details, $no_target_url, $data );
	}

	public function sanitize_name( $name ) {
		$name = $this->sanitize_url( $name );
		$name = str_replace( ' ', '', $name );
		$name = preg_replace( '/[^A-Za-z0-9\-_]/', '', $name );

		return trim( trim( $name, ':' ) );
	}

	public function sanitize_value( $value ) {
		return $this->sanitize_url( $value );
	}

	public function is_match( $url ) {
		if ( $this->regex ) {
			$regex = new Site\Regex( $this->value, true );
			return $regex->is_match( Site\Request::get_header( $this->name ) );
		}

		return Site\Request::get_header( $this->name ) === $this->value;
	}

	public function get_data() {
		return array_merge( array(
			'regex' => $this->regex,
			'name' => $this->name,
			'value' => $this->value,
		), $this->get_from_data() );
	}

	/**
	 * Load the match data into this instance.
	 *
	 * @param String $values Match values, as read from the database (plain text or serialized PHP).
	 * @return void
	 */
	public function load( $values ) {
		$values = $this->load_data( $values );
		$this->regex = isset( $values['regex'] ) ? $values['regex'] : false;
		$this->name = isset( $values['name'] ) ? $values['name'] : '';
		$this->value = isset( $values['value'] ) ? $values['value'] : '';
	}
}
