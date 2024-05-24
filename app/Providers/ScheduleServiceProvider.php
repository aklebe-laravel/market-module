<?php

namespace Modules\Market\app\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Modules\Market\app\Jobs\CleanupRatingProcess;
use Modules\Market\app\Services\ProductService;
use Modules\Market\app\Services\UserService;
use Modules\SystemBase\app\Providers\Base\ScheduleBaseServiceProvider;

class ScheduleServiceProvider extends ScheduleBaseServiceProvider
{
    protected function bootEnabledSchedule(Schedule $schedule): void
    {
        /**
         * aggregate product rating
         */
        $schedule->call(function () {
            // -------------------------------------
            // start rating aggregation by queue job
            // -------------------------------------
            /** @var ProductService $productService */
            $productService = app(ProductService::class);
            $productService->aggregateRatings(false);  // dont use queue because we are already in background by schedule
        })->everyThirtyMinutes();

        /**
         * aggregate user rating
         */
        $schedule->call(function () {
            // -------------------------------------
            // start rating aggregation by queue job
            // -------------------------------------
            /** @var UserService $userService */
            $userService = app(UserService::class);
            $userService->aggregateRatings(false); // dont use queue because we are already in background by schedule
        })->everyThirtyMinutes();

        /**
         * Scheduling user activation (trader)
         */
        $schedule->call(function () {
            // Log::debug('Scheduling user activation (trader) ...');
            // -------------------------------------
            // new traders
            // -------------------------------------
            /** @var UserService $userService */
            $userService = app(UserService::class);
            $userService->calculateTraders();
        })->everyThirtyMinutes();

        /**
         * Cleanup ratings ith invalid object ids.
         */
        $schedule->call(function () {
            // -------------------------------------
            // start rating cleanup by queue job
            // -------------------------------------
            CleanupRatingProcess::dispatch();
        })->dailyAt('02:45');


        // @todo: remove unused (product-) images

        // @todo: generate (product) flat tables

        // @todo: disable suspect users (also 0 rated users since at least 3 weeks)

    }

    protected function bootDisabledSchedule(Schedule $schedule): void
    {
    }

}
