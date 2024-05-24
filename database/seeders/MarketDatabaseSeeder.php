<?php

namespace Modules\Market\database\seeders;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Acl\database\seeders\AclGroupSeeder;
use Modules\Acl\database\seeders\AclResourceSeeder;
use Modules\Market\app\Models\AggregatedRating;
use Modules\Market\app\Models\Category;
use Modules\Market\app\Models\Product;
use Modules\WebsiteBase\app\Models\MediaItem;
use Modules\WebsiteBase\database\seeders\AddressSeeder;
use Modules\WebsiteBase\database\seeders\CoreConfigSeeder;
use Modules\WebsiteBase\database\seeders\MediaItemSeeder;
use Modules\WebsiteBase\database\seeders\StoreSeeder;
use Modules\WebsiteBase\database\seeders\TokenSeeder;

class MarketDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call([
            UserSeeder::class,
            StoreSeeder::class,
            CoreConfigSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            MediaItemSeeder::class,
            AclResourceSeeder::class,
            AclGroupSeeder::class,
            TokenSeeder::class,
            AddressSeeder::class,
        ]);

        // -------------------------------------------------
        // Assign products to categories
        // -------------------------------------------------
        $products = Product::all();
        // Populate the pivot table
        Category::all()->each(function ($category) use ($products) {
            // syncWithoutDetaching() better than attach() to avoid errors if items already exists
            $category->products()->syncWithoutDetaching($products->random(rand(15, 60))->pluck('id')->toArray());
        });

        // -------------------------------------------------
        // Assign media items to products
        // -------------------------------------------------
        $mediaItems = MediaItem::with([])->where('object_type', '=', MediaItem::OBJECT_TYPE_PRODUCT_IMAGE)->get();
        // Populate the pivot table
        Product::all()->each(function ($product) use ($mediaItems) {
            // syncWithoutDetaching() better than attach() to avoid errors if items already exists
            $product->mediaItems()->syncWithoutDetaching($mediaItems->random(rand(1, 10))->pluck('id')->toArray());
        });

        // -------------------------------------------------
        // Assign aggregated ratings to products
        // -------------------------------------------------
        Product::all()->each(function ($product) {
            AggregatedRating::factory()->create([
                'model'          => Product::class,
                'model_id'       => $product->id,
                'model_sub_code' => Product::RATING_SUB_CODE_PUBLIC_PRODUCT,
            ]);
            AggregatedRating::factory()->create([
                'model'          => Product::class,
                'model_id'       => $product->id,
                'model_sub_code' => Product::RATING_SUB_CODE_CONDITION,
            ]);
        });

        // -------------------------------------------------
        // Assign media items to categories
        // -------------------------------------------------
        $mediaItems = MediaItem::with([])->where('object_type', '=', MediaItem::OBJECT_TYPE_CATEGORY_IMAGE)->get();
        // Populate the pivot table
        Category::all()->each(function ($category) use ($mediaItems) {
            // syncWithoutDetaching() better than attach() to avoid errors if items already exists
            $category->mediaItems()->syncWithoutDetaching($mediaItems->random(rand(1, 10))->pluck('id')->toArray());
        });

        // -------------------------------------------------
        // Assign aggregated ratings to categories
        // -------------------------------------------------
        Category::all()->each(function ($category) {
            AggregatedRating::factory()->create([
                'model'          => Category::class,
                'model_id'       => $category->id,
                'model_sub_code' => 'like',
            ]);
        });

        // -------------------------------------------------
        // Assign media items to user
        // -------------------------------------------------
        $mediaItems = MediaItem::with([])->where('object_type', '=', MediaItem::OBJECT_TYPE_USER_AVATAR)->get();
        // Populate the pivot table
        User::all()->each(function ($user) use ($mediaItems) {
            // syncWithoutDetaching() better than attach() to avoid errors if items already exists
            $user->mediaItems()->syncWithoutDetaching($mediaItems->random(rand(1, 5))->pluck('id')->toArray());
        });

        // -------------------------------------------------
        // Assign aggregated ratings to user
        // -------------------------------------------------
        User::all()->each(function ($user) {
            AggregatedRating::factory()->create([
                'model'          => User::class,
                'model_id'       => $user->id,
                'model_sub_code' => 'trust',
            ]);
            AggregatedRating::factory()->create([
                'model'          => User::class,
                'model_id'       => $user->id,
                'model_sub_code' => 'well_known',
            ]);
            AggregatedRating::factory()->create([
                'model'          => User::class,
                'model_id'       => $user->id,
                'model_sub_code' => 'offer_success',
            ]);
        });

    }
}
