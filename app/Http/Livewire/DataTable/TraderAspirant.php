<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Exception;
use Illuminate\Database\Eloquent\Builder;

class TraderAspirant extends Trader
{
    /**
     * @var string
     */
    public string $description = 'dt_trader_aspirant_description';

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
                'options'    => [
                    'has_open_link' => $this->canManage(),
                    'str_limit'     => 30,
                ],
                'css_all'    => 'w-50',
                'view'       => 'market::livewire.js-dt.tables.columns.default-with-rating',
                'icon'       => 'person',
            ],
            [
                'name'       => 'extra_attributes.user_register_hint',
                'label'      => __('Registered Hint'),
                'searchable' => false,
                'sortable'   => false,
                'visible'    => $this->canManage(),
                'css_all'    => 'hide-mobile-show-md',
                'icon'       => 'card-text',
            ],
            [
                'name'       => 'created_at',
                'label'      => __('Created At'),
                'css_all'    => 'w-10',
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

        return $moduleClass->withNoAclResources(['trader'])->frontendItems();
    }

}
