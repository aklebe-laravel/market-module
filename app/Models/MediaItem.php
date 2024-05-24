<?php

namespace Modules\Market\app\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Market\Models\IdeHelperMediaItem;


/**
 * @mixin IdeHelperMediaItem
 */
class MediaItem extends \Modules\WebsiteBase\app\Models\MediaItem
{
    /**
     * @return BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}