<?php

declare(strict_types=1);

namespace App\Providers;

use App\Clients\Contracts\DistributorClient;
use App\Clients\DistributorHttpClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DistributorClient::class, DistributorHttpClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
