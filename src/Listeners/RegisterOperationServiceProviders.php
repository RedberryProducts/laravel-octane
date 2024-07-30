<?php

namespace Laravel\Octane\Listeners;

class RegisterOperationServiceProviders
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     */
    public function handle($event): void
    {
        $resolved_providers = [];

        foreach ($event->sandbox->make('config')->get('octane.op_service_providers', []) as $provider) {
            $resolved_providers[] = $event->sandbox->register($provider, boot: false);
        }

        // After all service providers have been registered, we will boot them.
        foreach ($resolved_providers as $provider) {
            $event->sandbox->bootProvider($provider);
        }
    }
}
