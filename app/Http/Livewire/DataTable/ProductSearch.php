<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Illuminate\Database\Eloquent\Builder;
use Modules\Market\app\Models\Product as ProductModel;


class ProductSearch extends Product
{
    /**
     * @var string
     */
    public string $eloquentModelName = ProductModel::class;

    /**
     * @var string
     */
    public string $searchStringLike = '';

    /**
     * Overwrite to init your sort orders before session exists
     *
     * @return void
     */
    protected function initSort(): void
    {
        $this->setSortAllCollections('name', 'asc');
    }

    /**
     * The base builder before all filter manipulations.
     * Usually used for all collections (default, selected, unselected), but can be overwritten.
     *
     * @param  string  $collectionName
     *
     * @return Builder|null
     */
    public function getBaseBuilder(string $collectionName): ?Builder
    {
        $builder = ProductModel::getBuilderFrontendItems();

        if ($this->searchStringLike) {
            $builder->where(function (Builder $b) {
                $b->where('name', 'like', $this->searchStringLike);
                $b->orWhere('short_description', 'like', $this->searchStringLike);
                $b->orWhere('description', 'like', $this->searchStringLike);
                $b->orWhere('meta_description', 'like', $this->searchStringLike);
                $b->orWhere('web_uri', 'like', $this->searchStringLike);
            });
        }

        return $builder;
    }

}
