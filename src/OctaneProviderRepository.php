<?php

namespace Laravel\Octane;

use Illuminate\Foundation\ProviderRepository;

class OctaneProviderRepository extends ProviderRepository
{
    /**
     * Register the application service providers.
     *
     * @param  array  $providers
     * @return void
     */
    public function load(array $providers)
    {
        $manifest = $this->loadManifest();

        // First we will load the service manifest, which contains information on all
        // service providers registered with the application and which services it
        // provides. This is used to know which services are "deferred" loaders.
        if ($this->shouldRecompile($manifest, $providers)) {
            $manifest = $this->compileManifest($providers);
        }

        // Next, we will register events to load the providers for each of the events
        // that it has requested. This allows the service provider to defer itself
        // while still getting automatically loaded when a certain event occurs.
        foreach ($manifest['when'] as $provider => $events) {
            $this->registerLoadEvents($provider, $events);
        }

        // We will register eagerly loaded providers, just like base ProviderRepository,
        // except we will leave out the ones specified in op_service_providers array.
        foreach ($manifest['eager'] as $provider) {
            if (!in_array($provider, $this->app->make('config')->get('octane.op_service_providers', []))) {
                $this->app->register($provider);
            }
        }

        $this->app->addDeferredServices($manifest['deferred']);
    }
}
