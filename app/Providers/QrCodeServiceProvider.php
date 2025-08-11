<?php

namespace App\Providers;

use App\Services\QrCode;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class QrCodeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('qrcode', function () {
            return new QrCode();
        });

        // Register the facade alias
        $loader = AliasLoader::getInstance();
        $loader->alias('QrCode', \App\Facades\QrCode::class);
    }

    public function boot()
    {
        //
    }
}