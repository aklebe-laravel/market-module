<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Modules\Acl\app\Models\AclResource;
use Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable;
use Modules\Market\app\Models\Product as ProductModel;

class Product extends BaseDataTable
{
    use BaseMarketDataTable;

    /**
     * Minimum restrictions to allow this component.
     */
    public const array aclResources = [AclResource::RES_TRADER];

    /**
     * Minimum restrictions to allow this component.
     */
    public string $description = 'dt_own_product_description';

    /**
     * Runs on every request, after the component is mounted or hydrated, but before any update methods are called
     *
     * @return void
     */
    protected function initBooted(): void
    {
        parent::initBooted();

        $this->addBaseWebsiteMessageBoxes();
    }

    /**
     * @return void
     */
    protected function initFilters(): void
    {
        parent::initFilters();

        $this->addFilterElement('product_filter1', [
            'label'      => 'Filter',
            'default'    => app('system_base')::selectValueNoChoice,
            'position'   => 1700, // between elements rows and search
            'soft_reset' => true,
            'css_group'  => 'col-12 col-md-3 text-start',
            'css_item'   => '',
            'options'    => $this->getFilterOptionsForImages(),
            'view'       => 'data-table::livewire.js-dt.filters.default-elements.select',
        ]);
    }

    /**
     * Overwrite to init your sort orders before session exists
     *
     * @return void
     */
    protected function initSort(): void
    {
        $this->setSortAllCollections('updated_at', 'desc');
    }

    /**
     * @return array|array[]
     */
    public function getColumns(): array
    {
        return [
            [
                'name'       => 'id',
                'label'      => __('ID'),
                'searchable' => true,
                'sortable'   => true,
                'format'     => 'number',
                'css_all'    => 'hide-mobile-show-lg text-muted font-monospace text-end w-5',
            ],
            [
                'name'     => 'is_enabled',
                'label'    => __('Enabled'),
                'view'     => 'data-table::livewire.js-dt.tables.columns.bool-red-green',
                'css_all'  => 'hide-mobile-show-lg text-center w-5',
                'sortable' => true,
                'icon'     => 'check',
            ],
            [
                'name'     => 'is_public',
                'label'    => __('Public'),
                'view'     => 'data-table::livewire.js-dt.tables.columns.bool-red-green',
                'css_all'  => 'hide-mobile-show-lg text-center w-5',
                'sortable' => true,
                'icon'     => 'door-open',
            ],
            [
                'name'     => 'is_locked',
                'label'    => __('Unlocked'),
                'view'     => 'data-table::livewire.js-dt.tables.columns.bool-red-green',
                'css_all'  => 'hide-mobile-show-lg text-center w-5',
                'sortable' => true,
                'options'  => [
                    'negate' => true,
                ],
                'icon'     => 'lock',
            ],
            [
                'name'    => 'user',
                'label'   => __('User'),
                'view'    => 'data-table::livewire.js-dt.tables.columns.user',
                'css_all' => 'hide-mobile-show-md text-center w-5',
                'icon'    => 'person',
            ],
            [
                'name'    => 'image_maker.final_thumb_small_url',
                'view'    => 'market::livewire.js-dt.tables.columns.product-image',
                'label'   => __('Product Image'),
                'css_all' => 'hide-mobile-show-sm text-center w-10',
                'icon'    => 'image',
            ],
            [
                'name'       => 'name',
                'label'      => __('Name'),
                'searchable' => true,
                'sortable'   => true,
                'options'    => [
                    'has_open_link'     => $this->canEdit(),
                    'has_frontend_link' => true,
                    'str_limit'         => 30,
                ],
                'css_all'    => '',
                'view'       => 'market::livewire.js-dt.tables.columns.default-with-product-rating',
                'icon'       => 'tag',
            ],
            [
                'name'    => 'price_formatted',
                'label'   => __('Price'),
                'css_all' => 'hide-mobile-show-md text-muted text-end w-10',
                'icon'    => 'cash',
            ],
            [
                'name'       => 'updated_at',
                'label'      => __('Updated At'),
                'searchable' => true,
                'sortable'   => true,
                'view'       => 'data-table::livewire.js-dt.tables.columns.datetime-since',
                'css_all'    => 'hide-mobile-show-lg ',
                'icon'       => 'arrow-clockwise',
            ],
            [
                'name'       => 'description',
                'visible'    => false,
                'searchable' => true,
            ],
            [
                'name'       => 'short_description',
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
     * @param  string   $collectionName
     *
     * @return void
     */
    protected function extendBuilderByFilters(Builder $builder, string $collectionName): void
    {
        parent::extendBuilderByFilters($builder, $collectionName);

        // filter current store
        $builder->where('store_id', '=', app('website_base_settings')->getStoreId());
    }

    /**
     * @param $item
     *
     * @return bool
     */
    protected function isItemValid($item): bool
    {
        /** @var ProductModel $item */
        return $item->salable;
    }

    /**
     * @param $item
     *
     * @return bool
     */
    protected function isItemWarn($item): bool
    {
        /** @var ProductModel $item */
        return $item->is_test;
    }

    /**
     * The base builder before all filter manipulations.
     * Usually used for all collections (default, selected, unselected), but can be overwritten.
     *
     * @param  string  $collectionName
     *
     * @return Builder|null
     * @throws Exception
     */
    public function getBaseBuilder(string $collectionName): ?Builder
    {
        $builder = parent::getBaseBuilder($collectionName);
        if ($this->filterByParentOwner) {
            $builder = $builder->whereUserId($this->getUserId());
        }

        return $builder;
    }

}
