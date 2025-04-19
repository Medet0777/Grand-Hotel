<?php

namespace App\Providers;

use App\Contracts\UserContracts\UserRepositoryContract;
use App\Contracts\UserContracts\UserServiceContract;
use App\Repositories\UserRepository\UserRepository;
use App\Services\UserServices\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(UserServiceContract::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
