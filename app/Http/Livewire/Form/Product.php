<?php

namespace Modules\Market\app\Http\Livewire\Form;

use Modules\Form\app\Http\Livewire\Form\Base\ModelBase;

class Product extends ModelBase
{
    /**
     * @return void
     */
    protected function initLiveFilters(): void
    {
        $this->addViewModeFilter();
    }
}
