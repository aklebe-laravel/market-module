<?php

namespace Modules\Market\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Market\app\Models\Offer;
use Modules\WebsiteBase\app\Services\SendNotificationService;

class OfferStatusProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Offer
     */
    public Offer $offer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Log::debug(sprintf("Handle offer (%s), status: %s.", $this->offer->id, $this->offer->status), [__METHOD__]);

        // Checking the new status ...
        switch ($this->offer->status) {
            case Offer::STATUS_APPLIED:
                {
                    // Log::debug('Doing nothing.', [$this->offer->status, __METHOD__]);
                }
                break;

            case Offer::STATUS_NEGOTIATION:
                {
                    /** @var SendNotificationService $sendNotificationService */
                    $sendNotificationService = app(SendNotificationService::class);
                    $sendNotificationService->sendNotificationConcern('market_offer_created',
                        $this->offer->addressedToUser, ['offer' => $this->offer]);
                }
                break;

            case Offer::STATUS_REJECTED:
                {
                    /** @var SendNotificationService $sendNotificationService */
                    $sendNotificationService = app(SendNotificationService::class);
                    $sendNotificationService->sendNotificationConcern('market_offer_rejected',
                        $this->offer->createdByUser, ['offer' => $this->offer]);
                }
                break;

            case Offer::STATUS_COMPLETED:
                {
                    // set all products is_locked = true
                    $this->offer->updateAllProducts(['is_locked' => true]);

                    /** @var SendNotificationService $sendNotificationService */
                    $sendNotificationService = app(SendNotificationService::class);
                    $sendNotificationService->sendNotificationConcern('market_offer_completed',
                        $this->offer->createdByUser, ['offer' => $this->offer]);
                }
                break;

        }

    }
}
