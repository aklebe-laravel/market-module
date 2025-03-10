<?php

namespace Modules\Market\app\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\DeployEnv\app\Events\ImportContent;
use Modules\DeployEnv\app\Events\ImportRow;
use Modules\Form\app\Events\InitFormElements;
use Modules\Market\app\Listeners\ImportContentAddress;
use Modules\Market\app\Listeners\ImportContentCategory;
use Modules\Market\app\Listeners\ImportContentProduct;
use Modules\Market\app\Listeners\ImportContentStore;
use Modules\Market\app\Listeners\ImportContentUser;
use Modules\Market\app\Listeners\ImportRowAddress;
use Modules\Market\app\Listeners\ImportRowCategory;
use Modules\Market\app\Listeners\ImportRowProduct;
use Modules\Market\app\Listeners\ImportRowStore;
use Modules\Market\app\Listeners\ImportRowUser;
use Modules\WebsiteBase\app\Events\InitNavigation;
use Modules\WebsiteBase\app\Events\ModelWithAttributesDeleting;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        InitNavigation::class              => [
            \Modules\Market\app\Listeners\InitNavigation::class,
        ],
        ModelWithAttributesDeleting::class => [
            \Modules\Market\app\Listeners\ModelWithAttributesDeleting::class,
        ],
        ImportRow::class                   => [
            ImportRowProduct::class,
            ImportRowCategory::class,
            ImportRowUser::class,
            ImportRowAddress::class,
            ImportRowStore::class,
        ],
        ImportContent::class               => [
            ImportContentProduct::class,
            ImportContentCategory::class,
            ImportContentUser::class,
            ImportContentAddress::class,
            ImportContentStore::class,
        ],
        InitFormElements::class            => [
            \Modules\Market\app\Listeners\InitFormElements::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
