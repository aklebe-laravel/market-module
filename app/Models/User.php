<?php

namespace Modules\Market\app\Models;

use Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents;
use Chelout\RelationshipEvents\Concerns\HasOneEvents;
use Chelout\RelationshipEvents\Traits\HasDispatchableEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Modules\Market\app\Models\Base\TraitBaseAggregatedRating;
use Modules\Market\database\factories\UserFactory;
use Modules\SystemBase\app\Models\Base\TraitModelAddMeta;
use Modules\WebsiteBase\app\Models\Base\TraitAttributeAssignment;
use Modules\WebsiteBase\app\Models\Base\TraitBaseMedia;
use Modules\WebsiteBase\app\Models\Base\UserTrait;
use Modules\WebsiteBase\app\Models\Store;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @mixin IdeHelperUser
 */
class User extends \Modules\WebsiteBase\app\Models\User
{
    // Traits Have to use redundant here, using in WebsiteUser is not enough!
    use HasFactory, Notifiable, TraitAttributeAssignment, TraitBaseMedia, UserTrait, HasDispatchableEvents, HasOneEvents, HasBelongsToManyEvents, TraitBaseAggregatedRating, TraitModelAddMeta;

    const string RATING_SUB_CODE_WELL_KNOWN = 'well_known';
    const string RATING_SUB_CODE_TRUST = 'trust';
    const string RATING_SUB_CODE_OFFER_SUCCESS = 'offer_success';

    /**
     * @var array|string[]
     */
    protected array $ratingSubCodes = [
        self::RATING_SUB_CODE_WELL_KNOWN,
        self::RATING_SUB_CODE_TRUST,
        self::RATING_SUB_CODE_OFFER_SUCCESS,
    ];

    /**
     * You can use this instead of newFactory()
     *
     * @var string
     */
    public static string $factory = UserFactory::class;

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

        $this->appends += [
            'rating',
            'rating5',
            'rating5_'.self::RATING_SUB_CODE_WELL_KNOWN,
            'rating5_'.self::RATING_SUB_CODE_TRUST,
            'rating5_'.self::RATING_SUB_CODE_OFFER_SUCCESS,
        ];
    }

    /**
     * @return HasMany
     */
    public function offersAddressedMe(): HasMany
    {
        return $this->hasMany(Offer::class, 'addressed_to_user_id');
    }

    /**
     * @return HasMany
     */
    public function offersAddressedOthers(): HasMany
    {
        return $this->hasMany(Offer::class, 'created_by_user_id');
    }

    /**
     * @return HasMany
     */
    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    /**
     * @return HasMany
     */
    public function shoppingCarts(): HasMany
    {
        return $this->hasMany(ShoppingCart::class);
    }

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return BelongsToMany
     */
    public function parentReputations(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'user_parent_reputations', 'user_id', 'parent_id')->withTimestamps()->withPivot('created_at');
    }

    /**
     * @return mixed
     */
    public function frontendProducts(): mixed
    {
        return $this->products()->frontendItems();
    }

    /**
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function crossSellingProducts(): mixed
    {
        return $this->frontendProducts()->take(app('website_base_config')->get('product.cross_selling.max_items', 12))->inRandomOrder();
    }

    /**
     * @return Attribute
     */
    protected function rating5WellKnown(): Attribute
    {
        return Attribute::make(get: fn() => $this->getAggregatedRating5BySubCode(self::RATING_SUB_CODE_WELL_KNOWN));
    }

    /**
     * @return Attribute
     */
    protected function rating5Trust(): Attribute
    {
        return Attribute::make(get: fn() => $this->getAggregatedRating5BySubCode(self::RATING_SUB_CODE_TRUST));
    }

    /**
     * @return Attribute
     */
    protected function rating5OfferSuccess(): Attribute
    {
        return Attribute::make(get: fn() => $this->getAggregatedRating5BySubCode(self::RATING_SUB_CODE_OFFER_SUCCESS));
    }

    /**
     * @return Attribute
     */
    protected function rating(): Attribute
    {
        $calcMap = [
            [
                'weight' => 0.5,
                'value'  => $this->getAggregatedRatingBySubCode(self::RATING_SUB_CODE_WELL_KNOWN),
            ],
            [
                'weight' => 0.8,
                'value'  => $this->getAggregatedRatingBySubCode(self::RATING_SUB_CODE_TRUST),
            ],
            [
                'weight' => 1.0,
                'value'  => $this->getAggregatedRatingBySubCode(self::RATING_SUB_CODE_OFFER_SUCCESS),
            ],
        ];

        return Attribute::make(get: fn() => $this->calculateRatings($calcMap));
    }

}
