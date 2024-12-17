<?php

namespace Modules\Market\app\Listeners;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Modules\Market\app\Models\Base\TraitBaseAggregatedRating;
use Modules\Market\app\Models\MediaItem;
use Modules\Market\app\Models\Product;
use Modules\Market\app\Models\User;
use Modules\WebsiteBase\app\Events\ModelWithAttributesDeleting as ModelWithAttributesDeletingEvent;
use Modules\WebsiteBase\app\Models\Store;
use Modules\WebsiteBase\app\Services\MediaService;

class ModelWithAttributesDeleting
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ModelWithAttributesDeletingEvent  $event
     *
     * @return void
     */
    public function handle(ModelWithAttributesDeletingEvent $event): void
    {
        /** @var Model $model */
        $model = $event->model;

        // Delete product specific relations ...
        if ($model instanceof Product) {
            Log::info(sprintf("Deleting Product and relations %s", $model->getKey()), [__METHOD__]);
            /** @var MediaService $mediaService */
            $mediaService = app('website_base_media');

            // Delete media items ...
            /** @var MediaItem $image */
            foreach ($model->images as $image) {
                // delete media item if no longer linked than this product
                if ($image->products->count() <= 1) {
                    $mediaService->deleteMediaItem($image);
                }
            }

            // Unset Shopping Cart product_id ...

            // Unset Offer product_id ...

        } // Delete user specific relations ...
        elseif ($model instanceof User) {
            Log::info(sprintf("Deleting User and relations %s", $model->getKey()), [__METHOD__]);
            /** @var MediaService $mediaService */
            $mediaService = app('website_base_media');

            // Delete media items ...
            /** @var MediaItem $image */
            foreach ($model->images as $image) {
                // delete media item if no longer linked than this user
                if ($image->users->count() <= 1) {
                    $mediaService->deleteMediaItem($image);
                }
            }

            // Delete Acl group links ...
            $model->aclGroups()->detach(); // all

            // Delete notification events
            $model->notificationEvents()->detach();

            // Delete Addresses ...
            $model->addresses()->delete();

            // Delete Shopping Carts ...
            $model->shoppingCarts()->delete(); // items cascading on delete

            // Delete Stores ...
            $model->stores()->delete();

            // Delete Products ...
            $model->products()->delete();

            // Delete Offers ...
            $model->offersAddressedMe()->delete();
            $model->offersAddressedOthers()->delete();

            // Delete Tokens ...
            $model->tokens()->delete();

            // Delete Sessions ...
            //$model->sessions()->delete();

            // Delete Parent Reputations ...
            $model->parentReputations()->detach();

        } // Delete store specific relations ...
        elseif ($model instanceof Store) {
            Log::info(sprintf("Deleting Store and relations %s", $model->getKey()), [__METHOD__]);
        }

        $systemService = app('system_base');
        // Delete ratings if any ...
        if ($systemService->hasInstanceClassOrTrait($model, TraitBaseAggregatedRating::class)) {
            Log::debug("Deleting rating relations for \"{get_class($model)}\" {$model->getKey()}");
            $model->ratings()->delete();
            $model->aggregatedRatings()->delete();
        }

    }
}
