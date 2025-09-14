<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
<<<<<<< HEAD
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
=======

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
    public function register(): void
    {
        //
    }

<<<<<<< HEAD
    public function boot(): void
    {
        Paginator::useBootstrap(); 
=======
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
    }
}
