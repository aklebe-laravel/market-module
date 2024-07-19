<?php

namespace Modules\Market\app\Providers;

use Modules\Market\app\Console\MarketProducts;
use Modules\Market\app\Models\MediaItem;
use Modules\Market\app\Models\User;
use Modules\Market\app\Services\ProductService;
use Modules\Market\app\Services\Setting;
use Modules\SystemBase\app\Providers\Base\ModuleBaseServiceProvider;

class MarketServiceProvider extends ModuleBaseServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected string $moduleName = 'Market';

    /**
     * @var string $moduleNameLower
     */
    protected string $moduleNameLower = 'market';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->app->singleton('market_settings', Setting::class);
        $this->app->singleton(ProductService::class);

        // Important to get Modules\WebsiteBase\Models\User when accessing app(\App\Models\User::class)
        $this->app->bind(\App\Models\User::class, User::class);

        // @todo: this should not needed if clean coded in websiteBase like app(\App\Models\User::class)
        $this->app->bind(\Modules\WebsiteBase\app\Models\User::class, User::class);

        // A powerful injection of MediaItem whenever \Modules\WebsiteBase\Models\MediaItem was targeted.
        // This way we override the whole class application wide.
        $this->app->bind(\Modules\WebsiteBase\app\Models\MediaItem::class, MediaItem::class);

        // This is also important to overwrite the user successfully!
        \Illuminate\Support\Facades\Config::set('auth.providers.users.model', User::class);

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(ScheduleServiceProvider::class);
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        $this->commands([
            MarketProducts::class
        ]);
    }
}
