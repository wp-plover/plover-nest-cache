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
	 * @param Container $conatiner
	 */
	public function __construct( Container $conatiner ) {
		$this->conatiner = $conatiner;

		// Default memeory cache driver
		$this->extend( 'memory', function () {
			return new \Plover\Nest\Cache\Stores\MemeoryStore();
		} );
	}

    /**
     * Same as driver method
     * 
     * @param mixed $name
     * @return Store
     */
	public function store( ?string $name = null ): Store {
		return $this->driver( $name );
	}

	/**
	 * Get cache driver
	 * 
	 * @param mixed $name
	 * @return Store
	 */
	public function driver( ?string $name = null ): Store {
		$name = $name ?: $this->getDefaultDriver();
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
	 * @return Store
	 */
	protected function createDriver( string $name ): Store {
		if ( isset( $this->creators[ $name ] ) ) {
			return call_user_func( $this->creators[ $name ], $this->conatiner );
		}

		throw new \InvalidArgumentException( $name );
	}

	/**
	 * Get default driver
	 * 
	 * @return string
	 */
	protected function getDefaultDriver(): string {
		$config = $this->conatiner->get( 'config' );
		if ( $config ) {
			return $config->get( 'cache.default', 'memory' );
		}

		return 'memory';
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
