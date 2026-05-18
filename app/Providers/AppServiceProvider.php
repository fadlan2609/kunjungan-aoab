<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// Hapus atau komentari baris berikut
// use Illuminate\Database\Seeder;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // HAPUS atau KOMENTARI kode ini:
        // Seeder::guessFactoryNamesUsing(function () {
        //     return '';
        // });
    }
}