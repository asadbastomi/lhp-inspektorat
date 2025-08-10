<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use TusPhp\Tus\Server as TusServer;

class TusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('tus-server', function ($app) {
            $server = new TusServer('file');
            $server
                ->setApiPath('/tus-upload')
                ->setUploadDir(storage_path('app/public/tus-uploads'));

            return $server;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
