<?php

namespace App\Providers;

use App\Contracts\Services\EntityServiceInterface;
use App\Contracts\Services\ReservationServiceInterface;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\BooksController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\SubscribersController;
use App\Models\Book;
use App\Models\Reservation;
use App\Models\Subscriber;
use App\Services\Api\Books\BooksService;
use App\Services\Api\Reservation\DefaultReservationService;
use App\Services\Api\Subscribers\SubscribersService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use App\Models\Auth\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use App\Contracts\Auth\AuthServiceInterface;
use App\Services\Api\Auth\SanctumAuthService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('api-auth', SanctumAuthService::class);

        $this->app->when(AuthController::class)
            ->needs(AuthServiceInterface::class)
            ->give(SanctumAuthService::class);

        $this->app->when(BooksController::class)
            ->needs(EntityServiceInterface::class)
            ->give(function (Application $app) {
                return $app->make(BooksService::class)->setModel(resolve(Book::class));
            });

        $this->app->when(SubscribersController::class)
            ->needs(EntityServiceInterface::class)
            ->give(function (Application $app) {
                return $app->make(SubscribersService::class)->setModel(resolve(Subscriber::class));
            });

        $this->app->when(ReservationController::class)
            ->needs(ReservationServiceInterface::class)
            ->give(function (Application $app) {
                return $app->make(DefaultReservationService::class)->setModel(resolve(Reservation::class));
            });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
