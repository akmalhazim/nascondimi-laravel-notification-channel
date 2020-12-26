<?php

namespace NotificationChannels\Nascondimi;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class NascondimiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->app->when(NascondimiChannel::class)
            ->needs(Nascondimi::class)
            ->give(static function () {
                return new Nascondimi(
                    config('services.nascondimi.token'),
                    app(HttpClient::class),
                    config('services.nascondimi.base_uri')
                );
            });
    }
}
