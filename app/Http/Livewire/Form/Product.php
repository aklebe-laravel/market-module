<?php

namespace Modules\Market\app\Http\Livewire\Form;

use Modules\Form\app\Http\Livewire\Form\Base\ModelBase;

class Product extends ModelBase
{
    /**
     * Add stuff like messagebox buttons here
     *
     * @return void
     */
    protected function initBooted(): void
    {
        parent::initBooted();

        $this->addMessageBoxButton('accept-rating', 'website-base');
    }


}
