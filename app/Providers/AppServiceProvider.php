<?php

namespace App\Providers;


use App\Contracts\UserContracts\OtpServiceContract;
use App\Contracts\UserContracts\UserRepositoryContract;
use App\Contracts\UserContracts\UserServiceContract;
use App\Contracts\WishlistContracts\WishlistRepositoryContract;
use App\Contracts\WishlistContracts\WishlistServiceContract;

use App\Repositories\UserRepository\UserRepository;
use App\Repositories\WishlistRepository\WishlistRepository;
use App\Services\UserServices\OtpService;
use App\Services\UserServices\UserService;
use App\Services\WishlistServices\WishlistService;
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
        $this->app->bind(OtpServiceContract::class, OtpService::class);
        $this->app->bind(WishlistRepositoryContract::class, WishlistRepository::class);
        $this->app->bind(WishlistServiceContract::class, WishlistService::class);

        // Фасад Service
        $this->app->singleton('service', function ($app) {
            return new class($app) {
                protected $app;

                public function __construct($app)
                {
                    $this->app = $app;
                }

                public function user()
                {
                    return $this->app->make(UserServiceContract::class);
                }

                public function otp()
                {
                    return $this->app->make(OtpServiceContract::class);
                }

                public function wishlist()
                {
                    return $this->app->make(WishlistServiceContract::class);
                }
            };
        });

        // Фасад Repository
        $this->app->singleton('repository', function ($app) {
            return new class($app) {
                protected $app;

                public function __construct($app)
                {
                    $this->app = $app;
                }

                public function user()
                {
                    return $this->app->make(UserRepositoryContract::class);
                }

                public function wishlist()
                {
                    return $this->app->make(WishlistRepositoryContract::class);
                }

            };
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
