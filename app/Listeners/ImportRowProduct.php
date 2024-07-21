<?php

namespace Modules\Market\app\Listeners;

use Modules\DeployEnv\app\Events\ImportRow;
use Modules\Market\app\Models\Category;
use Modules\Market\app\Models\Product;
use Modules\Market\app\Models\User as MarketUser;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ImportRowProduct extends ImportRowMarket
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        parent::__construct();

        $this->requiredTypesSingular[] = 'product';
    }

    /**
     * Handle the event.
     *
     * @param  ImportRow  $event
     * @return bool  false to stop all following listeners
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(ImportRow $event): bool
    {
        if (!$this->isRequiredType($event->type)) {
            return true;
        }

        $id = data_get($event->row, 'id');
        $sku = data_get($event->row, 'sku');
        if (!$id && !$sku) {
            return true;
        }

        // validate data
        $validated = $this->validateRow($event->row);

        // user is required
        if ($validated['user_id'] === null) {
            return true;
        }

        // get product by id or sku
        $product = Product::with([]);
        if ($id) {
            $product->where('id', $id);
        } elseif ($sku) {
            $product->where('sku', $sku)->where('user_id', $validated['user_id']);
        }

        // save the base product
        /** @var Product $product */
        $product = $product->first();
        if ($product) {
            $this->setExtraAttributes($product, $event->row);
            $product->update($validated);
        } else {
            /** @var Product $product */
            if ($product = Product::create($validated)) {
                $this->setExtraAttributes($product, $event->row);
                $product->saveModelAttributeTypeValues();
            }
        }

        if (!$product || !$product->getKey()) {
            return true;
        }

        if ($product->validateAndAdjustProperties()) {
            $product->save();
        }

        // save the product relations
        $this->doMediaRelations($product, $event->row);
        $this->doCategoryRelations($product, $event->row);

        return true;
    }

    /**
     * @param $row
     * @return array
     */
    protected function validateRow(&$row): array
    {
        $validatedRow = array_merge(parent::validateRow($row), [
            /**
             * get user_id by id or email
             */
            'user_id'  => $this->getCalculatedUserColumnAsId($row),
            /**
             * get store_id by id or store code
             */
            'store_id' => $this->getCalculatedStoreColumnAsId($row),
        ]);

        // Set current user if not explicit specified
        if ($validatedRow['user_id'] === null) {
            $validatedRow['user_id'] = app('website_base_settings')->currentUser()?->getKey() ?? null;
        }

        $this->addBasicColumnIfPresent($row, $validatedRow, 'is_enabled', default: true);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'is_public', default: false);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'is_test', default: false);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'is_individual', default: true);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'product_type');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'sku');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'name');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'system_status');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'short_description');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'description');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'meta_description');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'web_uri');

        $settings = app('market_settings');
        $this->addCustomColumnIfPresent($row, $validatedRow, 'payment_method', 'payment_method_id',
            function () use ($row, $settings) {
                return $settings->getValidPaymentMethods()
                    ->where('code', $row['payment_method'])
                    ->first()
                    ?->getKey() ?? null;
            });
        $this->addCustomColumnIfPresent($row, $validatedRow, 'shipping_method', 'shipping_method_id',
            function () use ($row, $settings) {
                return $settings->getValidShippingMethods()
                    ->where('code', $row['shipping_method'])
                    ->first()
                    ?->getKey() ?? null;
            });

        return $validatedRow;
    }

    /**
     * @param  Product  $product
     * @param  array    $row
     * @return void
     */
    private function doCategoryRelations(Product $product, array &$row): void
    {
        if (!($categories = data_get($row, 'categories'))) {
            return;
        }

        if (!($categories = explode(',', $categories))) {
            return;
        }

        $catIds = [];
        foreach ($categories as $c) {
            $category = Category::with([]);
            if (is_numeric($c)) {
                $category = $category->find($c)->first();
            } else {
                $category = $category->where('code', $c)->first();
            }
            if ($category) {
                $catIds[] = $category->getKey();
            }
        }

        $product->categories()->sync($catIds);

    }

    /**
     * @param  Product|Category|MarketUser  $o
     * @param  array                        $row
     * @return void
     */
    protected function setExtraAttributes(Product|Category|MarketUser $o, array $row): void
    {
        $this->addBasicExtraAttributeIfPresent($o, $row, 'price', default: 0);
        $this->addBasicExtraAttributeIfPresent($o, $row, 'currency');
    }

}
