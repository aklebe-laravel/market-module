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
    protected $signature = 'market:maintenance {--simulate}';

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
        $simulate = $this->option('simulate');

        $mediaService = app(MediaService::class);

        if (!app('system_base')->isEnvGroupImportant()) {
            if (!$this->confirm("Its not recommend to delete unused media files on this stage. Delete anyway?")) {
                $this->warn("Skipping this stage!");

                return CommandResult::SUCCESS;
            }
        }

        // for all valid media types ...
        foreach (WebsiteBaseMediaItem::MEDIA_TYPES as $mediaType => $data) {
            $result = $mediaService->deleteUnusedMediaFiles($mediaType, $simulate);
            $this->comment(json_encode($result, JSON_PRETTY_PRINT));
        }

        //
        app('system_base')->logExecutionTime('Finished maintenance');

        return CommandResult::SUCCESS;
    }

}
