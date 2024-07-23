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
        foreach ($event->sandbox->make('config')->get('octane.op_service_providers', []) as $provider) {
            $event->sandbox->register($provider);
        }
    }
}
