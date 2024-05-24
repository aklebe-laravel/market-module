<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Illuminate\Database\Eloquent\Builder;
use Modules\WebsiteBase\app\Http\Livewire\DataTable\MediaItemImage;

class MediaItemImageProductImage extends MediaItemImage
{
    /**
     * The base builder before all filter manipulations.
     * Usually used for all collections (default, selected, unselected), but can overwritten.
     *
     * @param  string  $collectionName
     *
     * @return Builder|null
     */
    public function getBaseBuilder(string $collectionName): ?Builder
    {
        $builder = parent::getBaseBuilder($collectionName);
        $builder->where('object_type', '=', \Modules\WebsiteBase\app\Models\MediaItem::OBJECT_TYPE_PRODUCT_IMAGE);
        return $builder;
    }


}
