<?php

namespace Modules\Market\app\Services;

use Modules\Market\app\Models\Rating;
use Modules\SystemBase\app\Services\Base\BaseService;

class RatingService extends BaseService
{
    /**
     * Went to delete if $value is 0, but it results in chaotic increment ids.
     *
     * @param  string  $modelClass
     * @param  string  $modelSubCode
     * @param  int  $userId
     * @param  int  $modelId
     * @param  float  $value
     * @return Rating
     */
    public function saveRating(string $modelClass, string $modelSubCode, int $userId, int $modelId,
        float $value): Rating
    {
        $rating = Rating::updateOrCreate([
            'model'          => $modelClass,
            'model_sub_code' => $modelSubCode,
            'user_id'        => $userId,
            'model_id'       => $modelId,
        ], [
            'value' => $value,
        ]);

        return $rating;
    }
}