<?php

namespace Plover\Nest\Cache\Contracts;

/**
 * Cache store interface
 * 
 * @since 1.0.0
 */
interface Store {

	/**
	 * Get a cache item
	 * 
	 * @param string $key
	 * @return mixed|null
	 */
	public function get( string $key );

	/**
	 * Save a cache item with ttl
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 * @return bool
	 */
	public function set( string $key, $value, int $ttl = 0 ): bool;

	/**
	 * Save a cache item, it never expires
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	public function forever( string $key, $value ): bool;

	/**
	 * Delete a cache item
	 * 
	 * @param string $key
	 * @return bool
	 */
	public function delete( string $key ): bool;

	/**
	 * Clear all cache
	 * 
	 * @return bool
	 */
	public function flush(): bool;
}
