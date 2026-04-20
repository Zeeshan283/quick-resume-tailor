<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\AiServiceInterface;
use App\Services\GenericAiProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AiServiceInterface::class, GenericAiProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
