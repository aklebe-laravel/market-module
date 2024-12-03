<?php

namespace Modules\Market\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Market\database\factories\AggregatedRatingFactory;

/**
 * @mixin IdeHelperAggregatedRating
 */
class AggregatedRating extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'aggregated_ratings';

    /**
     * You can use this instead of newFactory()
     * @var string
     */
    public static string $factory = AggregatedRatingFactory::class;

}
