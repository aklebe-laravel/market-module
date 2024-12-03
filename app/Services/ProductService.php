<?php

namespace Modules\Market\app\Services;

use Modules\Market\app\Jobs\AggregateRatingProcess;
use Modules\Market\app\Models\Product;
use Modules\SystemBase\app\Services\Base\BaseService;

class ProductService extends BaseService
{
    /**
     * @param  bool  $useQueue
     *
     * @return void
     */
    public function aggregateRatings(bool $useQueue = true): void
    {
        if ($useQueue) {
            AggregateRatingProcess::dispatch(Product::class);
        } else {
            AggregateRatingProcess::dispatchSync(Product::class);
        }
    }

}