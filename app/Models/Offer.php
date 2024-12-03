<?php

namespace Modules\Market\app\Models;

use Illuminate\Contracts\Mail\Attachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Mail\Attachment;
use Modules\WebsiteBase\app\Models\Base\TraitBaseModel;

/**
 * @mixin IdeHelperOffer
 */
class Offer extends Model implements Attachable
{
    use HasFactory;
    use TraitBaseModel;

    /** @var string Angelegt, aber noch nicht erhoben */
    const STATUS_APPLIED = 'APPLIED';

    /** @var string In Verhandlung */
    const STATUS_NEGOTIATION = 'NEGOTIATION';

    /** @var string Abgelehnt */
    const STATUS_REJECTED = 'REJECTED';

    /** @var string Geschlossen */
    const STATUS_CLOSED = 'CLOSED';

    /** @var string Abgeschlossen */
    const STATUS_COMPLETED = 'COMPLETED';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'offers';

//    /**
//     * You can use this instead of newFactory()
//     * @var string
//     */
//    public static string $factory = OfferFactory::class;

    /**
     * @return BelongsTo
     */
    public function prevOffer(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    /**
     * @return HasMany
     */
    public function nextOffers(): HasMany
    {
        return $this->hasMany(self::class, 'prev_offer_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function offerItems(): HasMany
    {
        return $this->hasMany(OfferItem::class);
    }

    /**
     * @return BelongsTo
     */
    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function addressedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     */
    public function getPathToPdf(): string
    {
        return "not-implemented-yet.dpf";
    }

    /**
     * @return Attachment
     */
    public function toMailAttachment(): Attachment
    {
        return Attachment::fromPath($this->getPathToPdf());
    }

    /**
     * @param  array  $values
     * @return bool
     */
    public function updateAllProducts(array $values): bool
    {
        /** @var OfferItem $offerItem */
        foreach ($this->offerItems as $offerItem) {
            /** @var Product $product */
            if ($product = $offerItem->product) {
                $product->update($values);
            }
        }

        return true;
    }

}
