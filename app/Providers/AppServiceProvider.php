<?php

namespace App\Providers;

use App\Interfaces\ProfileServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Repositories\BettingRepository;
use App\Services\BettingService;
use App\Services\ProfileService;
use App\Services\UserService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(ProfileServiceInterface::class, ProfileService::class);
        $this->app->singleton(BettingService::class, function ($app) {
            return new BettingService(new BettingRepository());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
