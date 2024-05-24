<?php

namespace Modules\Market\database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Market\app\Models\PaymentMethod;
use Modules\Market\app\Models\Product;
use Modules\Market\app\Models\ShippingMethod;
use Modules\WebsiteBase\app\Models\Store;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payments = PaymentMethod::with([])->get();
        $shipments = ShippingMethod::with([])->get();

        /** @var Store $store */
        $store = Store::with([])->get()->first();

        // get all puppets ...
        $users = User::with(['aclGroups.aclResources'])->whereHas('aclGroups.aclResources', function ($query) {
            return $query->where('code', '=', 'puppet');
        })->get();

        for ($i = 0; $i < 200; $i++) {
            Product::factory()
                //                   ->count(10)
                ->create([
                    'store_id'           => $store->id,
                    'user_id'            => $users->count() ? ($users->random()->id ?? null) : null,
                    'payment_method_id'  => $payments->random()->id,
                    'shipping_method_id' => $shipments->random()->id,
                ]);
        }
    }
}
