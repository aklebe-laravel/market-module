<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Illuminate\Database\Eloquent\Builder;
use Modules\WebsiteBase\app\Http\Livewire\DataTable\MediaItemImage;
use Modules\WebsiteBase\app\Models\MediaItem;

class MediaItemImageCategory extends MediaItemImage
{
    /**
     * The base builder before all filter manipulations.
     * Usually used for all collections (default, selected, unselected), but can be overwritten.
     *
     * @param  string  $collectionName
     *
     * @return Builder|null
     * @throws \Exception
     */
    public function getBaseBuilder(string $collectionName): ?Builder
    {
        $builder = parent::getBaseBuilder($collectionName);

        return $builder->where('object_type', '=', MediaItem::OBJECT_TYPE_CATEGORY_IMAGE);
    }
}
