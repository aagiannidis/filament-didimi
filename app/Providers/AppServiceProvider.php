<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use TomatoPHP\FilamentUsers\Facades\FilamentUser;

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
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en','gr']); // also accepts a closure
        });
        
        FilamentUser::register([
            \Filament\Resources\RelationManagers\RelationManager::make() // Replace with your custom relation manager
        ]);
    }
}
