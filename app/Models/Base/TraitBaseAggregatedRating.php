<?php

namespace Modules\Market\app\Models\Base;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Modules\Market\app\Models\AggregatedRating;
use Modules\Market\app\Models\Rating;

trait TraitBaseAggregatedRating
{
    /**
     * @return HasMany
     * @todo: another trait?
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'model_id')->where('model', '=', $this->getAttributeModelIdent());
    }

    /**
     * @return HasMany
     */
    public function aggregatedRatings(): HasMany
    {
        return $this->hasMany(AggregatedRating::class, 'model_id')
            ->where('model', '=', $this->getAttributeModelIdent());
    }

    /**
     * @param  string  $subCode
     *
     * @return float
     */
    protected function getAggregatedRatingBySubCode(string $subCode): float
    {
        $rating = $this->aggregatedRatings->where('model_sub_code', '=', $subCode)->first();
        if ($rating) {
            $rating = $rating->value;
        } else {
            $rating = 0.0;
        }

        return $rating;
    }

    /**
     * @param  string  $subCode
     *
     * @return float
     */
    protected function getAggregatedRating5BySubCode(string $subCode): float
    {
        return $this->getAggregatedRatingBySubCode($subCode) / 20.0;
    }

    /**
     * $calcMap should be formatted wit multiple items each like:
     * [
     *      'weight' => 0.5, // double 0.0. to 1.0 where 1.0 is the heavy weight (above 1 are also valid, but we will prefer this convention)
     *      'value'  => 80,  // values from 0 to 100
     * ]
     *
     * @param  array  $calcMap
     *
     * @return float
     */
    protected function calculateRatings(array $calcMap): float
    {
        $sumFactor = 0.0;
        $sumValue = 0.0;

        foreach ($calcMap as $item) {
            // dont calc 0 values
            if ($item['value']) {
                $sumFactor += (float) $item['weight'];
                $sumValue += (float) $item['value'] * (float) $item['weight'];
            }
        }

        if ($sumFactor) {
            return $sumValue / $sumFactor;
        }

        return 0;
    }

    /**
     * The calculated rating from 0 to 100.
     * Should be overwritten!
     *
     * @return Attribute
     */
    protected function rating(): Attribute
    {
        //        // Example of 5 stars
        //        $calcMap = [
        //            [
        //                'weight' => 1.0, // heavy relevance
        //                'value'  => 100,
        //            ],
        //            [
        //                'weight' => 0.75, // 3/4 weight
        //                'value'  => 100,
        //            ],
        //            [
        //                'weight' => 0.5, // half weight
        //                'value'  => 100,
        //            ],
        //        ];

        //        // Example of 4.31 stars
        //        $calcMap = [
        //            [
        //                'weight' => 1.0, // heavy weight
        //                'value'  => 100,
        //            ],
        //            [
        //                'weight' => 0.75, // 3/4 weight
        //                'value'  => 75,
        //            ],
        //            [
        //                'weight' => 0.5, // half weight
        //                'value'  => 75,
        //            ],
        //        ];

        //        // Example of 2.89 stars
        //        $calcMap = [
        //            [
        //                'weight' => 1.0, // heavy weight
        //                'value'  => 20,
        //            ],
        //            [
        //                'weight' => 0.75, // 3/4 weight
        //                'value'  => 80,
        //            ],
        //            [
        //                'weight' => 0.5, // half weight
        //                'value'  => 100,
        //            ],
        //        ];

        // Example of invalid (a half star)
        $calcMap = [
            [
                'weight' => 1.0, // heavy weight
                'value'  => 10.0,
            ],
        ];

        return Attribute::make(get: fn() => $this->calculateRatings($calcMap));
    }

    /**
     * @return Attribute
     */
    protected function rating5(): Attribute
    {
        return Attribute::make(get: fn() => $this->rating / 20.0);
    }

    /**
     * Rating of current logged-in user related to this item.
     *
     * @param  string|null  $specificSubCode  if null, any existing sub code returned
     * @return ?Rating
     */
    public function getCurrentUserRating(string $specificSubCode = null): ?Rating
    {
        foreach ($this->ratingSubCodes as $subCode) {
            if (($specificSubCode === null) || ($specificSubCode === $subCode)) {
                $rating1 = Rating::with([])
                    ->where('model', '=', $this->getAttributeModelIdent())
                    ->where('model_sub_code', '=', $subCode)
                    ->where('user_id', '=', Auth::id())
                    ->where('model_id', '=', $this->getKey())
                    ->first();

                if ($rating1) {
                    return $rating1;
                }
            }
        }

        return null;
    }

    /**
     * @param  string|null  $specificSubCode  if null, any existing sub code returned true
     * @return bool
     */
    public function hasCurrentUserAlreadyRated(string $specificSubCode = null): bool
    {
        return (bool) $this->getCurrentUserRating($specificSubCode);
    }

}
