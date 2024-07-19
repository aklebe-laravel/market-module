<?php

namespace Modules\Market\app\Services;

use Modules\Market\app\Jobs\AggregateRatingProcess;
use Modules\Market\app\Models\MediaItem;
use Modules\Market\app\Models\Product;
use Modules\SystemBase\app\Services\Base\BaseService;
use Modules\WebsiteBase\app\Services\MediaService;

class ProductService extends BaseService
{

    public function aggregateRatings(bool $useQueue = true): void
    {
        if ($useQueue) {
            AggregateRatingProcess::dispatch(Product::class);
        } else {
            AggregateRatingProcess::dispatchSync(Product::class);
        }
    }

    /**
     * Removes a product.
     *
     * @param  Product  $product
     * @return bool
     */
    public function deleteProduct(Product $product): bool
    {
        /** @var MediaService $mediaService */
        $mediaService = app(MediaService::class);

        /** @var MediaItem $image */
        foreach ($product->images as $image) {
            // delete media item if no longer linked than this product
            if ($image->products->count() <= 1) {
                $mediaService->deleteMediaItem($image);
            }
        }

        // delete product itself
        return (bool) $product->delete();
    }
}