<?php

namespace Plover\Nest\Cache\Stores;

use Plover\Nest\Cache\Contracts\Store;

/**
 * WordPress transient store Implementation
 * 
 * @since 1.0.0
 */
class WordPressTransientStore implements Store {

	/**
	 * @throws \RuntimeException
	 */
	public function __construct() {
		// Check WordPress runtime environment
		if ( ! function_exists( 'get_transient' ) ) {
			throw new \RuntimeException();
		}
	}

	/**
	 * @inheritDoc
	 * @param string $key
	 * @param mixed $default
	 */
	public function get( string $key, $default = null ) {
		$v = get_transient( $key );
		if ( $v === false ) {
			return $default;
		}

		return $v;
	}

	/**
	 * @inheritDoc
	 * @param string $key
	 * @param mixed $value
	 * @param int $seconds
	 * @return bool
	 */
	public function set( string $key, $value, int $seconds = 0 ): bool {
		return set_transient( $key, $value, $seconds > 0 ? $seconds : 0 );
	}

	/**
	 * @inheritDoc
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	public function forever( string $key, $value ): bool {
		return $this->set( $key, $value, 0 );
	}

	/**
	 * @inheritDoc
	 * @param string $key
	 * @return bool
	 */
	public function delete( string $key ): bool {
		return delete_transient( $key );
	}

	/**
	 * @inheritDoc
	 * @return bool
	 */
	public function flush(): bool {
        // No flush implementaion
		return false;
	}
}
