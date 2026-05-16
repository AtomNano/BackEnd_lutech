<?php

namespace App\Providers;

use App\Models\Finance;
use App\Models\User;
use App\Models\Workspace;
use App\Observers\FinanceObserver;
use App\Observers\UserObserver;
use App\Observers\WorkspaceObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\WorkspaceContext::class, function () {
            return new \App\Services\WorkspaceContext();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Finance::observe(FinanceObserver::class);
        User::observe(UserObserver::class);
        Workspace::observe(WorkspaceObserver::class);
    }
}
