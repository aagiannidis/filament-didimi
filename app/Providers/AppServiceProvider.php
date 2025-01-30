<?php

namespace App\Providers;

use Filament\Support\Assets\Css;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use TomatoPHP\FilamentUsers\Facades\FilamentUser;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Guava\FilamentKnowledgeBase\Filament\Panels\KnowledgeBasePanel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        KnowledgeBasePanel::configureUsing(
            fn(KnowledgeBasePanel $panel) => $panel
                ->viteTheme('resources/css/filament/knowledge-base/theme.css') // your filament vite theme path here
            //->disableBreadcrumbs()
            //->disableBackToDefaultPanelButton()
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en', 'gr'])
                ->displayLocale('en')
                //->visible(outsidePanels: true)
                ->excludes([
                    //'knowledge-base'
                ]);
        });

        FilamentUser::register([
            \Filament\Resources\RelationManagers\RelationManager::make() // Replace with your custom relation manager
        ]);

        // \Filament\Support\Facades\FilamentAsset::register([
        //     Css::make('flat-icons', 'https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css'),
        // ]);
    }
}
