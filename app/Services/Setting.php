<?php

namespace Modules\Market\app\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Acl\app\Services\UserService;
use Modules\Market\app\Models\Category;
use Modules\Market\app\Models\PaymentMethod;
use Modules\Market\app\Models\ShippingMethod;
use Modules\Market\app\Models\ShoppingCart;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spatie\Navigation\Section;

class Setting
{
    protected ?\Modules\WebsiteBase\app\Services\Setting $websiteBaseSetting = null;
    protected ?PaymentMethod $defaultPaymentMethod = null;
    protected ?ShippingMethod $defaultShippingMethod = null;
    protected ?Collection $validPaymentMethods = null;
    protected ?Collection $validShippingMethods = null;

    /**
     * @var ShoppingCart|null
     */
    protected ?ShoppingCart $shoppingCart = null;

    public function __construct()
    {
        $this->websiteBaseSetting = app('website_base_settings');
    }

    /**
     * @param  bool  $forceReload
     *
     * @return ShoppingCart
     */
    public function getCurrentShoppingCart(bool $forceReload = false): ShoppingCart
    {
        // Not created yet ...
        if (!$this->shoppingCart || $forceReload) {
            $carts = ShoppingCart::with(['shoppingCartItems']);
            if (Auth::check()) {
                // get by user or by session
                $carts->where('user_id', '=', Auth::id())->orWhere('session_token', '=', session()->getId());
            } else {
                // get by session only
                $carts->where('session_token', '=', session()->getId());
            }

            if ($carts->get() && $carts->count() > 0) {

                // here we got all carts by user and by session

                // @todo: select the right one if multiple exist ...
                // @todo: merge carts here

                $this->shoppingCart = $carts->orderBy('updated_at', 'DESC')->first();

            } else {

                $this->shoppingCart = ShoppingCart::make([
                    'session_token' => session()->getId(),
                    'store_id'      => $this->websiteBaseSetting->getStoreId(),
                    'user_id'       => Auth::id(),
                ]);

            }

        }

        return $this->shoppingCart;
    }

    protected function mergeCarts($cart1, $cart2)
    {
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createNavigation(): void
    {
        // Searching for Navi "Categories" and add the Items by DB
        $this->createCategoryNavigation();
    }

    /**
     * Searching for Navi "Categories" and add the Items by DB
     *
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createCategoryNavigation(): void
    {
        $navigation = $this->websiteBaseSetting->getNavigation();

        /** @var Section $section */
        $section = null;
        // Searching for Home ...
        foreach ($navigation->children as $child) {
            if ($child->title === '{{Categories}}') {
                $section = $child;
                break;
            }
        }

        if (!$section) {
            return;
        }

        //
        $navigationCategories = Category::with([]);
        $navigationCategories->where(function (Builder $b) {
            $b->whereDoesntHave('parents');
        })->where('store_id', '=', $this->websiteBaseSetting->getStoreId())->orderBy('name');
        $navigationCategories = $navigationCategories->get();

        foreach ($navigationCategories as $category) {
            $section->add($category->name, route('category-products', ['category' => $category->web_uri]),
                function ($section2) use ($category) {

                    $section2->attributes['icon_class'] = 'bi bi-list';
                    $section2->attributes['id'] = 'nav-cat-'.$category->getKey();
                    foreach ($category->children as $category2) {
                        $section2->add($category2->name,
                            route('category-products', ['category' => $category2->web_uri]),
                            function ($section3) use ($category2) {
                                $section3->attributes['icon_class'] = 'bi bi-list';
                                $section3->attributes['id'] = 'nav-cat-'.$category2->getKey();
                            });
                    }

                });
        }
    }

    /**
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function canShowRating(): bool
    {
        /** @var \Modules\Market\app\Services\UserService $userService */
        $userService = app(UserService::class);

        if (app('website_base_config')->getValue('site.rating.enabled', true)
            && $userService->hasUserResource(Auth::user(),
                'rating.visible')
        ) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function canShowProductRating(): bool
    {
        /** @var \Modules\Market\app\Services\UserService $userService */
        $userService = app(UserService::class);

        if ($this->canShowRating()
            && app('website_base_config')->getValue('product.rating.enabled',
                true)
            && $userService->hasUserResource(Auth::user(), 'rating.product.visible')
        ) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function canShowUserRating(): bool
    {
        if ($this->canShowRating() && app('website_base_config')->getValue('user.rating.enabled', true)
            && Auth::user()
                ->hasAclResource('rating.user.visible')
        ) {
            return true;
        }

        return false;
    }

    /**
     * @return PaymentMethod|null
     */
    public function getDefaultPaymentMethod(): ?PaymentMethod
    {
        if (!$this->defaultPaymentMethod) {
            $this->defaultPaymentMethod = PaymentMethod::with([])
                ->where('code', PaymentMethod::PAYMENT_METHOD_FREE)
                ->first();
        }

        return $this->defaultPaymentMethod;
    }

    /**
     * @return ShippingMethod|null
     */
    public function getDefaultShippingMethod(): ?ShippingMethod
    {
        if (!$this->defaultShippingMethod) {
            $this->defaultShippingMethod = ShippingMethod::with([])
                ->where('code', ShippingMethod::SHIPPING_METHOD_SELF_COLLECT)
                ->first();
        }

        return $this->defaultShippingMethod;
    }

    /**
     * @return Collection
     */
    public function getValidPaymentMethods(): Collection
    {
        if (!$this->validPaymentMethods) {
            $this->validPaymentMethods = PaymentMethod::with([])->get();
        }

        return $this->validPaymentMethods;
    }

    /**
     * @return Collection
     */
    public function getValidShippingMethods(): Collection
    {
        if (!$this->validShippingMethods) {
            $this->validShippingMethods = ShippingMethod::with([])->get();
        }

        return $this->validShippingMethods;
    }

}