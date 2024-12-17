<?php

namespace Modules\Market\app\Console;

use Illuminate\Console\Command;
use Modules\WebsiteBase\app\Models\MediaItem as WebsiteBaseMediaItem;
use Modules\WebsiteBase\app\Services\MediaService;
use Symfony\Component\Console\Command\Command as CommandResult;

class Maintenance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'market:maintenance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Maintenance.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $mediaService = app(MediaService::class);

        // for all valid media types ...
        foreach (WebsiteBaseMediaItem::MEDIA_TYPES as $mediaType => $data) {
            $mediaService->deleteUnusedMediaFiles($mediaType);
        }

        //
        app('system_base')->logExecutionTime('Finished maintenance');

        return CommandResult::SUCCESS;
    }

}
