<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Modules\Acl\app\Models\AclResource;
use Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable;


class ShoppingCartItem extends BaseDataTable
{
    use BaseMarketDataTable;

    /**
     * Minimum restrictions to allow this component.
     */
    public const array aclResources = [AclResource::RES_DEVELOPER, AclResource::RES_TRADER];

    /**
     * @var bool
     */
    public bool $removable = true;

    /**
     * @var array|true[]
     */
    public array $enabledCollectionNames = [
        self::COLLECTION_NAME_DEFAULT => true,
    ];

    /**
     * Overwrite to init your sort orders before session exists
     *
     * @return void
     */
    protected function initSort(): void
    {
        $this->setSortAllCollections('created_at', 'asc');
    }

    /**
     * Runs on every request, after the component is mounted or hydrated, but before any update methods are called
     *
     * @return void
     */
    protected function initBooted(): void
    {
        parent::initBooted();

        // reset commands to delete duplicate and use only delete button
        $this->rowCommands = [
            'delete' => 'data-table::livewire.js-dt.tables.columns.buttons.delete',
        ];

        $this->addBaseWebsiteMessageBoxes();
    }

    /**
     * @return array[]
     */
    public function getColumns(): array
    {
        return [
            [
                'name'       => 'id',
                'label'      => __('ID'),
                'visible'    => false,
                'format'     => 'number',
                'css_all'    => 'text-muted font-monospace text-end w-5',
                'searchable' => true,
                'sortable'   => true,
            ],
            [
                'name'    => 'product.user',
                'label'   => __('Owner'),
                'format'  => 'number',
                'view'    => 'data-table::livewire.js-dt.tables.columns.user',
                'css_all' => 'hide-mobile-show-sm text-center w-5',
                'icon'    => 'person',
            ],
            [
                'name'    => 'product.image_maker.final_thumb_small_url',
                'view'    => 'market::livewire.js-dt.tables.columns.cart-image',
                'label'   => __('Image'),
                'css_all' => 'text-center w-10',
                'icon'    => 'image',
            ],
            [
                'name'       => 'product_name',
                //                'value'   => function ($row) {
                //                    return $row->product_name . '<span class="bi bi-info"></span>';
                //                },
                'label'      => __('Product'),
                'options'    => [
                    'has_open_link' => $this->canEdit(),
                    'str_limit'     => 30,
                ],
                'css_all'    => 'w-50',
                'searchable' => true,
                'sortable'   => true,
                'icon'       => 'tag',
            ],
            [
                'name'    => 'paymentMethod.name',
                'label'   => __('Payment Method'),
                'view'    => 'market::livewire.js-dt.tables.columns.payment_method',
                'css_all' => 'hide-mobile-show-sm',
                'icon'    => 'wallet',
            ],
            [
                'name'    => 'shippingMethod.name',
                'label'   => __('Shipping Method'),
                'view'    => 'market::livewire.js-dt.tables.columns.shipping_method',
                'css_all' => 'hide-mobile-show-md',
                'icon'    => 'truck',
            ],
            [
                //                'name'       => 'price_formatted',
                'name'    => 'price',
                'label'   => __('Price'),
                'view'    => 'data-table::livewire.js-dt.tables.columns.price',
                'css_all' => 'text-muted text-end w-10',
                'icon'    => 'cash',
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
        $cart = app('market_settings')->getCurrentShoppingCart();

        $builder = parent::getBaseBuilder($collectionName);

        $builder->with([
            'product',
            'shoppingCart',
            'paymentMethod',
        ])->select('*')->whereRelation('shoppingCart', 'id', '=', $cart->getKey());

        return $builder;
    }

    /**
     * @param  mixed  $livewireId
     * @param  mixed  $itemId
     *
     * @return bool
     * @throws Exception
     */
    #[On('delete-item')]
    public function deleteItem(mixed $livewireId, mixed $itemId): bool
    {
        if (!parent::deleteItem($livewireId, $itemId)) {
            $this->addErrorMessage(__('Unable to remove cart item'));

            return false;
        }

        return true;
    }

}
