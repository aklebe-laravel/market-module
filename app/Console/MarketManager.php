<?php

namespace Modules\Market\app\Console;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Modules\SystemBase\app\Services\ModuleService;
use Symfony\Component\Console\Command\Command as CommandAlias;

class MarketManager extends MarketCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'market:manage {subject} {sub_command?} {--ids=} {--since-created=} {--last-seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage items (products,users,stores,...) like "delete" or "repair". ids can be comma separated and/or from to (x-y). See readme for details.';

    /**
     * WARNING! This way objects will be hard deleted if sub_command is 'delete'.
     *
     * Normally, try to avoid hard delete users, products and offers!
     * Use Soft delete instead! (like $user->deleteIn3Steps() or $user->is_deleted=true)
     * A possible reason you want to hard delete a user is on a non production server where users were seeded.
     *
     * @return int
     */
    public function handle(): int
    {
        $subject = $this->argument('subject');
        $subjectList = [$subject];
        $command = $this->argument('sub_command');

        // Choose all models automatically?
        if ($subject == 'model-*') {
            $subjectList = ModuleService::getAllBindings();
            //// if automatic use all models, blacklist ratings because (they cascaded anyway, but won't delete last ratings made/generated)
            //$subjectList = app('system_base')->removeBlacklistItems($subjectList, ['#rating#']);
        }

        if (count($subjectList) > 1) {
            $this->info(count($subjectList)." subjects found ...");
            if (($command == 'info') || ($command == 'status')) {
                $this->info(print_r($subjectList, true));
            }
        }

        // all subjects (usually one) ...
        foreach ($subjectList as $subject) {
            try {
                // for pp bindings see MarketServiceProvider
                $subjectModel = app($subject);
            } catch (Exception) {
                $this->error("Unable to resolve instance of \"{$subject}\"");

                return CommandAlias::FAILURE;
            }

            if (!($subject && ($subjectModel instanceof Model))) {
                $this->error("Invalid model subject \"{$subject}\"");

                return CommandAlias::FAILURE;
            }

            $this->line("");
            $this->line("Starting \"{$subject}\" > \"{$command}\"");
            $this->handleMarketCommand($command, $subjectModel, null, function ($item) use ($command, $subject) {
                switch ($command) {
                    case 'delete':
                        // delete with events (deleting media items, media files, attributes, ...)
                        return $item->delete();

                    case 'info':
                        $this->comment("Info subject {$item->getkey()} ...");
                        break;

                    case 'status':
                        // do nothing per items
                        break;

                    case 'repair':
                        break;

                    default:
                        $this->error(sprintf("Unknown command: '%s' for {$subject} id: %s", $command, $item->getKey()));

                        return CommandAlias::FAILURE;
                }

                return false;
            });
        }

        $this->line("");
        return CommandAlias::SUCCESS;
    }

}
