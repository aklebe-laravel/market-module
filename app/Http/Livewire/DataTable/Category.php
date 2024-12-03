<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Illuminate\Database\Eloquent\Builder;
use Modules\Acl\app\Models\AclResource;
use Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable;

class Category extends BaseDataTable
{
    /**
     *
     */
    public const aclResources = [
        AclResource::RES_DEVELOPER,
        AclResource::RES_MANAGE_CONTENT,
        AclResource::RES_MANAGE_DESIGN
    ];

    /**
     * Overwrite to init your sort orders before session exists
     * @return void
     */
    protected function initSort(): void
    {
        $this->setSortAllCollections('name', 'asc');
    }

    /**
     * @return array|array[]
     */
    public function getColumns(): array
    {
        return [
            [
                'name'       => 'id',
                'label'      => 'ID',
                'format'     => 'number',
                'searchable' => true,
                'sortable'   => true,
                'css_all'    => 'hide-mobile-show-lg text-muted font-monospace text-end w-5',
            ],
            [
                'name'       => 'is_enabled',
                'label'      => __('Enabled'),
                'view'       => 'data-table::livewire.js-dt.tables.columns.bool-red-green',
                'css_all'    => 'hide-mobile-show-md text-center w-5',
                'searchable' => true,
                'sortable'   => true,
            ],
            [
                'name'       => 'is_public',
                'label'      => __('Public'),
                'view'       => 'data-table::livewire.js-dt.tables.columns.bool-red-green',
                'css_all'    => 'hide-mobile-show-md text-center w-5',
                'searchable' => true,
                'sortable'   => true,
            ],
            [
                'name'    => 'image_maker.final_thumb_small_url',
                'view'    => 'market::livewire.js-dt.tables.columns.category-image',
                'label'   => __('Image'),
                'css_all' => 'hide-mobile-show-md text-center w-20',
            ],
            [
                'name'       => 'name',
                'label'      => __('Name'),
                'searchable' => true,
                'sortable'   => true,
                'options'    => [
                    'has_open_link' => $this->canEdit(),
                    'str_limit'     => 30,
                ],
                'css_all'    => 'w-50',
            ],
            [
                'name'       => 'description',
                'visible'    => false,
                'searchable' => true,
            ],
            [
                'name'       => 'meta_description',
                'visible'    => false,
                'searchable' => true,
            ],
            [
                'name'       => 'web_uri',
                'visible'    => false,
                'searchable' => true,
            ],
        ];
    }

    /**
     * Overwrite this to add filters
     *
     * @param  Builder  $builder
     * @param  string  $collectionName
     *
     * @return void
     */
    protected function extendBuilderByFilters(Builder $builder, string $collectionName): void
    {
        parent::extendBuilderByFilters($builder, $collectionName);

        // filter current store
        $builder->where('store_id', '=', app('website_base_settings')->getStore()->getKey());
    }

}
