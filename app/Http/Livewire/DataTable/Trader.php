<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Modules\Acl\app\Models\AclResource;

class Trader extends User
{
    /**
     * Minimum restrictions to allow this component.
     */
    public const array aclResources = [AclResource::RES_DEVELOPER, AclResource::RES_ADMIN, AclResource::RES_TRADER];

    /**
     * @var string
     */
    public string $description = 'dt_trader_description';

    /**
     * Determine whether command has buttons like "add new row" in header.
     *
     * @var bool
     */
    public bool $canAddRow = false;

    /**
     * Enables checkbox and bulk actions.
     *
     * @var bool
     */
    public bool $selectable = false;

    /**
     * Overwrite to init your sort orders before session exists
     *
     * @return void
     */
    protected function initSort(): void
    {
        $this->setSortAllCollections('name', 'asc');
    }

    /**
     * Runs on every request, after the component is mounted or hydrated, but before any update methods are called
     *
     * @return void
     */
    protected function initBooted(): void
    {
        parent::initBooted();

        $this->rowCommands = [
            'rate_user' => 'market::livewire.js-dt.tables.columns.buttons.rate-user',
            ...$this->rowCommands,
        ];
    }

    /**
     * @return array[]
     */
    public function getColumns(): array
    {
        return [
            [
                'name'       => 'last_visited_at',
                'label'      => __('Online'),
                'css_all'    => 'text-center w-5',
                'view'       => 'data-table::livewire.js-dt.tables.columns.online',
                'searchable' => true,
                'sortable'   => true,
                'icon'       => 'globe',
            ],
            [
                'name'    => 'id',
                'label'   => __('Link'),
                'format'  => 'number',
                'css_all' => 'text-center',
                'view'    => 'data-table::livewire.js-dt.tables.columns.user',
                'icon'    => 'link',
            ],
            [
                'name'       => 'name',
                'label'      => __('Name'),
                'searchable' => true,
                'sortable'   => true,
                'css_all'    => 'w-50',
                'view'       => 'market::livewire.js-dt.tables.columns.default-with-user-rating',
                'icon'       => 'tag',
            ],
            [
                'name'       => 'created_at',
                'label'      => __('Created'),
                'css_all'    => 'hide-mobile-show-sm w-10',
                'view'       => 'data-table::livewire.js-dt.tables.columns.datetime-since',
                'searchable' => true,
                'sortable'   => true,
                'icon'       => 'clock-history',
            ],
        ];
    }

    /**
     * The base builder before all filter manipulations.
     * Usually used for all collections (default, selected, unselected), but can overwritten.
     *
     * @param  string  $collectionName
     *
     * @return Builder|null
     * @throws Exception
     */
    public function getBaseBuilder(string $collectionName): ?Builder
    {
        $moduleClass = app('system_base')->getEloquentModel($this->getEloquentModelName());

        return $moduleClass->withAclResources(['trader'])->frontendItems();
    }

}
