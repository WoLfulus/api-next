<?php

declare(strict_types=1);

namespace Directus\Providers;

use Illuminate\Config\Repository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

/**
 * Config provider.
 */
class ConfigProvider extends ServiceProvider
{
    /**
     * Service register.
     */
    public function register(): void
    {
        /** @var bool $debug */
        $debug = config('app.debug', false);

        // Do not load configs if it's cached.
        if (!$debug && App::configurationIsCached()) {
            return;
        }

        $this->mergeConfigFrom(
            __DIR__.'/../../config/directus.php',
            'directus'
        );

        /** @var Repository $config */
        $config = $this->app['config'];
        $config->set('directus', array_merge(
            require __DIR__.'/../../config/static.php',
            $config->get('directus', [])
        ));
    }

    /**
     * Service boot.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/directus.php' => config_path('directus.php'),
        ], [
            'directus',
            'directus-config',
        ]);
    }
}
