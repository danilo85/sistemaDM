<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon; 
use Illuminate\Pagination\Paginator; 
use Illuminate\Support\Facades\App;
use App\Models\Orcamento;
use App\Observers\OrcamentoObserver;


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
        Carbon::setLocale(config('app.locale'));
        App::setLocale('pt_BR');
        Paginator::useTailwind(); // <-- Adicione esta linha
        
        // Registrar observers
        Orcamento::observe(OrcamentoObserver::class);
    }
}