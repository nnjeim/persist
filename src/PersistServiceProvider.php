<?php

namespace Nnjeim\Persist;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\Repository as CacheRepository;

class PersistServiceProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(CacheRepository $cacheRepository) {

        // Register the main class to use with the facade
        $this->app->singleton('persist', function () use ($cacheRepository) {

            return new PersistHelper($cacheRepository);
        });
    }
}
