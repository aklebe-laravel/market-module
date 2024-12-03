<?php

namespace Modules\Market\app\Console;

use Illuminate\Console\Command;
use Modules\SystemBase\app\Services\ModelService;

abstract class MarketCommand extends Command
{
    /**
     * Execute the console command.
     *
     * @param $command
     * @param $modelClass
     * @param  callable|null  $callbackBuilder
     * @param  callable|null  $callbackItem
     *
     * @return int
     */
    public function handleMarketCommand($command, $modelClass, callable|null $callbackBuilder, callable|null $callbackItem): int
    {
        $idsParam = $this->option('ids');
        $sinceCreatedParam = $this->option('since-created');

        $total = 0;
        $success = 0;
        $modelService = app(ModelService::class);
        $builder = $modelClass::with([]);
        if ($idsParam) {
            $modelService->PrepareBuilderIdsByParam($builder, $idsParam);
        }
        if ($sinceCreatedParam) {
            $modelService->PrepareBuilderSinceCreated($builder, $sinceCreatedParam);
        }

        // callback builder ...
        if ($callbackBuilder && $callbackBuilder()) {
            // ...
        }

        // callback all items ...
        foreach ($builder->get() as $item) {
            if ($callbackItem && $callbackItem($item)) {
                $success++;
            }
            $total++;
        }


        $taskDone = 'did nothing';
        switch ($command) {
            case 'delete':
                $taskDone = 'deleted';
                break;
            case 'repair':
                $taskDone = 'repaired';
                break;
        }
        $this->comment(sprintf("Items %d/%d: %s", $success, $total, $taskDone));

        return Command::SUCCESS;
    }

}
