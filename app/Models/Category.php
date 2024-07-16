<?php

namespace Modules\Market\app\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Modules\WebsiteBase\app\Models\Base\TraitAttributeAssignment;
use Modules\WebsiteBase\app\Models\Base\TraitBaseMedia;
use Modules\WebsiteBase\app\Models\MediaItem;

/**
 * @mixin IdeHelperCategory
 */
class Category extends Model
{
    use TraitAttributeAssignment, TraitBaseMedia, HasFactory;

    /**
     * Default media type. Should be overwritten by delivered class.
     */
    const MEDIA_TYPE = MediaItem::MEDIA_TYPE_IMAGE;

    /**
     * Default media object type. Should be overwritten by delivered class.
     */
    const MEDIA_OBJECT_TYPE = MediaItem::OBJECT_TYPE_CATEGORY_IMAGE;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * appends will be filled dynamically for this instance by ModelWithAttributesLoaded
     *
     * @var array
     */
    protected $appends = ['extra_attributes'];

    /**
     * @var string
     */
    protected $table = 'categories';

    /**
     * Multiple bootable model traits is not working
     * https://github.com/laravel/framework/issues/40645
     *
     * parent::construct() will not (or too early) be called without this construct()
     * so all trait boots also were not called.
     *
     * Important for \Modules\Acl\Models\Base\TraitBaseModel::bootTraitBaseModel
     */
    public function __construct()
    {
        parent::__construct();
    }

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
    public function frontendProducts(): BelongsToMany
    {
        return $this->products()->frontendItems();
    }

    /**
     * @return BelongsToMany
     */
    public function userProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->where('user_id', '=', Auth::id())->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_parent', 'parent_id', 'category_id')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_parent', 'category_id', 'parent_id')->withTimestamps();
    }

    public static function getBuilderFrontendItems(): Builder
    {
        return self::query()->frontendItems();
    }

    /**
     * scope frontendItems()
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeFrontendItems(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('is_enabled', true);
            $q->where('is_public', true);
            $q->where('store_id', app('website_base_settings')->getStore()->getKey());
        });
    }


}
