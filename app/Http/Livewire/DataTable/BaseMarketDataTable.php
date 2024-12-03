<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Illuminate\Database\Eloquent\Builder;

trait BaseMarketDataTable
{
    /**
     * @return array[]
     */
    protected function getFilterOptionsForImages(): array
    {
        return [
            'images'    => [
                'label' => 'With Images',
                'builder' => function(Builder $builder, string $filterElementKey, string $filterValue) {
                    $builder->whereHas('images');
                    //Log::debug("Builder added to product filter '$filterElementKey' to '$filterValue'");
                },
            ],
            'no_images'    => [
                'label' => 'Without Images',
                'builder' => function(Builder $builder, string $filterElementKey, string $filterValue) {
                    $builder->whereDoesntHave('images');
                    //Log::debug("Builder added to product filter '$filterElementKey' to '$filterValue'");
                },
            ],
        ];
    }


}
