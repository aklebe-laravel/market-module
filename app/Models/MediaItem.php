<?php

namespace Modules\Market\app\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\WebsiteBase\database\factories\MediaItemFactory;

/**
 * @mixin IdeHelperMediaItem
 */
class MediaItem extends \Modules\WebsiteBase\app\Models\MediaItem
{
    /**
     * You can use this instead of newFactory()
     *
     * @var string
     */
    public static string $factory = MediaItemFactory::class;

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}