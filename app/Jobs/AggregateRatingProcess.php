<?php

namespace Modules\Market\app\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Market\app\Models\AggregatedRating;
use Modules\Market\app\Models\Product;
use Modules\Market\app\Models\Rating;
use Modules\Market\app\Services\ProductService;

class AggregateRatingProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * if true, the smart updated_At check will be prevented.
     *
     * @var bool
     */
    public bool $forceAllRatings = false;

    /**
     * @var ?\Modules\Market\app\Services\ProductService
     */
    public ?ProductService $productService = null;

    /**
     * @var string|null
     */
    public ?string $ratingClassName = null;

    /**
     * @var array
     */
    public array $modelIds = [];

    /**
     * Create a new job instance.
     *
     * @param  string|null  $ratingClassName  If no class given, rating of all models will be aggregated.
     * @param  array        $modelIds
     */
    public function __construct(?string $ratingClassName = null, array $modelIds = [])
    {
        $this->ratingClassName = $ratingClassName;
        $this->modelIds = $modelIds;
        $this->productService = app(ProductService::class);
    }

    /**
     * Aggregates a rating (user or product) by model id
     *
     * @param  string  $modelClassName
     * @param  int     $modelId
     * @return void
     */
    public function aggregateModelRating(string $modelClassName, int $modelId): void
    {
        // Collect all ratings by all every user ...
        $ratings = Rating::with([])
            ->where('model', $modelClassName)
            ->where('model_id', $modelId)
            ->orderBy('model_id', 'ASC');
        $ratings = $ratings->get();
        if ($ratings->count()) {

            // Group values by sub codes ...
            $values = [];
            foreach ($ratings as $rating) {
                if (!isset($values[$rating->model_sub_code])) {
                    $values[$rating->model_sub_code] = [
                        'value' => 0,
                        'count' => 0,
                    ];
                }
                $values[$rating->model_sub_code]['value'] += (float) $rating->value;
                $values[$rating->model_sub_code]['count']++;
            }

            // Calculate and update aggregated rating if needed
            foreach ($values as $modelSubCode => $data) {
                $value = round($data['value'] / $data['count'], 2);
                AggregatedRating::updateOrCreate([
                    'model'          => $modelClassName,
                    'model_id'       => $modelId,
                    'model_sub_code' => $modelSubCode,
                ], [
                    'value' => $value,
                ]);
            }

        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $latestAggregatedRating = null;
        if (!$this->forceAllRatings) {
            $latestAggregatedRating = AggregatedRating::with([])->orderByDesc('updated_at')->first();
        }
        $latestAggregatedRatingAt = $latestAggregatedRating->updated_at ?? '0000-00-00 00:00:00';

        if ((!$this->ratingClassName) || ($this->ratingClassName === Product::class)) {
            $startTime = microtime(true);

            $products = Product::with([])
                ->whereHas('ratings', function (Builder $query) use ($latestAggregatedRatingAt) {
                    return $query->where('updated_at', '>', $latestAggregatedRatingAt)
                        ->orWhereNull('updated_at');
                })
                ->pluck('id');

            if ($products->count()) {
                // Log::debug(sprintf("Rated products found: %d. Aggregating ...", $products->count()));
                foreach ($products as $productId) {
                    $this->aggregateModelRating(Product::class, $productId);
                }
            }
            app('system_base')->logExecutionTime('aggregate product rating', $startTime);
        }

        if ((!$this->ratingClassName) || ($this->ratingClassName === User::class)) {
            $startTime = microtime(true);

            $users = app(User::class)
                ->with([])
                ->whereHas('ratings', function (Builder $query) use ($latestAggregatedRatingAt) {
                    return $query->where('updated_at', '>', $latestAggregatedRatingAt)
                        ->orWhereNull('updated_at');
                })
                ->pluck('id');

            if ($users->count()) {
                // Log::debug(sprintf("Rated users found: %d. Aggregating ...", $users->count()));
                foreach ($users as $userId) {
                    $this->aggregateModelRating(User::class, $userId);
                }
            }
            app('system_base')->logExecutionTime('aggregate user rating', $startTime);
        }


    }

}
