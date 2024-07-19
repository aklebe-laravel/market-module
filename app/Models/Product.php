<?php

namespace Modules\Market\app\Models;

use Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents;
use Chelout\RelationshipEvents\Concerns\HasOneEvents;
use Chelout\RelationshipEvents\Traits\HasDispatchableEvents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Market\app\Models\Base\TraitBaseAggregatedRating;
use Modules\SystemBase\app\Models\Base\TraitModelAddMeta;
use Modules\SystemBase\app\Services\FileService;
use Modules\SystemBase\app\Services\SystemService;
use Modules\WebsiteBase\app\Models\Base\TraitAttributeAssignment;
use Modules\WebsiteBase\app\Models\Base\TraitBaseMedia;
use Modules\WebsiteBase\app\Models\MediaItem;
use Modules\WebsiteBase\app\Models\Store;


/**
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    use TraitAttributeAssignment, TraitBaseMedia, HasFactory, HasDispatchableEvents, HasOneEvents, HasBelongsToManyEvents, TraitBaseAggregatedRating, TraitModelAddMeta;

    /**
     * Default media type. Should be overwritten by delivered class.
     */
    const MEDIA_TYPE = MediaItem::MEDIA_TYPE_IMAGE;

    /**
     * Default media object type. Should be overwritten by delivered class.
     */
    const MEDIA_OBJECT_TYPE = MediaItem::OBJECT_TYPE_PRODUCT_IMAGE;

    /**
     * Zustand
     */
    const RATING_SUB_CODE_CONDITION = 'condition';

    /**
     * Öffentliche/allgemeine Produktbewertung
     */
    const RATING_SUB_CODE_PUBLIC_PRODUCT = 'public_product';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * appends will be filled dynamically for this instance by ModelWithAttributesLoaded
     *
     * @var array
     */
    protected $appends = [
        'extra_attributes'
    ];

    /**
     * @var array|string[]
     */
    protected array $ratingSubCodes = [
        self::RATING_SUB_CODE_CONDITION,
        self::RATING_SUB_CODE_PUBLIC_PRODUCT,
    ];

    /**
     * @var string
     */
    protected $table = 'products';

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
     * Override this instead of declare $appends with all parent declarations.
     *
     * @return array|string[]
     */
    protected function getArrayableAppends()
    {
        return parent::getArrayableAppends() + [
                'image_maker',
                'price',
                'price_formatted',
                'rating',
                'rating5',
                'rating5_'.self::RATING_SUB_CODE_CONDITION,
                'rating5_'.self::RATING_SUB_CODE_PUBLIC_PRODUCT,
                'salable',
            ];
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
        $images = $this->images()->where('object_type', MediaItem::OBJECT_TYPE_PRODUCT_IMAGE);

        if ($contentCode) {
            $images->where(function (Builder $b) use ($contentCode, $forceAny) {
                $b->where('media_item_product.content_code', '=',
                    $contentCode); // @todo: content_code is pivot column but pivot_content_code is not working
                if ($forceAny) {
                    // also list no marked items
                    $b->orWhereNull('media_item_product.content_code'); // @todo: content_code is pivot column but pivot_content_code is not working
                }
            });
            if ($forceAny) {
                // order by content_code at top to get the proper items by first()
                $images->orderByPivot('content_code', 'desc');
            }
        }

        return $images;
    }

    /**
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    /**
     * @return BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * @return BelongsTo
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * @return BelongsTo
     */
    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(static::$userClassName);
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function offerItems(): HasMany
    {
        return $this->hasMany(OfferItem::class);
    }

    protected function priceFormatted(): Attribute
    {
        return Attribute::make(get: function ($value, $attributes) {
            return app('system_base')->getPriceFormatted($this->price, $this->getExtraAttribute('currency', ''),
                $this->paymentMethod?->code ?? '');
        });
    }

    protected function price(): Attribute
    {
        return Attribute::make(get: function ($value, $attributes) {
            return (float) $this->getExtraAttribute('price', 0);
        });
    }

    protected function rating5Condition(): Attribute
    {
        return Attribute::make(get: fn() => $this->getAggregatedRating5BySubCode(self::RATING_SUB_CODE_CONDITION));
    }

    protected function rating5PublicProduct(): Attribute
    {
        return Attribute::make(get: fn() => $this->getAggregatedRating5BySubCode(self::RATING_SUB_CODE_PUBLIC_PRODUCT));
    }

    /**
     * The calculated rating from 0 to 100.
     *
     * @return Attribute
     */
    protected function rating(): Attribute
    {
        $calcMap = [
            [
                'weight' => 1.0,
                'value'  => $this->getAggregatedRatingBySubCode(self::RATING_SUB_CODE_CONDITION),
            ],
            [
                'weight' => 0.75,
                'value'  => $this->getAggregatedRatingBySubCode(self::RATING_SUB_CODE_PUBLIC_PRODUCT),
            ],
        ];

        return Attribute::make(get: fn() => $this->calculateRatings($calcMap));
    }

    protected function salable(): Attribute
    {
        return Attribute::make(get: function ($value, $attributes) {

            // @todo: cache, flat table, cron, queue

            // don't filter out $this->is_public
            if ($this->is_locked || !$this->is_enabled || !$this->user_id) {
                return false;
            }

            // store_id
            if (!($this->store_id == app('website_base_settings')->getStore()->getKey())) {
                return false;
            }

            // limited by timespan: started_at, expired_at
            if ($this->started_at || $this->expired_at) {
                $now = date(SystemService::dateIsoFormat8601);
                if ($this->started_at) {
                    if ($now < $this->started_at) {
                        return false;
                    }
                }
                if ($this->expired_at) {
                    if ($now >= $this->expired_at) {
                        return false;
                    }
                }
            }

            $isSalable = true;

            // @todo: filter products in closed offers (flat table?)

            return $isSalable;
        }

        );
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
    public function scopeFrontendItems(Builder $query)
    {
        return $query->where(function (Builder $q) {
            $q->where('is_locked', false);
            $q->where('is_enabled', true);
            $q->where('is_public', true);
            $q->where('store_id', app('website_base_settings')->getStore()->getKey());
            $now = date(SystemService::dateIsoFormat8601);
            $q->where(function (Builder $q2) use ($now) {
                $q2->whereNull('started_at')->orWhere('started_at', '<=', $now);
            });
            $q->where(function (Builder $q2) use ($now) {
                $q2->whereNull('expired_at')->orWhere('expired_at', '>', $now);
            });
        });
    }

    /**
     * After replicated/duplicated/copied
     * but before save()
     *
     * @return void
     */
    public function afterReplicated(Model $fromItem)
    {
        $this->is_public = false;
        $this->name = __('New').' '.$this->name.' '.uniqid();
        $this->web_uri = FileService::sanitize($this->name).'_'.uniqid('product_');
    }

    /**
     * Returns relations to replicate.
     *
     * @return array
     */
    public function getReplicateRelations(): array
    {
        return ['mediaItems', 'categories'];
    }

    /**
     * Adjust existing product if needed.
     *
     * @return bool true if save() is needed
     */
    public function validateAndAdjustProperties(): bool
    {
        $changed = false;

        if (!$this->web_uri) {
            $this->web_uri = uniqid('product_');
            $changed = true;
        }

        // $settings = app('market_settings');
        // if (!$this->payment_method_id) {
        //     $this->payment_method_id = $settings->getDefaultPaymentMethod()->getKey();
        //     $changed = true;
        // }
        //
        // if (!$this->shipping_method_id) {
        //     $this->shipping_method_id = $settings->getDefaultShippingMethod()->getKey();
        //     $changed = true;
        // }

        return $changed;
    }

}
