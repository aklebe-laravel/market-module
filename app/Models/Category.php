<?php

namespace Modules\Market\app\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Modules\Market\database\factories\CategoryFactory;
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
    const string MEDIA_TYPE = MediaItem::MEDIA_TYPE_IMAGE;

    /**
     * Default media object type. Should be overwritten by delivered class.
     */
    const string MEDIA_OBJECT_TYPE = MediaItem::OBJECT_TYPE_CATEGORY_IMAGE;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'categories';

    /**
     * You can use this instead of newFactory()
     *
     * @var string
     */
    public static string $factory = CategoryFactory::class;

    /**
     * Multiple bootable model traits is not working
     * https://github.com/laravel/framework/issues/40645
     *
     * parent::construct() will not (or too early) be called without this construct()
     * so all trait boots also were not called.
     *
     * Important for \Modules\Acl\Models\Base\TraitBaseModel::bootTraitBaseModel
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
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
     *
     * @return Builder
     */
    public function scopeFrontendItems(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('is_enabled', true);
            $q->where('is_public', true);
            $q->where('store_id', app('website_base_settings')->getStoreId());
        });
    }

    /**
     * Overwrite this to get proper images by specific class!
     * Pivot tables can differ by class objects.
     *
     * @param  string  $contentCode
     * @param  bool    $forceAny  If true: Also select nullable pivots but order by pivots exists
     *
     * @return BelongsToMany
     * @todo: caching?
     *
     */
    public function getContentImages(string $contentCode = '', bool $forceAny = true): BelongsToMany
    {
        $images = $this->images()->categoryImages();
        //$this->prepareContentImagesBuilder($images, $contentCode, 'media_item_category', $forceAny);

        return $images;
    }


}
