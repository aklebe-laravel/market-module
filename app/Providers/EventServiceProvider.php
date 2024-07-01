<?php

namespace Modules\Market\app\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\DeployEnv\app\Events\ImportRow;
use Modules\Market\app\Listeners\ImportRowAddress;
use Modules\Market\app\Listeners\ImportRowCategory;
use Modules\Market\app\Listeners\ImportRowProduct;
use Modules\Market\app\Listeners\ImportRowStore;
use Modules\Market\app\Listeners\ImportRowUser;
use Modules\WebsiteBase\app\Events\InitNavigation;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        InitNavigation::class => [
            \Modules\Market\app\Listeners\InitNavigation::class,
        ],
        ImportRow::class=> [
            ImportRowProduct::class,
            ImportRowCategory::class,
            ImportRowUser::class,
            ImportRowAddress::class,
            ImportRowStore::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
