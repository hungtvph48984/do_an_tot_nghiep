<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\Category;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Chia sẻ biến $categories cho tất cả view trong clients.layouts.*
        View::composer('clients.layouts.*', function ($view) {
            $categories = Category::where('is_active', true)
                ->orderBy('id', 'desc')
                ->get();

            $view->with('categories', $categories);
        });

    }
}
