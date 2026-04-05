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

	protected static function getFacadeAccessor() {
		return 'cache';
	}

}
