<?php

namespace Modules\Market\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $table = 'aggregated_ratings';
}
