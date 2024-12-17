<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Modules\Acl\app\Models\AclResource;
use Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable;


class OfferItem extends BaseDataTable
{
    use BaseMarketDataTable;

    /**
     * Minimum restrictions to allow this component.
     */
    public const array aclResources = [AclResource::RES_DEVELOPER, AclResource::RES_TRADER];

    /**
     * Overwrite to init your sort orders before session exists
     *
     * @return void
     */
    protected function initSort(): void
    {
        $this->setSortAllCollections('created_at', 'desc');
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
            'edit'   => 'data-table::livewire.js-dt.tables.columns.buttons.edit',
            'delete' => 'data-table::livewire.js-dt.tables.columns.buttons.delete',
        ];
    }

    /**
     * @return array|array[]
     */
    public function getColumns(): array
    {
        return [
            [
                'name'    => 'id',
                'label'   => __('ID'),
                'visible' => false,
                'format'  => 'number',
                'css_all' => 'text-muted font-monospace text-end w-5',
            ],
            [
                'name'    => 'product.user',
                'label'   => __('Owner'),
                'format'  => 'number',
                'view'    => 'data-table::livewire.js-dt.tables.columns.user',
                'css_all' => 'hide-mobile-show-lg text-center w-5',
            ],
            [
                'name'    => 'product.image_maker.final_thumb_small_url',
                'view'    => 'market::livewire.js-dt.tables.columns.cart-image',
                'label'   => __('Image'),
                'css_all' => 'text-center w-10',
            ],
            [
                'name'       => 'product_name',
                'label'      => __('Product'),
                'searchable' => true,
                'sortable'   => true,
                'options'    => [
                    'has_open_link' => $this->canEdit(),
                    'str_limit'     => 30,
                ],
                'css_all'    => 'w-50',
            ],
            [
                'name'    => 'paymentMethod.name',
                'label'   => __('Payment Method'),
                'view'    => 'market::livewire.js-dt.tables.columns.payment_method',
                'css_all' => 'hide-mobile-show-md',
            ],
            [
                'name'    => 'shippingMethod.name',
                'label'   => __('Shipping Method'),
                'view'    => 'market::livewire.js-dt.tables.columns.shipping_method',
                'css_all' => 'hide-mobile-show-md',
            ],
            [
                'name'    => 'price',
                'label'   => 'Price',
                'view'    => 'data-table::livewire.js-dt.tables.columns.price',
                'css_all' => 'text-muted text-end w-10',
            ],
            [
                'name'       => 'description',
                'visible'    => false,
                'searchable' => true,
            ],
        ];
    }

}
