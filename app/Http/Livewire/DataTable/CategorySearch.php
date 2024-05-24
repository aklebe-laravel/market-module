<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Illuminate\Database\Eloquent\Builder;


class CategorySearch extends Category
{
    public string $modelName = 'Category';

    public string $searchStringLike = '';

    /**
     * Overwrite to init your sort orders before session exists
     * @return void
     */
    protected function initSort(): void
    {
        $this->setSortAllCollections('name', 'asc');
    }

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
        $builder = \Modules\Market\app\Models\Category::getBuilderFrontendItems();

        if ($this->searchStringLike) {
            $builder->where(function (Builder $b) {
                $b->where('name', 'like', $this->searchStringLike);
                $b->orWhere('code', 'like', $this->searchStringLike);
                $b->orWhere('description', 'like', $this->searchStringLike);
                $b->orWhere('meta_description', 'like', $this->searchStringLike);
                $b->orWhere('web_uri', 'like', $this->searchStringLike);
            });
        }

        return $builder;
    }

}
