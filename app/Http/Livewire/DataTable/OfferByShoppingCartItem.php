<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Modules\Market\app\Services\OfferService;
use Throwable;


class OfferByShoppingCartItem extends ShoppingCartItem
{
    /**
     * Can be overwritten and should if class names differ.
     *
     * @var string
     */
    public string $modelName = 'ShoppingCartItem';

    /**
     * @var string
     */
    public string $footerActions = 'market::inc.offers.actions-cart-items';

    /**
     * @var array|true[]
     */
    public array $enabledCollectionNames = [
        // self::COLLECTION_NAME_DEFAULT          => true,
        self::COLLECTION_NAME_SELECTED_ITEMS => true,
        // self::COLLECTION_NAME_UNSELECTED_ITEMS => true,
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

        $this->rowCommands = [
            'edit'   => 'data-table::livewire.js-dt.tables.columns.buttons.edit',
            'delete' => 'data-table::livewire.js-dt.tables.columns.buttons.delete',
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
        $builder = parent::getBaseBuilder($collectionName);
        $builder->with([
            'product',
            'shoppingCart',
            'paymentMethod',
        ])->select('*');

        return $builder;
    }

    /**
     * @param $userId
     *
     * @return void
     * @throws Throwable
     */
    #[On('create-offer-to-user')]
    public function createOfferToUser($userId): void
    {
        //        $this->checkLivewireId()
        if ($this->getUserId() !== (int) $userId) {
            return;
        }

        $builder = $this->getBuilder(self::COLLECTION_NAME_SELECTED_ITEMS);

        /** @var OfferService $offerService */
        $offerService = app(OfferService::class);
        if ($offer = $offerService->createOfferByCartItems(Auth::id(), $builder)) {

            $this->redirectRoute('manage-data', ['modelName' => 'Offer', 'modelId' => $offer->shared_id]);
        } else {
            // @todo: error
        }
    }
}
