<?php

namespace Modules\Market\database\seeders;

use Illuminate\Support\Facades\Config;
use Modules\Acl\database\seeders\AclGroupSeeder;
use Modules\Acl\database\seeders\AclResourceSeeder;
use Modules\Market\app\Models\AggregatedRating;
use Modules\Market\app\Models\Category;
use Modules\Market\app\Models\Product;
use Modules\Market\app\Models\User;
use Modules\SystemBase\database\seeders\BaseModelSeeder;
use Modules\WebsiteBase\app\Models\MediaItem;
use Modules\WebsiteBase\database\seeders\AddressSeeder;
use Modules\WebsiteBase\database\seeders\CoreConfigSeeder;
use Modules\WebsiteBase\database\seeders\MediaItemSeeder;
use Modules\WebsiteBase\database\seeders\StoreSeeder;
use Modules\WebsiteBase\database\seeders\TokenSeeder;

class DatabaseSeeder extends BaseModelSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        parent::run();

        $timestamp = date('Y-m-d H:i:s');
        Config::set('seeder_started', $timestamp);

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

        // get all new user ids for some relations below
        $userIds = User::with([])->select('id')->where('created_at', '>=', $timestamp)->pluck('id');


        // @todo: ac resources to groups ...

        // -------------------------------------------------
        // All the user specific stuff
        // -------------------------------------------------
        $this->AssignUserRelations($userIds);

        // -------------------------------------------------
        // Assign products and media items to categories
        // -------------------------------------------------
        $this->AssignCategoryRelations($timestamp);

        // -------------------------------------------------
        // Aggregate ratings
        // -------------------------------------------------
        $this->AggregateRatings($timestamp);

        //
        app('system_base')->logExecutionTime("Seeded ".__METHOD__);
    }

    /**
     * @param  array  $userIds
     *
     * @return void
     */
    private function AssignUserRelations(iterable $userIds): void
    {
        foreach ($userIds as $userId) {
            // -------------------------------------------------
            // Assign media items to products
            // -------------------------------------------------
            $productMediaItems = MediaItem::where('user_id', $userId)->productImages()->get();
            if ($productMediaItems->count()) {
                // Populate the pivot table
                Product::where('user_id', $userId)->each(function ($product) use ($productMediaItems) {
                    $min = config('seeders.users.media_items.count_min_product_images_per_product', 2);
                    if ($min > $productMediaItems->count()) {
                        $min = $productMediaItems->count();
                    }
                    $max = config('seeders.users.media_items.count_max_product_images_per_product', 2);
                    if ($max > $productMediaItems->count()) {
                        $max = $productMediaItems->count();
                    }
                    $imagesPerProduct = rand($min, $max);
                    if ($imagesPerProduct) {
                        // syncWithoutDetaching() better than attach() to avoid errors if items already exists
                        $product->mediaItems()->syncWithoutDetaching($productMediaItems->random($imagesPerProduct)->pluck('id')->toArray());
                    }
                });
            }

            // -------------------------------------------------
            // Assign media items to user
            // -------------------------------------------------
            $avatarMediaItems = MediaItem::where('user_id', $userId)->userAvatars()->get();
            if ($avatarMediaItems->count()) {
                // syncWithoutDetaching() better than attach() to avoid errors if items already exists
                /** @var User $user */
                $user = User::where('id', $userId)->get()->first();
                $user->mediaItems()->syncWithoutDetaching($avatarMediaItems->random(rand(1, ($avatarMediaItems->count() >= 5) ? 5 : $avatarMediaItems->count()))->pluck('id')->toArray());
            }

            // -------------------------------------------------
            // Assign user groups
            // -------------------------------------------------

            // ...

            // -------------------------------------------------
            // Assign aggregated ratings to user
            // -------------------------------------------------
            AggregatedRating::factory()->create([
                'model'          => User::class,
                'model_id'       => $userId,
                'model_sub_code' => 'trust',
            ]);
            AggregatedRating::factory()->create([
                'model'          => User::class,
                'model_id'       => $userId,
                'model_sub_code' => 'well_known',
            ]);
            AggregatedRating::factory()->create([
                'model'          => User::class,
                'model_id'       => $userId,
                'model_sub_code' => 'offer_success',
            ]);

        }
    }

    /**
     * @param  string  $timestamp
     *
     * @return void
     */
    private function AssignCategoryRelations(string $timestamp): void
    {
        // preparing for products ...
        $products = Product::where('created_at', '>=', $timestamp)->select('id')->get();
        $productsCount = $products->count();
        $minProductsPerCategory = config('seeders.categories.count_min_products', 20);
        if ($minProductsPerCategory > $productsCount) {
            $minProductsPerCategory = $productsCount;
        }
        $maxProductsPerCategory = config('seeders.categories.count_max_products', 100);
        if ($maxProductsPerCategory > $productsCount) {
            $maxProductsPerCategory = $productsCount;
        }

        // preparing for media items ...
        $mediaItems = MediaItem::where('created_at', '>=', $timestamp)->where('object_type', '=', MediaItem::OBJECT_TYPE_CATEGORY_IMAGE)->get();
        $mediaItemsCount = $mediaItems->count();
        $minMediaItemsPerCategory = config('seeders.categories.count_min_media_items', 1);
        if ($minMediaItemsPerCategory > $mediaItemsCount) {
            $minMediaItemsPerCategory = $mediaItemsCount;
        }
        $maxMediaItemsPerCategory = config('seeders.categories.count_max_media_items', 3);
        if ($maxMediaItemsPerCategory > $mediaItemsCount) {
            $maxMediaItemsPerCategory = $mediaItemsCount;
        }

        // Get all new categories ...
        Category::where('created_at', '>=', $timestamp)->each(function ($category) use (
            $products,
            $minProductsPerCategory,
            $maxProductsPerCategory,
            $minMediaItemsPerCategory,
            $maxMediaItemsPerCategory,
            $timestamp,
            $mediaItems
        ) {
            // -------------------------------------------------
            // Assign products to categories
            // -------------------------------------------------
            if ($minProductsPerCategory) {
                // syncWithoutDetaching() better than attach() to avoid errors if items already exists
                $category->products()->syncWithoutDetaching($products->random(rand($minProductsPerCategory, $maxProductsPerCategory))->select('id')->pluck('id')->toArray());
            }

            // -------------------------------------------------
            // Assign media items to categories
            // -------------------------------------------------
            if ($minMediaItemsPerCategory) {
                // syncWithoutDetaching() better than attach() to avoid errors if items already exists
                $category->mediaItems()->syncWithoutDetaching($mediaItems->random(rand($minMediaItemsPerCategory, $maxMediaItemsPerCategory))->pluck('id')->toArray());
            }
        });
    }

    /**
     * @param  string  $timestamp
     *
     * @return void
     */
    private function AggregateRatings(string $timestamp): void
    {
        // -------------------------------------------------
        // Assign aggregated ratings to products
        // -------------------------------------------------
        Product::where('created_at', '>=', $timestamp)->each(function ($product) {
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
        // Assign aggregated ratings to categories
        // -------------------------------------------------
        Category::where('created_at', '>=', $timestamp)->select('id')->get()->each(function ($category) {
            AggregatedRating::factory()->create([
                'model'          => Category::class,
                'model_id'       => $category->id,
                'model_sub_code' => 'like',
            ]);
        });
    }

}
