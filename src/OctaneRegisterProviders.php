<?php

namespace Laravel\Octane;

use Illuminate\Contracts\Foundation\Application;
use Laravel\Octane\OctaneProviderRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bootstrap\RegisterProviders;

class OctaneRegisterProviders extends RegisterProviders
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        if (
            !$app->bound('config_loaded_from_cache') ||
            $app->make('config_loaded_from_cache') === false
        ) {
            $this->mergeAdditionalProviders($app);
        }

        $providerRepository = new OctaneProviderRepository($app, new Filesystem, $app->getCachedServicesPath());

        $app->registerConfiguredProviders($providerRepository);
    }
}
