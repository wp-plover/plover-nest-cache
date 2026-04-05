<?php

namespace Plover\Nest\Cache\Facades;

use Plover\Nest\Support\Facade;
use Plover\Nest\Cache\Contracts\Store;

/**
 * @method static Store 	store( ?string $name = null )
 * @method static Store 	driver( ?string $name = null )
 * @method static void 		extend( ?string $driver, $callback )
 * @method static mixed 	get( string $key )
 * @method static bool 		set( string $key, $value, int $ttl = 0 )
 * @method static bool 		forever( string $key, $value )
 * @method static bool 		delete( string $key )
 * @method static bool 		flush()
 * 
 * @since 1.0.1
 */
class Cache extends Facade {

	/**
	 * Get a cache item, if it does not exist, call the callback to fetch and cache it.
	 * 
	 * @param string $key
	 * @param mixed $ttl
	 * @param mixed $callback
	 */
	public static function remember( string $key, $ttl, $callback ) {
		$value = static::get( $key );
		if ( $value !== null ) {
			return $value;
		}

		$value = call_user_func( $callback );

		static::set( $key, $value, $ttl );

		return $value;
	}

	/**
	 * Get a cache item, if it does not exist, call the callback to cache it permanently.
	 * 
	 * @param string $key
	 * @param mixed $callback
	 */
	public static function rememberForever( string $key, $callback ) {
		$value = static::get( $key );
		if ( $value !== null ) {
			return $value;
		}

		$value = call_user_func( $callback );

		static::forever( $key, $value );

		return $value;
	}

	protected static function getFacadeAccessor() {
		return 'cache';
	}

}
