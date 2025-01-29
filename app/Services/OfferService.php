<?php

namespace Modules\Market\app\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Market\app\Jobs\OfferStatusProcess;
use Modules\Market\app\Models\Offer;
use Modules\Market\app\Models\OfferItem;
use Modules\Market\app\Models\ShoppingCartItem;
use Modules\SystemBase\app\Services\Base\BaseService;
use Throwable;

class OfferService extends BaseService
{
    const array canEditStatusWhiteList = [
        Offer::STATUS_APPLIED,
        Offer::STATUS_REJECTED,
    ];

    const array statusActionMap = [
        'created_by_user_id'   => [
            Offer::STATUS_APPLIED  => [
                'form_actions' => [
                    'form' => 'defaults.cancel',
                    'offer-suspend',
                    'offer-create-binding',
                ],
            ],
            Offer::STATUS_REJECTED => [
                'form_actions' => [
                    're-offer',
                ],
            ],
        ],
        'addressed_to_user_id' => [
            Offer::STATUS_NEGOTIATION => [
                'form_actions' => [
                    'offer-reject',
                    're-offer',
                    'offer-accept',
                ],
            ],
            Offer::STATUS_REJECTED    => [
                'form_actions' => [
                    're-offer',
                ],
            ],
        ],
    ];

    /**
     * @param  Offer  $offer
     *
     * @return bool
     */
    public function disbandOfferToCartItems(Offer $offer): bool
    {
        $shoppingCart = app('market_settings')->getCurrentShoppingCart();

        /** @var OfferItem $offerItem */
        foreach ($offer->offerItems as $offerItem) {
            if ($cartItem = $shoppingCart->addProduct($offerItem->product->id)) {
                // Also update changes from offer ...
                $cartItem->update([
                    'product_name'       => $offerItem->product_name,
                    'price'              => $offerItem->price,
                    'currency_code'      => $offerItem->currency_code,
                    'payment_method_id'  => $offerItem->payment_method_id,
                    'shipping_method_id' => $offerItem->shipping_method_id,
                    'description'        => $offerItem->description,
                ]);
            } else {
                // @todo: error
            }

            $offerItem->delete();
        }

        $offer->delete();

        return true;
    }

    /**
     * @param  int  $userId
     * @param  Builder  $cartItemBuilder
     *
     * @return Offer|null
     * @throws Throwable
     */
    public function createOfferByCartItems(int $userId, Builder $cartItemBuilder): ?Offer
    {
        $offer = null;

        // do it in a transaction ...
        DB::transaction(function () use ($userId, $cartItemBuilder, &$offer) {

            $offerData = [
                'created_by_user_id' => $userId,
                'session_token'      => session()->getId(),
                'shared_id'          => $this->getNewSharedId(),
                'status'             => Offer::STATUS_APPLIED,
            ];

            $cartItemCollection = $cartItemBuilder->get();

            if (!$cartItemCollection->count()) {
                // @todo: error message
                return null;
            }

            /** @var ShoppingCartItem $cartItem */
            foreach ($cartItemCollection as $cartItem) {
                $offerData['addressed_to_user_id'] = $cartItem->product->user_id;
                $offerData['store_id'] = $cartItem->shoppingCart->store_id;
                break;
            }

            // create offer itself
            $offer = Offer::create($offerData);

            // create offer items
            foreach ($cartItemCollection as $cartItem) {

                // @todo: check product is salable ...

                //
                OfferItem::create([
                    'offer_id'           => $offer->id,
                    'product_id'         => $cartItem->product->id,
                    'product_name'       => $cartItem->product_name,
                    'price'              => $cartItem->price,
                    'currency_code'      => $cartItem->currency_code,
                    'payment_method_id'  => $cartItem->payment_method_id,
                    'shipping_method_id' => $cartItem->shipping_method_id,
                    'description'        => $cartItem->description,
                ]);
            }

            // remove cart items
            foreach ($cartItemCollection as $cartItem) {
                $cartItem->delete();
            }

        });

        return $offer;
    }

    /**
     * @return string
     *
     * @todo: need to check unique and avoid creation failure!
     */
    public function getNewSharedId(): string
    {
        return uniqid('js_soid_');
    }

    /**
     * Switch status and trigger specific things.
     *
     * @param  Offer  $offer
     * @param  string  $newStatus
     * @param  bool  $checkAlreadySet
     *
     * @return bool
     */
    public function switchStatus(Offer $offer, string $newStatus, bool $checkAlreadySet = true): bool
    {
        //        switch ($newStatus) {
        //            case OfferModel::STATUS_NEGOTIATION:
        //            {
        //                // @todo: Message (email or smth) to seller
        //
        //                // add queue job
        //                OfferProcess::dispatch($offer);
        //
        //                break;
        //            }
        //        }

        // Maybe nothing to do ...
        if ($checkAlreadySet && $offer->status === $newStatus) {
            return true;
        }

        //
        if ($offer->update(['status' => $newStatus])) {

            // add possible send mail job to queue
            OfferStatusProcess::dispatch($offer);

            return true;
        }

        return false;
    }

    /**
     * @param  Offer  $offer
     *
     * @return Offer
     */
    public function reOffer(Offer $offer): Offer
    {
        // create the new offer ...
        $offerData = $offer->toArray();
        unset($offerData['id']);
        unset($offerData['updated_at']);
        unset($offerData['created_at']);
        $offerData['prev_offer_id'] = $offer->id;
        $offerData['addressed_to_user_id'] = ($offerData['addressed_to_user_id'] != Auth::id()) ? $offerData['addressed_to_user_id'] : $offerData['created_by_user_id'];
        $offerData['created_by_user_id'] = Auth::id();
        $offerData['shared_id'] = $this->getNewSharedId();
        $offerData['status'] = Offer::STATUS_APPLIED;
        $newOffer = Offer::create($offerData);

        // offerItems ...
        foreach ($offer->offerItems as $offerItem) {
            $offerItemData = $offerItem->toArray();
            unset($offerItemData['id']);
            unset($offerItemData['updated_at']);
            unset($offerItemData['created_at']);
            $offerItemData['offer_id'] = $newOffer->id;
            $newOfferItem = OfferItem::create($offerItemData);
        }

        // change the old offer
        $offer->update([
            'status' => Offer::STATUS_CLOSED,
        ]);

        return $newOffer;
    }


    /**
     * Decides offer can still be edited in the given status (like description)
     *
     * @param  string  $status
     *
     * @return bool
     */
    public function canEditStatus(string $status): bool
    {
        return (in_array($status, self::canEditStatusWhiteList));
    }

    /**
     * Get 'created_by_user_id' or 'addressed_to_user_id' or '' depends on the equality of $userId
     *
     * @param  Offer  $offer
     * @param $userId
     *
     * @return string
     */
    public function getOwnerProperty(Offer $offer, $userId): string
    {
        if ($offer->created_by_user_id == $userId) {
            return 'created_by_user_id';
        }

        if ($offer->addressed_to_user_id == $userId) {
            return 'addressed_to_user_id';
        }

        return '';
    }

    /**
     * Get actions depends on the offer status.
     *
     * @param  Offer  $offer
     *
     * @return array
     */
    public function getOfferActions(Offer $offer): array
    {
        if ($userProp = $this->getOwnerProperty($offer, Auth::id())) {
            return data_get(self::statusActionMap, $userProp.'.'.$offer->status.'.form_actions', []);
        }

        return [];
    }

    /**
     * @param  Offer  $offer
     *
     * @return bool
     */
    public function hasOfferActions(Offer $offer): bool
    {
        return !!$this->getOfferActions($offer);
    }

}