<?php

namespace Modules\Market\app\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Modules\DeployEnv\app\Listeners\ImportRow as ImportRowBase;
use Modules\Market\app\Models\Category;
use Modules\Market\app\Models\MediaItem;
use Modules\Market\app\Models\Product;
use Modules\Market\app\Models\User as MarketUser;
use Modules\WebsiteBase\app\Models\Store;
use Modules\WebsiteBase\app\Services\MediaService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ImportRowMarket extends ImportRowBase
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        parent::__construct();

        $this->columnTypeMap['price'] = 'double';
    }


    /**
     * @param $row
     * @return array
     */
    protected function validateRow(&$row): array
    {
        $validatedRow = [];

        return $validatedRow;
    }

    /**
     * @param  Product|Category|MarketUser  $o
     * @param  array                        $source
     * @param  string                       $sourceKey
     * @param  string|null                  $destKey
     * @param  callable|null                $callback
     * @return bool
     */
    protected function addCustomExtraAttributeIfPresent(Product|Category|MarketUser $o, array &$source,
        string $sourceKey, ?string $destKey = null, callable $callback = null): bool
    {
        if (!isset($source[$sourceKey])) {
            return false;
        }

        if ($destKey === null) {
            $destKey = $sourceKey;
        }

        $o->setExtraAttribute($destKey, $callback());

        return true;
    }

    /**
     * @param  Product|Category|MarketUser  $o
     * @param  array                        $source
     * @param  string                       $sourceKey
     * @param  string|null                  $destKey
     * @param  mixed|null                   $default
     * @return bool
     */
    protected function addBasicExtraAttributeIfPresent(Product|Category|MarketUser $o, array &$source,
        string $sourceKey, ?string $destKey = null, mixed $default = null): bool
    {
        return $this->addCustomExtraAttributeIfPresent($o, $source, $sourceKey, $destKey,
            function () use (&$source, $sourceKey, $default) {
                $v = data_get($source, $sourceKey, $default);
                return $this->typeCast($sourceKey, $v);
            });
    }

    /**
     * @param  Product|Category|MarketUser  $o
     * @param  array                        $row
     * @return void
     */
    protected function setExtraAttributes(Product|Category|MarketUser $o, array $row): void
    {
    }

    /**
     * get user_id by id or email
     *
     * @param  array   $row
     * @param  string  $sourceColumn
     * @return string|int|null
     */
    protected function getCalculatedUserColumnAsId(array &$row, string $sourceColumn = 'user'): string|int|null
    {
        $userId = data_get($row, $sourceColumn);
        if (!$userId) {
            return null;
        }
        if (is_numeric($userId)) {
            return $userId;
        }
        // get by email
        if ($user = app(User::class)->where('email', $userId)->first()) {
            return $user->getKey();
        }
        Log::error(sprintf("User '%s' not found.", $userId));
        return null;
    }

    /**
     * get store_id by id or store code
     *
     * @param  array   $row
     * @param  string  $sourceColumn
     * @return string|int|null
     */
    protected function getCalculatedStoreColumnAsId(array &$row, string $sourceColumn = 'store'): string|int|null
    {
        $storeId = data_get($row, $sourceColumn);
        if (!$storeId) {
            return null;
        }
        if (is_numeric($storeId)) {
            return $storeId;
        }
        // get by code
        if ($store = Store::with([])->where('code', $storeId)->first()) {
            return $store->getKey();
        }
        Log::error(sprintf("Store '%s' not found. Set relation to null.", $storeId));
        return null;
    }

    /**
     * Media items not listed will be detached, but not deleted.
     *
     * @param  Product|Category|MarketUser  $o
     * @param  array                        $row
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function doMediaRelations(Product|Category|MarketUser $o, array &$row): void
    {
        if (!($images = data_get($row, 'images'))) {
            return;
        }

        if (!($images = explode(',', $images))) {
            return;
        }

        /** @var MediaService $mediaService */
        $mediaService = app(MediaService::class);

        $isUser = $o instanceof MarketUser;
        $isProduct = $o instanceof Product;
        $isCategory = $o instanceof Category;
        $userId = ($isProduct) ? $o->user_id : ($isUser ? $o->getKey() : null);
        $storeId = ($isProduct || $isCategory) ? $o->store_id : null;
        $name = ($isProduct || $isUser) ? $o->name : $o->code;

        // add images
        $mediaIds = [];
        $position = 100;
        foreach ($images as $imageFilename) {

            if (!$imageFilename) {
                continue;
            }

            $position += 10;

            /** @var MediaItem $mediaItemFound */
            if ($mediaItemFound = MediaItem::with([])
                ->where('media_type', \Modules\WebsiteBase\app\Models\MediaItem::MEDIA_TYPE_IMAGE)
                ->where('extern_url', $imageFilename)
                ->where('user_id', $userId)
                ->first()) {
                Log::debug("Image already found. Skipped.", [$mediaItemFound->getKey(), $imageFilename]);
                $mediaIds[] = $mediaItemFound->getKey();
                if ($mediaItemFound->position > $position) {
                    $position = $mediaItemFound->position + 10;
                }
                continue;
            }

            if ($content = file_get_contents($imageFilename)) {

                // download file
                $tempImageFilename = tempnam(sys_get_temp_dir(), 'tmp-image');
                file_put_contents($tempImageFilename, $content);

                // create mediaItem
                $createData = [
                    'user_id'          => $userId,
                    'store_id'         => $storeId,
                    'name'             => $name,
                    'extern_url'       => $imageFilename,
                    'description'      => 'Auto generated by import',
                    'meta_description' => 'import',
                    'media_type'       => MediaItem::MEDIA_TYPE_IMAGE,
                    'object_type'      => ($isUser) ? MediaItem::OBJECT_TYPE_USER_AVATAR : ($isCategory ? MediaItem::OBJECT_TYPE_CATEGORY_IMAGE : MediaItem::OBJECT_TYPE_PRODUCT_IMAGE),
                    'position'         => $position,
                ];

                /** @var MediaItem $mediaModel */
                $mediaModel = app('media')->create($createData);
                $mediaService->createMediaFile($mediaModel, $tempImageFilename);
                $mediaIds[] = $mediaModel->getKey();
            }
        }

        $o->mediaItems()->sync($mediaIds);

        // Media items not listed will be detached, but not deleted.
    }

}
