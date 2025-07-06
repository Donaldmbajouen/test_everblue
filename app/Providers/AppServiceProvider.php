<?php

namespace App\Providers;

use App\Models\Language;
use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );

        $this->app->singleton(
            LogoutResponse::class,
            \App\Http\Responses\LogoutResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $locales = Language::where('is_active', true)->pluck('code')->toArray();
            $switch
                ->locales($locales)
                ->flags([
                    'ar' => asset('images/flags/arabic.svg'),
                    'en' => asset('images/flags/english.png'),
                    'fr' => asset('images/flags/france.png'),
                    'de' => asset('images/flags/german.png'),
                    'es' => asset('images/flags/spain.png'),
                    'pt' => asset('images/flags/portuguese.png'),
                    'it' => asset('images/flags/italian.png'),
                    'ru' => asset('images/flags/russian.png'),
                    'tr' => asset('images/flags/turkish.png'),
                    'zh' => asset('images/flags/china.png'),
                    'vi' => asset('images/flags/vietnamese.png'),
                    'pl' => asset('images/flags/polish.png'),
                ])
                ->outsidePanelPlacement(Placement::TopRight)
                ->visible(outsidePanels: true)
                ->outsidePanelRoutes([
                    'auth.login',
                    'auth.register',
                    'auth.password-reset',
                ]);
        });

        Model::unguard();
    }
}
