<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // pastikan tautan storage/public dibuat secara otomatis pada saat aplikasi di-boot
        // sehingga file yang diunggah ke disk "public" dapat diakses melalui url asset('storage/...).
        if (!\Illuminate\Support\Facades\File::exists(public_path('storage'))) {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
        }
    }
}
