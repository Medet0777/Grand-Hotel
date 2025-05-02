<?php

namespace App\Providers;

use App\Contracts\BookingContracts\BookingRepositoryContract;
use App\Contracts\BookingContracts\BookingServiceContract;
use App\Contracts\HotelContracts\HotelRepositoryContract;
use App\Contracts\RoomContracts\RoomRepositoryContract;
use App\Contracts\RoomContracts\RoomServiceContract;
use App\Contracts\UserContracts\OtpServiceContract;
use App\Contracts\UserContracts\UserRepositoryContract;
use App\Contracts\UserContracts\UserServiceContract;
use App\Contracts\WishlistContracts\WishlistRepositoryContract;
use App\Contracts\WishlistContracts\WishlistServiceContract;
use App\Repositories\BookingRepository;
use App\Repositories\HotelRepository;
use App\Repositories\RoomRepository;
use App\Repositories\UserRepository;
use App\Repositories\WishlistRepository;
use App\Services\BookingServices\BookingService;
use App\Services\RoomServices\RoomService;
use App\Services\UserServices\AuthService;
use App\Services\UserServices\OtpService;
use App\Services\UserServices\UserService;
use App\Services\WishlistServices\WishlistService;
use Illuminate\Support\ServiceProvider;
use App\Contracts\HotelContracts\HotelServiceContract;
use App\Services\HotelServices\HotelService;

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
        $this->app->bind(HotelRepositoryContract::class, HotelRepository::class);
        $this->app->bind(HotelServiceContract::class, HotelService::class);
        $this->app->bind(RoomRepositoryContract::class, RoomRepository::class);
        $this->app->bind(RoomServiceContract::class, RoomService::class);
        $this->app->bind(AuthService::class, AuthService::class);
        $this->app->bind(BookingRepositoryContract::class, BookingRepository::class);
        $this->app->bind(BookingServiceContract::class, BookingService::class);

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

                public function auth()
                {
                    return $this->app->make(AuthService::class);
                }

                public function otp()
                {
                    return $this->app->make(OtpServiceContract::class);
                }

                public function wishlist()
                {
                    return $this->app->make(WishlistServiceContract::class);
                }
                public function hotel()
                {
                    return $this->app->make(HotelServiceContract::class);
                }

                public function room()
                {
                    return $this->app->make(RoomServiceContract::class);
                }

                public function booking()
                {
                    return $this->app->make(BookingServiceContract::class);
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

                public function hotel()
                {
                    return $this->app->make(HotelRepositoryContract::class);
                }

                public function room()
                {
                    return $this->app->make(RoomRepositoryContract::class);
                }
                public function booking()
                {
                    return $this->app->make(BookingRepositoryContract::class);
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
