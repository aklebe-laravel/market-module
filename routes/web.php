<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Market\app\Http\Controllers\CategoryProductController;
use Modules\Market\app\Http\Controllers\OfferController;
use Modules\Market\app\Http\Controllers\SearchController;
use Modules\Market\app\Http\Controllers\ShoppingCartController;
use Modules\WebsiteBase\app\Http\Middleware\StoreUserValid;
use Modules\WebsiteBase\app\Services\WebsiteService;

//
$forceAuthMiddleware = [
    'auth',
    StoreUserValid::class
];
/** @var WebsiteService $websiteService */
$websiteService = app(WebsiteService::class);
$defaultMiddleware = $websiteService->getDefaultMiddleware();

/**
 * In this group we need staff user is logged in.
 */
Route::group(['middleware' => [\Modules\Acl\app\Http\Middleware\StaffUserPresent::class]], function () {
    // nothing so far ...
});

/**
 * In this group we need auth and trader is logged in.
 */
Route::group(['middleware' => $forceAuthMiddleware], function () {

    Route::get('offer/potential', [
        OfferController::class,
        'potential'
    ])->name('offer.potential');

});

/**
 * $defaultMiddleware depends on config setting.
 * Store settings can be public or wanted auth and trader are present.
 */
Route::group(['middleware' => $defaultMiddleware], function () {

    Route::get('/', [CategoryProductController::class, 'show'])->name('home');;

    Route::get('/categories/{category?}', function ($categoryId = 0) {

        $children = [];
        if (!empty($categoryId)) {
            if ($category = \Modules\Market\app\Models\Category::with([])->loadByFrontend($categoryId, 'web_uri')->first()) {
                $children = $category->children()->get();
            } else {
                app()->abort(404);
            }
        }

        return view('website-base::page', [
            'title'       => __('Categories'),
            'contentView' => 'market::categories',
            'categoryId'  => $categoryId,
            'children'    => $children,
        ]);
    })->name('category');

    Route::get('/category-products/{category?}', [
        CategoryProductController::class,
        'show'
    ])->name('category-products');

    Route::get('/product/{product?}', function ($productId = 0) {

        $product = \Modules\Market\app\Models\Product::with([])->loadByFrontend($productId, 'web_uri')->first();

        // is $product valid?
        if (!$product || !$product->salable) {
            app()->abort(404);
        }

        return view('website-base::page', [
            'title'       => $product->name,
            'contentView' => 'market::product',
            'product'     => $product
        ]);
    })->name('product');

    Route::get('get-form-rating/product/{id}', [\Modules\Market\app\Http\Controllers\RatingController::class, 'showProduct'])
        ->name('get.form.rating.product');
    Route::get('get-form-rating/user/{id}', [\Modules\Market\app\Http\Controllers\RatingController::class, 'showUser'])
        ->name('get.form.rating.user');
    Route::post('submit-form-rating', [\Modules\Market\app\Http\Controllers\RatingController::class, 'setProductRating'])
        ->name('submit.form.rating.product');

    Route::post('cart/add-product', [ShoppingCartController::class, 'addProduct'])->name('cart.add-product');

    Route::post('cart/remove-product', [ShoppingCartController::class, 'removeProduct'])->name('cart.remove-product');

    Route::post('cart/remove-item', [ShoppingCartController::class, 'removeItem'])->name('cart.remove-item');

    Route::get('/cart', function () {

        return view('website-base::page', [
            'title'         => 'Cart',
            'contentView'   => 'website-base::components.data-tables.tables.dt-simple',
            'livewireTable' => 'market::data-table.shopping-cart-item',
            'footerView'    => 'market::inc.shopping-cart.actions',
        ]);

    })->name('shopping-cart');

    // ------------------------------------------------------------------------------
    // Search
    // ------------------------------------------------------------------------------
    Route::post('/search', [SearchController::class, 'find'])->name('search');

});

/**
 * In this group we don't need any permissions.
 * Same as login, info pages or something like this.
 */
Route::group(['middleware' => []], function () {

    // ------------------------------------------------------------------------------
    // Get Cart should be allowed for everyone to avoid redirects while expecting json
    // ------------------------------------------------------------------------------
    Route::get('cart/get/{id}', [
        ShoppingCartController::class,
        'get'
    ])->name('cart.get');


});
