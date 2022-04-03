<?php
namespace Tobexkee\LaravelOtp;

use Illuminate\Support\ServiceProvider;
use Tobexkee\LaravelOtp\Interfaces\OtpInterface;

class LaravelOtpServiceProvider extends ServiceProvider
{
    public $bindings = [
        OtpInterface::class => OtpService::class
    ];

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__."/../config/laravel-otp.php", 'laravel-otp');
    }
}
