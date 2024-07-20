<?php

namespace Modules\Market\app\Console;

use Illuminate\Console\Command;
use Modules\Market\app\Models\Product;
use Modules\Market\app\Services\ProductService;

class MarketProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'market:products {product_command?} {--ids=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Product commands like delete. ids can be comma separated or from to (x-y). See readme for more.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var ProductService $productService */
        $productService = app(ProductService::class);
        $command = $this->argument('product_command');
        $productIdArg = $this->option('ids');

        // can still contains x-y
        $productIds = explode(',', $productIdArg);

        $total = 0;
        $success = 0;
        foreach ($productIds as $productId) {
            if (!($productId = trim($productId))) {
                continue;
            }
            $products = Product::with([]);
            $between = explode('-', $productId);
            if (count($between) === 2) {
                if (!$between[0]) { // "-y"
                    $products->where('id', '<=', $between[1]);
                } elseif (!$between[1]) { // "x-"
                    $products->where('id', '>=', $between[0]);
                } else { // "x-y"
                    $products->whereBetween('id', $between);
                }
            } else {
                $products->where('id', $productId);
            }

            foreach ($products->get() as $product) {
                switch ($command) {
                    case 'delete':
                        if ($productService->deleteProduct($product)) {
                            $success++;
                        }
                        break;

                    case 'repair':
                        break;

                    default:
                        $this->getOutput()->writeln(sprintf("Unknown command: '%s' for product id: %s", $command,
                            $product->getKey()));
                        break;
                }

                $total++;
            }

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
        $this->getOutput()->writeln(sprintf("Products %d/%d: %s", $success, $total, $taskDone));

        return Command::SUCCESS;
    }

}
