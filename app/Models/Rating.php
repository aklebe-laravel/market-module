<?php

namespace Modules\Market\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperRating
 */
class Rating extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

//    /**
//     * You can use this instead of newFactory()
//     * @var string
//     */
//    public static string $factory = RatingFactory::class;

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
