<?php

namespace App\Providers;

use App\Models\News;
use App\Policies\NewsPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

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
        ini_set('upload_max_filesize', '0');
        ini_set('post_max_size', '0');
        ini_set('max_file_uploads', '50');

        RateLimiter::for('login', function (Request $request) {
            $limit = app()->environment('local') ? 30 : 5;
            return Limit::perMinute($limit)->by($request->ip());
        });

        Gate::policy(News::class, NewsPolicy::class);
    }
}
