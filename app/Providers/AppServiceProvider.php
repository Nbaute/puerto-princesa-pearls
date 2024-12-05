<?php

namespace App\Providers;

use App\Models\Otp;
use App\Models\User;
use App\Observers\OtpObserver;
use App\Observers\UserObserver;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
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
        User::observe(UserObserver::class);
        Otp::observe(OtpObserver::class);

        Blade::directive('money', function ($amount) {
            return "<?php echo '&#8369;' . number_format($amount, 2); ?>";
        });
    }
}
