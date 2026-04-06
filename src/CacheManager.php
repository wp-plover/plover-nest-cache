<?php

namespace Plover\Nest\Cache;

use Plover\Nest\Container\Container;
use Plover\Nest\Cache\Contracts\Store;

/**
 * Cache manager
 * 
 * @since 1.0.0
 */
class CacheManager {

	/**
	 * Container instance
	 * 
	 * @var \Plover\Nest\Container\Container
	 */
	protected $conatiner;

	/**
	 * All initialized drivers
	 * 
	 * @var array
	 */
	protected $drivers = [];

	/**
	 * Cache driver creators
	 * 
	 * @var array
	 */
	protected $creators = [];

	/**
	 * Default cache driver
	 * 
	 * @var string
	 */
	protected $default_driver = 'memory';

	/**
	 * @param Container $conatiner
	 */
	public function __construct( Container $conatiner ) {
		$this->conatiner = $conatiner;

		// Default memory cache driver
		$this->extend( 'memory', function () {
			return new \Plover\Nest\Cache\Stores\MemoryStore();
		} );
		// WordPress transient cache driver
		$this->extend( 'wp-transient', function () {
			return new \Plover\Nest\Cache\Stores\WordPressTransientStore();
		} );
	}

	/**
	 * Set default driver
	 * 
	 * @param string $name
	 * @return void
	 */
	public function setDefaultDriver( string $name ) {
		$this->default_driver = $name;
	}

	/**
	 * Same as driver method
	 * 
	 * @param mixed $name
	 * @return CacheRepository
	 */
	public function store( ?string $name = null ): Store {
		return $this->driver( $name );
	}

	/**
	 * Get cache driver
	 * 
	 * @param mixed $name
	 * @return CacheRepository
	 */
	public function driver( ?string $name = null ): Store {
		$name = $name ?: $this->default_driver;
		if ( ! isset( $this->drivers[ $name ] ) ) {
			$this->drivers[ $name ] = $this->createDriver( $name );
		}
		return $this->drivers[ $name ];
	}

	/**
	 * Extend cache driver
	 * 
	 * @param string $driver
	 * @param $callback
	 * @return void
	 */
	public function extend( string $driver, $callback ): void {
		$this->creators[ $driver ] = $callback;
	}

	/**
	 * Create drive instance
	 * 
	 * @param string $name
	 * @throws \InvalidArgumentException
	 * @return CacheRepository
	 */
	protected function createDriver( string $name ): Store {
		if ( isset( $this->creators[ $name ] ) ) {
			$driver = call_user_func( $this->creators[ $name ], $this->conatiner );

			return new CacheRepository( $driver );
		}

		throw new \InvalidArgumentException( $name );
	}

	/**
	 * Magic method, proxy all store methods
	 * 
	 * @param mixed $method
	 * @param mixed $arguments
	 */
	public function __call( $method, $arguments ) {
		return $this->driver()->$method( ...$arguments );
	}
}
