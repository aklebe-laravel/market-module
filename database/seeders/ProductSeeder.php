<?php

namespace Modules\Market\database\seeders;

use Illuminate\Support\Facades\Log;
use Modules\Market\app\Models\PaymentMethod;
use Modules\Market\app\Models\Product;
use Modules\Market\app\Models\ShippingMethod;
use Modules\Market\app\Models\User;
use Modules\SystemBase\database\seeders\BaseModelSeeder;
use Modules\WebsiteBase\app\Models\Store;

class ProductSeeder extends BaseModelSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        parent::run();

        $payments = PaymentMethod::with([])
                                 ->get();
        $shipments = ShippingMethod::with([])
                                   ->get();

        /** @var Store $store */
        $store = Store::with([])
                      ->get()
                      ->first();

        // find seeder start
        if (!($seederStarted = config('seeder_started'))) {
            Log::error("No seeder start found.", [__METHOD__]);

            return;
        }

        // get all new generated users
        $userIds = User::with([])
                       ->where('created_at', '>=', $seederStarted)
                       ->pluck('id');
        $productMinCount = config('seeders.users.products.min_count', 2);
        $productMaxCount = config('seeders.users.products.max_count', 6);
        foreach ($userIds as $userId) {
            $this->TryCreateFactories(Product::class, rand($productMinCount, $productMaxCount), fn() => [
                'store_id'           => $store->id,
                'user_id'            => $userId,
                'payment_method_id'  => $payments->random()->id,
                'shipping_method_id' => $shipments->random()->id,
            ]);
        }
    }
}
