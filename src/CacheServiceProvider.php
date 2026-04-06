<?php

namespace Plover\Nest\Cache;

use Plover\Nest\Support\ServiceProvider;

/**
 * @since 1.0.0
 */
class CacheServiceProvider extends ServiceProvider {

	/**
	 * @var array
	 */
	public $singletons = [
		\Plover\Nest\Cache\CacheManager::class,
	];

    /**
     * @var array
     */
	public $aliases = [
		'cache' => \Plover\Nest\Cache\CacheManager::class
	];
}
