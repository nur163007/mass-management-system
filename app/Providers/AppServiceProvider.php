<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register Blade directive for custom amount formatting
        \Blade::directive('formatAmount', function ($expression) {
            return "<?php echo App\Helpers\AmountHelper::formatCurrency($expression); ?>";
        });
    }
}
