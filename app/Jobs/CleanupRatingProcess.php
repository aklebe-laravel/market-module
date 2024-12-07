<?php

namespace Modules\Market\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Market\app\Models\AggregatedRating;
use Modules\Market\app\Models\Rating;

class CleanupRatingProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        // all ratings - fifo
        Rating::with([])->orderBy('id')->chunk(500, function ($ratings) {
            /** @var Rating $rating */
            foreach ($ratings as $rating) {
                $this->checkDeleteRatingOrAggregatedRating($rating);
            }
        });

        // all aggregated ratings - fifo
        AggregatedRating::with([])->orderBy('id')->chunk(500, function ($ratings) {
            /** @var Rating $rating */
            foreach ($ratings as $rating) {
                $this->checkDeleteRatingOrAggregatedRating($rating);
            }
        });
    }

    /**
     * @param  Rating|AggregatedRating  $ratingOrAggregatedRating
     *
     * @return bool
     */
    private function checkDeleteRatingOrAggregatedRating(Rating|AggregatedRating $ratingOrAggregatedRating): bool
    {
        $deleteIt = false;
        if (class_exists($ratingOrAggregatedRating->model)) {
            if (!app($ratingOrAggregatedRating->model)->withoutEvents(function () use ($ratingOrAggregatedRating) {
                return app($ratingOrAggregatedRating->model)->whereId($ratingOrAggregatedRating->model_id)->first();
            })
            ) {
                // Log::debug(sprintf("Model '%s':'%s' no longer exists. (%s:%s)", $ratingOrAggregatedRating->model,
                //     $ratingOrAggregatedRating->model_id, get_class($ratingOrAggregatedRating),
                //     $ratingOrAggregatedRating->getKey()));
                $deleteIt = true;
            }
        } else {
            // Log::debug(sprintf("Unknown Model '%s'. (%s:%s)", $ratingOrAggregatedRating->model,
            //     get_class($ratingOrAggregatedRating), $ratingOrAggregatedRating->getKey()));
            $deleteIt = true;
        }

        if ($deleteIt) {
            if ($ratingOrAggregatedRating->delete()) {
                // Log::debug(sprintf("%s:%s deleted.", get_class($ratingOrAggregatedRating),
                //     $ratingOrAggregatedRating->getKey()));
                return true;
            }
        }

        return false;
    }

}
